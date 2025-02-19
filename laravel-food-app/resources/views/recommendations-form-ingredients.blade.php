@extends('layout.app')

@section('content')
<div class="container mt-5"> 
    <div class="card shadow-lg p-4">
        <h1 class="text-center mb-4">Recommendation by Ingredients</h1> <!-- Judul di tengah -->

        <!-- Form untuk menginput bahan -->
        <form action="{{ route('recommendations-ingredients') }}" method="POST"> <!-- Rute form untuk rekomendasi bahan -->
            @csrf <!-- Menyertakan token CSRF untuk keamanan -->

            <!-- Input bahan -->
            <div class="mb-3">
                <label for="ingredients" class="form-label">Enter Ingredients (comma-separated):</label> <!-- Label input -->
                <input type="text" class="form-control" id="ingredients" name="ingredients" placeholder="e.g., Chicken, Tomato, Garlic" required> <!-- Input teks untuk bahan -->
            </div>

            <!-- Tombol Submit -->
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Get Recommendations</button> <!-- Tombol untuk mengirim form -->
            </div>
        </form>
    </div>
</div>

<!-- Tombol kembali -->
<a href="{{ url()->previous() }}" class="btn btn-back d-block mx-auto">Back</a> <!-- Tombol navigasi kembali -->

@endsection
