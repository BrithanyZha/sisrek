import requests
import pandas as pd

# Fungsi untuk menghitung metrik evaluasi (Precision, Recall, dan F1-Score)
# Precision: Seberapa banyak rekomendasi yang benar dari semua rekomendasi.
# Recall: Seberapa banyak resep relevan yang berhasil ditemukan.
# F1-Score: Kombinasi antara Precision dan Recall.
def calculate_metrics(true_relevant, recommended_relevant, all_relevant):
    # Buat nama resep menjadi huruf kecil dan hapus spasi untuk konsistensi.
    true_relevant_set = set([name.lower().strip() for name in true_relevant])
    recommended_relevant_set = set([name.lower().strip() for name in recommended_relevant])
    all_relevant_set = set([name.lower().strip() for name in all_relevant])

    # Hitung jumlah yang benar (TP), salah (FP), dan yang terlewat (FN).
    tp = len(true_relevant_set & recommended_relevant_set)  # Resep yang relevan dan direkomendasikan
    fp = len(recommended_relevant_set - true_relevant_set)  # Resep yang direkomendasikan tapi tidak relevan
    fn = len(all_relevant_set - recommended_relevant_set)  # Resep yang relevan tapi tidak direkomendasikan

    # Hitung Precision, Recall, dan F1-Score.
    precision = tp / (tp + fp) if (tp + fp) > 0 else 0
    recall = tp / (tp + fn) if (tp + fn) > 0 else 0
    f1_score = 2 * (precision * recall) / (precision + recall) if (precision + recall) > 0 else 0
    return precision, recall, f1_score

# Fungsi untuk menghitung tingkat kesamaan (similarity) antara bahan pengguna dan bahan resep.
def calculate_similarity(input_ingredients, recipe_ingredients):
    # Ubah bahan menjadi format "set" untuk menghitung kesamaan.
    input_set = set(input_ingredients)
    recipe_set = set(recipe_ingredients.split(', ')) if isinstance(recipe_ingredients, str) else set()

    # Hitung bahan yang sama (intersection) dan semua bahan unik (union).
    intersection = len(input_set & recipe_set)  # Jumlah bahan yang sama
    union = input_set | recipe_set  # Gabungan semua bahan

    # Tambahkan penalti untuk bahan yang tidak cocok.
    unmatched = len(union) - intersection  # Jumlah bahan yang tidak cocok
    similarity = intersection / (len(union) + unmatched * 0.5) if (len(union) + unmatched * 0.5) > 0 else 0
    return similarity

# Baca dataset resep dari file CSV.
dataset_path = 'recipe_data.csv'  # Path file dataset
data = pd.read_csv(dataset_path)

# Normalisasi nama resep dan daftar bahan untuk memastikan konsistensi.
data['Recipe_Name'] = data['Recipe_Name'].str.strip().str.lower()
data['Ingredients_List'] = data['Ingredients_List'].str.strip().str.lower()

# Input bahan dari pengguna.
input_ingredients = ["chicken", "tomato", "garlic"]

# Cari semua resep yang mengandung minimal satu bahan dari input pengguna.
relevant_recipes = data[data['Ingredients_List'].apply(
    lambda x: any(ingredient in x for ingredient in input_ingredients)
)]
print("Resep yang mengandung bahan input:")
print(relevant_recipes)

# Tentukan resep relevan berdasarkan ambang batas (threshold) similarity.
threshold = 0.2  # Ambang batas similarity
data['Similarity'] = data['Ingredients_List'].apply(
    lambda x: calculate_similarity(input_ingredients, x)
)
true_relevant = data[data['Similarity'] >= threshold]['Recipe_Name'].tolist()  # Resep yang relevan
all_relevant = data[data['Ingredients_List'].apply(
    lambda x: any(ingredient in x for ingredient in input_ingredients)
)]['Recipe_Name'].tolist()  # Semua resep dengan bahan input

# Kirim permintaan ke API untuk mendapatkan rekomendasi.
url = "http://localhost:5000/api/recommendation-by-ingredients"  # Pastikan API Flask berjalan di localhost:5000
response = requests.post(url, json={"ingredients": input_ingredients})

if response.status_code == 200:
    # Ambil hasil rekomendasi dari API dalam format JSON.
    data_response = response.json()
    recommendations = data_response.get('recommendations', [])

    # Validasi rekomendasi berdasarkan similarity.
    valid_recommendations = []
    for rec in recommendations:
        recipe_name = rec['Recipe_Name'].lower()  # Ubah nama resep menjadi huruf kecil
        recipe_row = data[data['Recipe_Name'] == recipe_name]  # Cari resep di dataset
        if not recipe_row.empty:
            ingredients = recipe_row.iloc[0]['Ingredients_List'].split(', ')  # Daftar bahan resep
            ingredient_similarity = calculate_similarity(input_ingredients, ', '.join(ingredients))  # Hitung similarity
            if ingredient_similarity > threshold:  # Validasi similarity dengan threshold
                valid_recommendations.append({
                    "Recipe_Name": recipe_name.title(),  # Ubah nama resep ke format Title Case
                    "System_Similarity": rec['similarity'],  # Similarity dari API
                    "Ingredient_Similarity": ingredient_similarity  # Similarity dari validasi pengujian
                })

    # Urutkan rekomendasi berdasarkan similarity tertinggi ke terendah.
    valid_recommendations.sort(key=lambda x: x['Ingredient_Similarity'], reverse=True)

    # Ambil nama resep yang valid.
    validated_recommended_relevant = [rec['Recipe_Name'] for rec in valid_recommendations]

    # Hitung metrik evaluasi.
    precision, recall, f1_score = calculate_metrics(true_relevant, validated_recommended_relevant, all_relevant)

    # Tampilkan hasil evaluasi.
    print("\nMetrik Evaluasi:")
    print(f"Precision: {precision:.2f}")  # Akurasi rekomendasi relevan
    print(f"Recall: {recall:.2f}")  # Cakupan resep relevan yang ditemukan
    print(f"F1-Score: {f1_score:.2f}")  # Kombinasi antara Precision dan Recall

    # Tampilkan rekomendasi yang valid (10 teratas).
    print("\nRekomendasi yang Valid (Urut dari Similarity Tertinggi):")
    for rec in valid_recommendations[:10]:  # Tampilkan maksimal 10 rekomendasi
        print(f"Recipe: {rec['Recipe_Name']}, System Similarity: {rec['System_Similarity']}%, Ingredient Relevance: {rec['Ingredient_Similarity']:.2f}")
else:
    print("Terjadi kesalahan pada API.")
