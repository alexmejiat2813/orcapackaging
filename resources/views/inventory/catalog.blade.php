@extends('layouts.app')

@section('title', 'Product Catalog')

@section('content')
<div class="pagetitle">
    <h1>Product Catalog</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('inventory.types') }}">Types</a></li>
            <li class="breadcrumb-item active">Catalog</li>
        </ol>
    </nav>
</div>

{{-- Filters --}}
<div class="card shadow-sm mb-4">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('inventory.catalog') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label form-label-sm mb-1">Search</label>
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Product # or description...">
            </div>
            <div class="col-md-3">
                <label class="form-label form-label-sm mb-1">Type</label>
                <select name="type" class="form-select form-select-sm">
                    <option value="">All types</option>
                    @foreach($types as $type)
                        <option value="{{ $type->ProductType_ID }}" @selected(request('type') == $type->ProductType_ID)>
                            {{ $type->ProductType_Description }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label form-label-sm mb-1">Status</label>
                <select name="active" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="1" @selected(request('active') === '1')>Active</option>
                    <option value="0" @selected(request('active') === '0')>Inactive</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-search"></i> Search
                </button>
                <a href="{{ route('inventory.catalog') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>
</div>

{{-- Results --}}
<div class="card shadow-sm">
    <div class="card-header">
        <span class="fw-bold">{{ $products->total() }} product(s)</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product #</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th class="text-center">Active</th>
                        <th class="text-end">Price</th>
                        <th>Location</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="fw-bold">{{ $product->PrNumber }}</td>
                        <td>{{ $product->PrDescription1 }}</td>
                        <td class="text-muted small">{{ $product->ProductType_ID }}</td>
                        <td class="text-center">
                            @if($product->PrActif)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            {{ $product->Price_1 ? '$'.number_format($product->Price_1, 2) : '-' }}
                        </td>
                        <td class="text-muted small">{{ $product->PrLocation ?? '-' }}</td>
                        <td>
                            <a href="{{ route('products.show', $product->Product_ID) }}" class="btn btn-sm btn-outline-primary py-0">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No products found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($products->hasPages())
    <div class="card-footer d-flex justify-content-end">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
