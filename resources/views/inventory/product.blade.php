@extends('layouts.app')

@section('title', $productType->ProductType_Description ?? 'Products')

@section('content')
<div class="pagetitle">
    <h1>{{ $productType->ProductType_Description ?? 'Products' }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('inventory.types') }}">Types</a></li>
            <li class="breadcrumb-item active">{{ $productType->ProductType_Description ?? '' }}</li>
        </ol>
    </nav>
</div>

<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span class="fw-bold">{{ $products->count() }} product(s)</span>
        <a href="{{ route('inventory.catalog') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-search"></i> Full Catalog
        </a>
    </div>
    <div class="card-body p-0">
        <div class="p-3 border-bottom">
            <input type="text" id="tableSearch" class="form-control form-control-sm" placeholder="Search by number or description...">
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="productTable">
                <thead class="table-light">
                    <tr>
                        <th>Product #</th>
                        <th>Description</th>
                        <th>Description 2</th>
                        <th class="text-center">Active</th>
                        <th class="text-center">Stock</th>
                        <th class="text-end">Min Qty</th>
                        <th class="text-end">Price</th>
                        <th>Location</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr class="product-row">
                        <td class="fw-bold">{{ $product->PrNumber }}</td>
                        <td>{{ $product->PrDescription1 }}</td>
                        <td class="text-muted small">{{ $product->PrDescription2 }}</td>
                        <td class="text-center">
                            @if($product->PrActif)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($product->PrStock)
                                <i class="bi bi-check-circle text-success"></i>
                            @else
                                <i class="bi bi-dash text-muted"></i>
                            @endif
                        </td>
                        <td class="text-end">{{ $product->PrMinQty ?? '-' }}</td>
                        <td class="text-end">
                            @if($product->Price_1)
                                ${{ number_format($product->Price_1, 4) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-muted small">{{ $product->PrLocation ?? '-' }}</td>
                        <td>
                            <a href="{{ route('products.show', $product->Product_ID) }}" class="btn btn-xs btn-outline-primary btn-sm py-0">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('tableSearch').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#productTable .product-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endpush
