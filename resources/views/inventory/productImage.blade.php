@extends('layouts.app') {{-- remplace par ton layout si besoin --}}

@section('content')
<div class="container mt-5">

    <h2 class="mb-4">Téléverser une image</h2>

    <form id="uploadForm" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="image" class="form-label">Choisir une image :</label>
            <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
        </div>

        <div id="container-clients" class="mb-4">
            <label for="clients" class="form-label">Choix du client</label>
            <select name="clients" id="clients" class="form-select" style="width: 300px;" required>
                <option value="Prospect">Prospect ou Inconnu</option>
                @foreach($clients as $client)
                    <option value="{{ $client->Customer_No }}">
                        {{ $client->Customer_Name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="container-products" class="mb-4">
            <label for="products" class="form-label">Choix du produit</label>
            <select name="products" id="products" class="form-select" style="width: 300px;" required>
                @foreach($products as $product)
                    <option value="{{ $product->PrNumber }}">
                        {{ $product->PrNumber }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Bouton de soumission --}}
        <button type="submit" class="btn btn-primary">Soumettre l’image</button>
    </form>

    <div id="resultMessage" class="mt-3"></div>

</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#clients').select2({
                placeholder: "Tape pour chercher..."
            });
        });

        $(document).ready(function () {
            $('#products').select2({
                placeholder: "Tape pour chercher..."
            });
        });
        
        document.getElementById('uploadForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const image = document.getElementById('image').files[0];
        const client = document.getElementById('clients').value;
        const product = document.getElementById('products').value;
        const result = document.getElementById('resultMessage');

        if (!image || !client) {
            result.innerHTML = `<div class="alert alert-warning">Veuillez choisir une image et une catégorie.</div>`;
            return;
        }

        const formData = new FormData();
        formData.append('image', image);
        formData.append('client', client);
        formData.append('product', product);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            const response = await fetch("{{ route('productImage.upload') }}", {
                method: "POST",
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                result.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
            } else {
                result.innerHTML = `<div class="alert alert-danger">${data.message || 'Erreur lors du téléversement.'}</div>`;
            }

        } catch (error) {
            console.error(error);
            result.innerHTML = `<div class="alert alert-danger">Erreur réseau ou serveur.</div>`;
        }
    });
    </script>
@endpush
