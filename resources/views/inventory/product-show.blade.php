@extends('layouts.app')

@section('title', $product->PrNumber)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">{{ $product->PrNumber }}</h2>
            <small class="text-muted">ID: {{ $product->Product_ID }}</small>
        </div>
        <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Product Info</div>
                <div class="card-body small">
                    <table class="table table-borderless table-sm mb-0">
                        <tr><td class="text-muted" style="width:40%">Product #</td><td class="fw-bold">{{ $product->PrNumber }}</td></tr>
                        <tr><td class="text-muted">Description</td><td>{{ $product->PrDescription1 }}</td></tr>
                        @if($product->PrDescription2)
                        <tr><td class="text-muted">Description 2</td><td>{{ $product->PrDescription2 }}</td></tr>
                        @endif
                        @if($product->PrDescription3)
                        <tr><td class="text-muted">Description 3</td><td>{{ $product->PrDescription3 }}</td></tr>
                        @endif
                        <tr><td class="text-muted">Type</td><td>{{ $product->productType->ProductType_Description ?? $product->ProductType_ID }}</td></tr>
                        <tr>
                            <td class="text-muted">Status</td>
                            <td>
                                @if($product->PrActif)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                                @if($product->Product_Lock)
                                    <span class="badge bg-warning text-dark ms-1">Locked</span>
                                @endif
                            </td>
                        </tr>
                        <tr><td class="text-muted">Location</td><td>{{ $product->PrLocation ?? '-' }}</td></tr>
                        <tr><td class="text-muted">UPC</td><td>{{ $product->PrUPC ?? $product->UPCA ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Stock & Quantities</div>
                <div class="card-body small">
                    <table class="table table-borderless table-sm mb-0">
                        <tr><td class="text-muted">Track Stock</td><td>{{ $product->PrStock ? 'Yes' : 'No' }}</td></tr>
                        <tr><td class="text-muted">Min Qty</td><td>{{ $product->PrMinQty ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Max Qty</td><td>{{ $product->PrMaxQty ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Monthly Min</td><td>{{ $product->PrQtyMonthMin ?? '-' }}</td></tr>
                        <tr><td class="text-muted">Monthly Max</td><td>{{ $product->PrQtyMonthMax ?? '-' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Pricing</div>
                <div class="card-body small">
                    <table class="table table-borderless table-sm mb-0">
                        <tr><td class="text-muted">Price 1</td><td>${{ number_format($product->Price_1 ?? 0, 4) }}</td></tr>
                        <tr><td class="text-muted">Price 2</td><td>${{ number_format($product->Price_2 ?? 0, 4) }}</td></tr>
                        <tr><td class="text-muted">Price 3</td><td>${{ number_format($product->Price_3 ?? 0, 4) }}</td></tr>
                        <tr><td class="text-muted">Suggested</td><td>${{ number_format($product->Price_Suggest ?? 0, 4) }}</td></tr>
                        <tr><td class="text-muted">Cost</td><td>${{ number_format($product->Product_Cost ?? 0, 4) }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($product->PrComments)
    <div class="alert alert-light border mb-4">
        <strong>Comments:</strong> {{ $product->PrComments }}
    </div>
    @endif

    @if($product->PrPath)
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Product Image</div>
        <div class="card-body text-center">
            @php
                $imgPath = str_replace('\\\\192.168.0.97\\storage\\app\\public\\', 'storage/', $product->PrPath);
            @endphp
            <img src="/{{ $imgPath }}" alt="{{ $product->PrNumber }}" style="max-height:300px; max-width:100%;"
                 onerror="this.style.display='none'; document.getElementById('noImg').style.display='block'">
            <p id="noImg" class="text-muted" style="display:none">No image available</p>
        </div>
    </div>
    @endif
</div>
@endsection
