from flask import Flask, jsonify, request  # Import Flask untuk membuat API dan modul JSON untuk respons
import pandas as pd  # Pandas digunakan untuk membaca dan memproses data CSV
import math  # Math digunakan untuk perhitungan matematis
import logging  # Logging digunakan untuk mencatat informasi/debugging

# Inisialisasi aplikasi Flask
app = Flask(__name__)
# Atur tingkat logging untuk mencatat informasi selama aplikasi berjalan
logging.basicConfig(level=logging.DEBUG)

# Blok untuk memuat data dari file CSV
try:
    # Membaca dataset dari file CSV
    data = pd.read_csv('recipe_data.csv')
    # Normalisasi kolom 'Recipe_Name' menjadi huruf kecil untuk pencocokan yang konsisten
    data['Recipe_Name'] = data['Recipe_Name'].str.strip().str.lower()

# Tangani kasus ketika file CSV tidak ditemukan
except FileNotFoundError:
    logging.error("recipe_data.csv not found!")  # Catat error jika file tidak ditemukan
    data = pd.DataFrame()  # Inisialisasi dataset kosong
except Exception as e:  # Tangkap error lain saat membaca file
    logging.exception(f"Error loading data: {e}")  # Catat detail error
    data = pd.DataFrame()  # Inisialisasi dataset kosong

# Endpoint API untuk rekomendasi berdasarkan daftar bahan (ingredients)
@app.route('/api/recommendation-by-ingredients', methods=['POST'])
def recommendation_by_ingredients():
    # Ambil data JSON dari request
    req_data = request.get_json()
    if not req_data:  # Jika tidak ada data JSON, kirim error
        return jsonify({"error": "No JSON data provided", "recommendations": []}), 400

    # Ambil daftar bahan dari JSON
    input_ingredients = req_data.get('ingredients', [])

    # Validasi input: pastikan ada minimal dua bahan
    if not isinstance(input_ingredients, list) or len(input_ingredients) < 2:
        return jsonify({"error": "Please provide at least two ingredients.", "recommendations": []}), 400

    # Periksa apakah dataset tersedia
    if data.empty:
        return jsonify({"error": "Dataset is not available.", "recommendations": []}), 500

    try:
        # Normalisasi input pengguna: ubah menjadi huruf kecil dan hapus spasi
        input_set = set([ingredient.strip().lower() for ingredient in input_ingredients])

        recommendations = []  # Daftar untuk menyimpan rekomendasi
        for _, row in data.iterrows():  # Iterasi setiap baris dalam dataset
            # Normalisasi bahan resep dari dataset menjadi set
            recipe_ingredients = set(map(str.strip, row['Ingredients_List'].lower().split(", ")))

            # Hitung intersection (bahan yang sama) dan union (gabungan bahan)
            intersection = input_set & recipe_ingredients
            union = input_set | recipe_ingredients

            # Penjelasan Jaccard Similarity:
            # Formula dasar: |A ∩ B| / |A ∪ B|
            # - |A ∩ B|: Jumlah bahan yang ada di input pengguna dan di resep.
            # - |A ∪ B|: Jumlah total bahan unik di input pengguna dan resep.
            # Penalti diterapkan berdasarkan bahan yang tidak cocok untuk mengurangi nilai similarity.

            unmatched = len(union) - len(intersection)  # Hitung bahan yang tidak cocok
            similarity = len(intersection) / (len(union) + unmatched * 0.5)  # Faktor penalti 0.5

            # Tambahkan ke rekomendasi jika similarity > 0
            if similarity > 0:
                recommendations.append({
                    "Recipe_Name": row['Recipe_Name'].title(),  # Nama resep dalam format Title Case
                    "Cuisine_Type": row.get('Cuisine_Type', None),  # Tipe masakan
                    "Difficulty_Level": row.get('Difficulty_Level', None),  # Tingkat kesulitan
                    "Ingredients_List": row['Ingredients_List'],  # Daftar bahan resep
                    "Cooking_Step": row['Cooking_Step'], #
                    "similarity": round(similarity * 100, 2)  # Similarity dalam persen
                })

        # Urutkan rekomendasi berdasarkan similarity (tertinggi ke terendah)
        recommendations.sort(key=lambda x: x['similarity'], reverse=True)
        # Ambil hanya 50 rekomendasi teratas
        recommendations = recommendations[:50]

        # Kembalikan input pengguna dan rekomendasi dalam format JSON
        return jsonify({
            "input_ingredients": list(input_set),  # Daftar bahan input pengguna
            "recommendations": recommendations  # Daftar rekomendasi
        })

    # Tangani error yang terjadi selama proses rekomendasi
    except Exception as e:
        logging.exception(f"Error during recommendation by ingredients: {e}")
        return jsonify({"error": "An error occurred during recommendation.", "recommendations": []}), 500

# Jalankan aplikasi Flask
if __name__ == '__main__':
    app.run(debug=True, port=5000)  # Aplikasi berjalan di port 5000 dengan mode debug aktif
