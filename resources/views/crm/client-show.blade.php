@extends('layouts.app')

@section('title', $customer->Customer_Name)

@section('content')
<div class="container-fluid py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">{{ $customer->Customer_Name }}</h2>
            <small class="text-muted"># {{ $customer->Customer_No }}</small>
        </div>
        <a href="/crm/clients" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour aux clients
        </a>
    </div>

    <div class="row mb-4 g-3">

        {{-- Coordonnées --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Coordonnées</div>
                <div class="card-body small">
                    @if($customer->CuAddress)
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Adresse</div>
                        <div class="col-7">{{ $customer->CuAddress }}</div>
                    </div>
                    @endif
                    @if($customer->CuAddress2)
                    <div class="row mb-1">
                        <div class="col-5 text-muted"></div>
                        <div class="col-7">{{ $customer->CuAddress2 }}</div>
                    </div>
                    @endif
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Ville</div>
                        <div class="col-7">{{ $customer->CuCity ?? '-' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Province</div>
                        <div class="col-7">{{ $customer->CuProvince ?? '-' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Code postal</div>
                        <div class="col-7">{{ $customer->CuPostalCode ?? '-' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Pays</div>
                        <div class="col-7">{{ $customer->CuISOCountryCode ?? '-' }}</div>
                    </div>
                    <hr class="my-2">
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Téléphone</div>
                        <div class="col-7">{{ $customer->CuPhoneNumber1 ?? '-' }}</div>
                    </div>
                    @if($customer->CuPhoneNumber2)
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Téléphone 2</div>
                        <div class="col-7">{{ $customer->CuPhoneNumber2 }}</div>
                    </div>
                    @endif
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Courriel</div>
                        <div class="col-7">
                            @if($customer->CuEMail)
                                <a href="mailto:{{ $customer->CuEMail }}">{{ $customer->CuEMail }}</a>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    @if($customer->CuWebAddress)
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Site web</div>
                        <div class="col-7">
                            <a href="{{ $customer->CuWebAddress }}" target="_blank" rel="noopener">{{ $customer->CuWebAddress }}</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Statut & Représentant --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Statut</div>
                <div class="card-body small">
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Actif</div>
                        <div class="col-7">
                            @if($customer->Cst_Active)
                                <span class="badge bg-success">Actif</span>
                            @else
                                <span class="badge bg-secondary">Inactif</span>
                            @endif
                        </div>
                    </div>
                    @if($customer->CuProspect)
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Prospect</div>
                        <div class="col-7"><span class="badge bg-info">Oui</span></div>
                    </div>
                    @endif
                    @if($customer->Customer_Block_Credit)
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Crédit bloqué</div>
                        <div class="col-7"><span class="badge bg-danger">Oui</span></div>
                    </div>
                    @endif
                    @if($customer->Customer_Stop_Transactions)
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Transactions bloquées</div>
                        <div class="col-7"><span class="badge bg-danger">Oui</span></div>
                    </div>
                    @endif
                    <hr class="my-2">
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Représentant</div>
                        <div class="col-7">{{ $customer->Rep_Name ?? '-' }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-5 text-muted">Client depuis</div>
                        <div class="col-7">{{ $customer->CuOpeningDate?->format('Y-m-d') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Achats --}}
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Historique achats</div>
                <div class="card-body small">
                    <div class="row mb-1">
                        <div class="col-6 text-muted">Total achats</div>
                        <div class="col-6 fw-bold">${{ number_format($customer->CuTotalPurchases ?? 0, 2) }}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-6 text-muted">Dernier achat</div>
                        <div class="col-6">{{ $customer->CuLastPurchasesDate?->format('Y-m-d') ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notes --}}
    @if($customer->CuComment)
    <div class="alert alert-light border mb-4">
        <strong>Note:</strong> {{ $customer->CuComment }}
    </div>
    @endif

    {{-- Commandes récentes --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Commandes récentes ({{ $orders->count() }})</div>
        @if($orders->isEmpty())
            <div class="card-body text-muted">Aucune commande trouvée.</div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Facture</th>
                        <th>Statut</th>
                        <th>Date requise</th>
                        <th class="text-center">Complété</th>
                        <th class="text-center">Annulé</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->Commande_Id }}</td>
                        <td>{{ $order->InInvoiceNumber ?? '-' }}</td>
                        <td>{{ $order->Production_Status_Description ?? '-' }}</td>
                        <td>{{ $order->Date_Demander ? \Carbon\Carbon::parse($order->Date_Demander)->format('Y-m-d') : '-' }}</td>
                        <td class="text-center">
                            @if($order->Complet)
                                <span class="badge bg-success">Oui</span>
                            @else
                                <span class="badge bg-secondary">Non</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($order->Cancel)
                                <span class="badge bg-danger">Oui</span>
                            @else
                                <span class="badge bg-secondary">Non</span>
                            @endif
                        </td>
                        <td>
                            <a href="/production/orders/{{ $order->Commande_Id }}" class="btn btn-outline-primary btn-sm py-0 px-1">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
