<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FoodController extends Controller
{
    // Fungsi untuk menampilkan halaman dashboard
    public function dashboard()
    {
        return view('dashboard'); // Mengarahkan ke tampilan 'dashboard'
    }

    // Fungsi untuk menampilkan formulir input bahan untuk rekomendasi
    public function formRecommendationIngredients()
    {
        return view('recommendations-form-ingredients'); // Mengarahkan ke formulir 'recommendations-form-ingredients'
    }

    // Fungsi untuk memberikan rekomendasi berdasarkan bahan yang diinput
    public function recommendationByIngredients(Request $request)
    {
        $ingredients = $request->input('ingredients', ''); // Mengambil input bahan dari request
        
        // Jika input kosong, kembalikan error ke tampilan
        if (empty($ingredients)) {
            return view('recommendations-ingredients', ['error' => 'Please enter at least two ingredient.']);
        }

        // Mengubah string input bahan menjadi array
        $ingredientsArray = array_map('trim', explode(',', $ingredients));
        
        // Mengirimkan data ke API Flask untuk mendapatkan rekomendasi
        $response = Http::post(env('FLASK_API_URL') . '/recommendation-by-ingredients', [
            'ingredients' => $ingredientsArray,
        ]);

        // Jika respons berhasil, olah data dan tampilkan di halaman
        if ($response->successful()) {
            $data = $response->json(); // Ambil data JSON dari respons

            $inputIngredients = $data['input_ingredients'] ?? []; // Bahan input yang diproses
            $recommendations = $data['recommendations'] ?? []; // Rekomendasi dari API

            // Format Cooking_Step agar setiap langkah ditampilkan di baris baru
            foreach ($recommendations as &$recommendation) {
                if (isset($recommendation['Cooking_Step'])) {
                    // Pecah langkah berdasarkan "Step X:"
                    $recommendation['Cooking_Step'] = preg_replace('/(Step \d+:)/', "\n$1", $recommendation['Cooking_Step']);
                }
            }

            return view('recommendations-ingredients', compact('inputIngredients', 'recommendations'));
        } else {
            // Jika gagal, tampilkan pesan error
            return view('recommendations-ingredients', ['error' => 'Unable to fetch recommendations.']);
        }
    }
}
