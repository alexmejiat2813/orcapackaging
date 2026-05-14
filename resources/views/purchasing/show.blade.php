@extends('layouts.app')

@section('title', 'PO #' . $po->PO_No)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">Purchase Order #{{ $po->PO_No }}</h2>
            <small class="text-muted">ID: {{ $po->PO_ID }}</small>
        </div>
        <a href="/purchasing/orders" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
    </div>

    {{-- Header cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Supplier</div>
                <div class="card-body">
                    <p class="mb-1 fw-bold">{{ $po->supplier->Supplier_Name ?? '-' }}</p>
                    <p class="mb-0 text-muted small">{{ $po->supplier->Supplier_No ?? '' }}</p>
                    @if($po->supplier?->Supplier_City)
                        <p class="mb-0 text-muted small">{{ $po->supplier->Supplier_City }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Status</div>
                <div class="card-body">
                    @if($po->PO_Transmit)
                        <span class="badge bg-primary">Transmitted</span>
                    @endif
                    @if($po->PO_Completed)
                        <span class="badge bg-success">Completed</span>
                    @endif
                    @if($po->PO_Cancel)
                        <span class="badge bg-danger">Cancelled</span>
                    @endif
                    @if($po->PO_Lock)
                        <span class="badge bg-secondary">Locked</span>
                    @endif
                    @if(!$po->PO_Transmit && !$po->PO_Completed && !$po->PO_Cancel)
                        <span class="badge bg-warning text-dark">Draft</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Details</div>
                <div class="card-body small">
                    <div class="row mb-1">
                        <div class="col-6 text-muted">PO Date</div>
                        <div class="col-6">{{ $po->PO_Date ? \Carbon\Carbon::parse($po->PO_Date)->format('Y-m-d') : '-' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-6 text-muted">Required Date</div>
                        <div class="col-6">{{ $po->PO_Date_Reception ? \Carbon\Carbon::parse($po->PO_Date_Reception)->format('Y-m-d') : '-' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-6 text-muted">Total</div>
                        <div class="col-6 fw-bold">${{ number_format($po->PO_Total ?? 0, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($po->PO_Note)
    <div class="alert alert-light border mb-4">
        <strong>Note:</strong> {{ $po->PO_Note }}
    </div>
    @endif

    {{-- Line items --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Line Items ({{ $details->count() }})</div>
        @if($details->isEmpty())
            <div class="card-body text-muted">No line items found.</div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product #</th>
                        <th>Description</th>
                        <th class="text-end">Qty</th>
                        <th>Unit</th>
                        <th class="text-end">Unit Price</th>
                        <th class="text-end">Total</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $line)
                    <tr>
                        <td>{{ $line->PrNumber ?? $line->Product_ID }}</td>
                        <td>{{ $line->PrDescription1 ?? '-' }}</td>
                        <td class="text-end">{{ number_format($line->Quantity, 0) }}</td>
                        <td>{{ $line->Unit_Qty ?? '-' }}</td>
                        <td class="text-end">${{ number_format($line->Unit_Price ?? 0, 4) }}</td>
                        <td class="text-end">${{ number_format(($line->Quantity ?? 0) * ($line->Unit_Price ?? 0), 2) }}</td>
                        <td class="text-muted small">{{ $line->PO_Detail_Note ?? '' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Receivings --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Receivings ({{ $receivings->count() }})</div>
        @if($receivings->isEmpty())
            <div class="card-body text-muted">No receivings recorded.</div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Receiving ID</th>
                        <th>Lines</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($receivings as $recv)
                    <tr>
                        <td>{{ $recv->Receiving_ID }}</td>
                        <td>{{ $recv->details->count() }} line(s)</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    <x-order-notes type="po" :id="$po->PO_ID" :notes="$notes" />

</div>
@endsection
