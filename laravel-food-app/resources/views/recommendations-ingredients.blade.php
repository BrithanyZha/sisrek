@extends('layout.app')

@section('content')
<div class="container my-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-body p-5">
            <h1 class="text-center text-gradient fw-bold mb-4">Recommendation Results by Ingredients</h1>
            
            @if(isset($error))
                <div class="alert alert-danger text-center">{{ $error }}</div>
            @else
                <div class="mb-4">
                    <h3 class="text-center text-muted">Input Ingredients</h3> <!-- Subjudul untuk bahan input -->
                    <p class="text-center text-muted fs-5">{{ implode(", ", $inputIngredients) }}</p> <!-- Menampilkan bahan input -->
                </div>

      

                <!-- Interactive Table -->
                <div class="table-responsive"> <!-- Tabel dengan scroll jika terlalu lebar -->
                    <table class="table table-hover table-bordered align-middle" id="recommendation-table"> <!-- Tabel hasil rekomendasi -->
                        <thead class="table-light">
                            <tr class="text-center"> <!-- Header tabel -->
                                <th onclick="sortTable(0)">Name</th> <!-- Kolom nama yang bisa diurutkan -->
                                <th onclick="sortTable(1)">Cuisine Type</th> <!-- Kolom jenis masakan -->
                                <th onclick="sortTable(2)">Difficulty Level</th> <!-- Kolom tingkat kesulitan -->
                                <th>Ingredients</th> <!-- Kolom daftar bahan -->
                                <th>Cooking Step</th>
                                <th onclick="sortTable(4)">Similarity</th> <!-- Kolom tingkat kesamaan -->
                            </tr>
                        </thead>
                        <tbody id="table-body"> <!-- Isi tabel -->
                            @foreach ($recommendations as $recommendation) <!-- Iterasi hasil rekomendasi -->
                                <tr>
                                    <td>{{ $recommendation['Recipe_Name'] }}</td> <!-- Nama resep -->
                                    <td class="text-center">{{ $recommendation['Cuisine_Type'] }}</td> <!-- Jenis masakan -->
                                    <td class="text-center">{{ $recommendation['Difficulty_Level'] }}</td> <!-- Tingkat kesulitan -->
                                    <td>{{ $recommendation['Ingredients_List'] }}</td> <!-- Daftar bahan -->
                                    <td class="cooking-step">{{ $recommendation['Cooking_Step'] }}</td>
                                    <td class="text-center">{{ $recommendation['similarity'] }}%</td> <!-- Tingkat kesamaan -->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4"> <!-- Paginasi -->
                    <nav aria-label="Table Pagination">
                        <ul class="pagination" id="pagination"> <!-- Elemen pagination -->
                            <!-- Pagination will be dynamically added by JavaScript -->
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>
</div>
    <a href="{{ url()->previous() }}" class="btn btn-back d-block mx-auto">Back</a> <!-- Tombol kembali -->

@endsection

<style>
    .text-gradient {
        background: -webkit-linear-gradient(135deg, #ff758c, #ff9eb3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .table-bordered th,
    .table-bordered td {
        vertical-align: middle;
    }
    th {
        cursor: pointer;
    }
    th:hover {
        background-color: #f1f1f1;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rowsPerPage = 10; // Jumlah baris per halaman
        const table = document.getElementById('recommendation-table'); // Referensi tabel
        const tableBody = document.getElementById('table-body'); // Referensi isi tabel
        const pagination = document.getElementById('pagination'); // Referensi elemen paginasi
  
        let currentPage = 1; // Halaman saat ini

        // Fungsi untuk memperbarui tampilan tabel berdasarkan halaman
        function updateTable() {
            const rows = Array.from(tableBody.getElementsByTagName('tr')); // Semua baris tabel
            const totalRows = rows.length; // Total jumlah baris
            const totalPages = Math.ceil(totalRows / rowsPerPage); // Total halaman

            // Menyembunyikan semua baris, hanya menampilkan yang sesuai halaman
            rows.forEach((row, index) => {
                row.style.display = (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) ? '' : 'none';
            });

            // Memperbarui paginasi
            pagination.innerHTML = ''; // Mengosongkan elemen paginasi
            for (let i = 1; i <= totalPages; i++) {
                const pageItem = document.createElement('li'); // Membuat elemen item halaman
                pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`; // Menambahkan kelas aktif untuk halaman saat ini
                pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`; // Tautan ke halaman
                pageItem.addEventListener('click', function (e) { // Event klik untuk item halaman
                    e.preventDefault();
                    currentPage = i; // Mengatur halaman saat ini
                    updateTable(); // Memperbarui tabel
                });
                pagination.appendChild(pageItem); // Menambahkan item ke elemen paginasi
            }
        }

        // Fungsi untuk mengurutkan tabel berdasarkan kolom
        function sortTable(columnIndex) {
            const rows = Array.from(tableBody.getElementsByTagName('tr')); // Semua baris tabel
            const sortedRows = rows.sort((a, b) => {
                const aText = a.getElementsByTagName('td')[columnIndex].innerText.trim().toLowerCase(); // Nilai kolom A
                const bText = b.getElementsByTagName('td')[columnIndex].innerText.trim().toLowerCase(); // Nilai kolom B

                if (columnIndex === 4) { // Jika kolom Similarity (kolom 4), urutkan angka
                    return parseFloat(bText) - parseFloat(aText); // Urutkan secara numerik
                }

                return aText.localeCompare(bText); // Urutkan secara alfabetis
            });

            tableBody.innerHTML = ''; // Mengosongkan tabel
            sortedRows.forEach(row => tableBody.appendChild(row)); // Menambahkan baris yang diurutkan
            updateTable(); // Perbarui tampilan tabel
        }

        

        updateTable(); // Inisialisasi tabel saat halaman dimuat
    });
</script>
