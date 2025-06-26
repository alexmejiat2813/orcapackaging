@extends('layouts.app')

@section('title', 'Orca Packaging')

@section('content')
    <div class="pagetitle">
        <h1>Product Types</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Types</li>
            </ol>
        </nav>
    </div>

<div class="product-type-dashboard ">
    <h2 class="title">Sélectionnez un type de produit</h2>
    <div class="grid container-center">
        @foreach($types as $type)
            <div class="tile" onclick="window.location.href='{{ route('products.byType', $type->ProductType_ID) }}'">
                <div class="tile-name">{{ $type->ProductType_Description }}</div>
                <div class="tile-code">{{ $type->ProductType_Code }}</div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('styles')
<style>

.product-type-dashboard {
    font-family: 'Segoe UI', sans-serif;
    padding: 2rem;
    /*background-color: #f4f4f4;*/
    text-align: center;
}

.title {
    margin-bottom: 2rem;
    font-size: 1.8rem;
    color: #004d40;
}

.grid {
    display: grid;
    justify-content: center;    /* Centra horizontalmente */
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.2rem;
    max-width: 1000px;
    margin: 0 auto;
}

.tile {
    background-color: #00695c;
    color: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}

.tile:hover {
    transform: scale(1.05);
    background-color: #004d40;
}

.tile-name {
    font-weight: bold;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.tile-code {
    font-size: 0.9rem;
    color: #c8e6c9;
}
</style>
@endpush
