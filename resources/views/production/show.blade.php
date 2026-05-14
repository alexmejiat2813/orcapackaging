@extends('layouts.app')

@section('title', 'Order #' . $commande->Commande_Id)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">Order #{{ $commande->Commande_Id }}</h2>
            <small class="text-muted">Invoice: {{ $commande->InInvoiceNumber ?? '-' }}</small>
        </div>
        <a href="/production/orders" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
    </div>

    {{-- Header cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Customer</div>
                <div class="card-body">
                    <p class="mb-1 fw-bold">{{ $commande->customer->Customer_Name ?? $commande->Customer_Id }}</p>
                    <p class="mb-0 text-muted small">{{ $commande->customer->Customer_No ?? '' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Status</div>
                <div class="card-body">
                    <span class="badge bg-primary fs-6">
                        {{ $commande->productionStatus->Production_Status_Description ?? 'N/A' }}
                    </span>
                    <div class="mt-2">
                        @if($commande->Complet)
                            <span class="badge bg-success">Completed</span>
                        @endif
                        @if($commande->Cancel)
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                        @if($commande->isReady_Production)
                            <span class="badge bg-info">Ready for Production</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Dates</div>
                <div class="card-body small">
                    <div class="row">
                        <div class="col-5 text-muted">Created</div>
                        <div class="col-7">{{ $commande->created_at?->format('Y-m-d') ?? '-' }}</div>
                    </div>
                    <div class="row">
                        <div class="col-5 text-muted">Due Date</div>
                        <div class="col-7">{{ $commande->Commande_Due_Date?->format('Y-m-d') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Lots --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Lots ({{ $lots->count() }})</div>
        @if($lots->isEmpty())
            <div class="card-body text-muted">No lots found.</div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Lot ID</th>
                        <th>Product #</th>
                        <th>Description</th>
                        <th class="text-end">Qty</th>
                        <th class="text-center">Complete</th>
                        <th class="text-center">Cancelled</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lots as $lot)
                    <tr>
                        <td>{{ $lot->Lot_Id }}</td>
                        <td>{{ $lot->PrNumber ?? '-' }}</td>
                        <td>{{ $lot->PrDescription1 ?? '-' }}</td>
                        <td class="text-end">{{ $lot->Lots_Qty }}</td>
                        <td class="text-center">
                            @if($lot->Lots_Complet)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($lot->Lots_Cancel)
                                <span class="badge bg-danger">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Recipe / Equipment --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Production Recipe - Equipment ({{ $recipe->count() }})</div>
        @if($recipe->isEmpty())
            <div class="card-body text-muted">No recipe lines found.</div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Equipment ID</th>
                        <th>Equipment</th>
                        <th class="text-end">Hours</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recipe as $line)
                    <tr>
                        <td>{{ $line->Equipment_Id }}</td>
                        <td>{{ $line->Equipment_Description ?? '-' }}</td>
                        <td class="text-end">{{ number_format($line->Hours, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
