@extends('layout.app')

@section('content')
<div class="header">
    <h1>Welcome to Your Dashboard</h1>
    <p>Your personalized food recommendations at your fingertips.</p>
</div>

<div class="container">
    <div class="row g-4 mt-5 justify-content-center">
        <!-- Card 1: Recommendation by Ingredients -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 h-100 interactive-card">
                <div class="card-body text-center">
                    <h5 class="card-title  #ff9eb3 fw-bold">Recommendation by Ingredients</h5>
                    <p class="card-text">Browse through a curated list of ingredients available in our database.</p>
                    <a href="{{ route('recommendations-form-ingredients') }}" class="btn btn-primary">Explore</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection