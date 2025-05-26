@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Configuration des Requis</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </nav>
    </div>

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="settingsTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab">
                <i class="fas fa-user"></i> Profile
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="company-tab" data-bs-toggle="tab" data-bs-target="#company-tab-pane" type="button" role="tab">
                <i class="fas fa-building"></i> Company
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-tab-pane" type="button" role="tab">
                <i class="fas fa-users-cog"></i> Users & Roles
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="modules-tab" data-bs-toggle="tab" data-bs-target="#modules-tab-pane" type="button" role="tab">
                <i class="fas fa-th-large"></i> Modules
            </button>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content p-4 border border-top-0 bg-white rounded-bottom" id="settingsTabContent">
        <!-- Profile Tab -->
        <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel">
            <h4 class="mb-3">Edit Profile</h4>
            <p>Manage your personal information and change your password.</p>
            <a href="{{ route('settings.profile') }}" class="btn btn-primary">Edit Profile</a>
            <a href="{{ route('settings.password') }}" class="btn btn-outline-secondary">Change Password</a>
        </div>

        <!-- Company Tab -->
        <div class="tab-pane fade" id="company-tab-pane" role="tabpanel">
            <h4 class="mb-3">Company Info</h4>
            <p>Update company name, address, logo, and branding preferences.</p>
            <a href="#" class="btn btn-primary">Edit Company Info</a>
        </div>

        <!-- Users & Roles Tab -->
        <div class="tab-pane fade" id="users-tab-pane" role="tabpanel">
            <h4 class="mb-3">Users & Roles</h4>
            <p>Manage user accounts, assign roles and control access permissions.</p>
            <a href="#" class="btn btn-primary me-2">Manage Users</a>
            <a href="#" class="btn btn-secondary">Manage Roles</a>
        </div>

        <!-- Modules Tab -->
        <div class="tab-pane fade" id="modules-tab-pane" role="tabpanel">
            <h4 class="mb-3">Module Settings</h4>
            <p>Select a module to configure its related parameters.</p>

            <div class="row g-3">
                @php
                    $modules = [
                        ['name' => 'General', 'icon' => 'shopping-cart', 'slug' => 'settings.modules.general', 'items' => [
                            ['name' => 'Department', 'slug' => 'department'],
                            ['name' => 'Equipment', 'slug' => 'equipment']
                        ]],
                        ['name' => 'Sales', 'icon' => 'shopping-cart', 'slug' => 'sales', 'items' => [
                            ['name' => 'Estimates', 'slug' => 'estimates'],
                            ['name' => 'Quotations', 'slug' => 'quotations'],
                            ['name' => 'Sales Orders', 'slug' => 'sales-orders'],
                            ['name' => 'Invoices', 'slug' => 'invoices'],
                            ['name' => 'Returns', 'slug' => 'returns'],
                            ['name' => 'Contracts', 'slug' => 'contracts'],
                            ['name' => 'Sales Goals', 'slug' => 'sales-goals'],
                            ['name' => 'Reports', 'slug' => 'reports'],
                        ]],
                        ['name' => 'Purchasing', 'icon' => 'box', 'slug' => 'purchasing', 'items' => [
                            ['name' => 'Requests', 'slug' => 'requests'],
                            ['name' => 'Purchase Orders', 'slug' => 'purchase-orders'],
                            ['name' => 'RFQs', 'slug' => 'rfqs'],
                            ['name' => 'Agreements', 'slug' => 'agreements'],
                            ['name' => 'Price Lists', 'slug' => 'price-lists'],
                            ['name' => 'Suppliers', 'slug' => 'suppliers'],
                            ['name' => 'Rating', 'slug' => 'rating'],
                        ]],
                        ['name' => 'Production', 'icon' => 'cogs', 'slug' => 'settings.modules.production.requis', 'items' => [
                            ['name' => 'Requis', 'slug' => 'requis'],
                            ['name' => 'Order Tracking', 'slug' => 'order-tracking'],
                            ['name' => 'Production Jobs', 'slug' => 'production-jobs'],
                            ['name' => 'BOM', 'slug' => 'bom'],
                            ['name' => 'Planning', 'slug' => 'planning'],
                            ['name' => 'Work Orders', 'slug' => 'work-orders'],
                            ['name' => 'Machine Downtime', 'slug' => 'machine-downtime'],
                            ['name' => 'Efficiency', 'slug' => 'efficiency'],
                        ]],
                        ['name' => 'Inventory', 'icon' => 'warehouse', 'slug' => 'inventory', 'items' => [
                            ['name' => 'Items', 'slug' => 'items'],
                            ['name' => 'Movements', 'slug' => 'movements'],
                            ['name' => 'Warehouses', 'slug' => 'warehouses'],
                            ['name' => 'Barcodes', 'slug' => 'barcodes'],
                            ['name' => 'Lots', 'slug' => 'lots'],
                            ['name' => 'Alerts', 'slug' => 'alerts'],
                        ]],
                        ['name' => 'CRM', 'icon' => 'address-book', 'slug' => 'crm', 'items' => [
                            ['name' => 'Clients', 'slug' => 'clients'],
                            ['name' => 'Contacts', 'slug' => 'contacts'],
                            ['name' => 'Activities', 'slug' => 'activities'],
                            ['name' => 'Opportunities', 'slug' => 'opportunities'],
                            ['name' => 'Leads', 'slug' => 'leads'],
                            ['name' => 'Campaigns', 'slug' => 'campaigns'],
                            ['name' => 'Segmentation', 'slug' => 'segmentation'],
                        ]],
                        ['name' => 'Accounting', 'icon' => 'calculator', 'slug' => 'accounting', 'items' => [
                            ['name' => 'Invoices', 'slug' => 'invoices'],
                            ['name' => 'Payments', 'slug' => 'payments'],
                            ['name' => 'Expenses', 'slug' => 'expenses'],
                            ['name' => 'Journals', 'slug' => 'journals'],
                            ['name' => 'Taxes', 'slug' => 'taxes'],
                            ['name' => 'Reconciliation', 'slug' => 'reconciliation'],
                            ['name' => 'Balance Sheet', 'slug' => 'balance-sheet'],
                        ]],
                        ['name' => 'HR', 'icon' => 'user-clock', 'slug' => 'hr', 'items' => [
                            ['name' => 'Clock In/Out', 'slug' => 'clock-in-out'],
                            ['name' => 'Attendance', 'slug' => 'attendance'],
                            ['name' => 'Employees', 'slug' => 'employees'],
                            ['name' => 'Leaves', 'slug' => 'leaves'],
                            ['name' => 'Payroll', 'slug' => 'payroll'],
                            ['name' => 'Evaluations', 'slug' => 'evaluations'],
                            ['name' => 'Training', 'slug' => 'training'],
                            ['name' => 'Recruitment', 'slug' => 'recruitment'],
                        ]],
                    ];
                @endphp

                @foreach ($modules as $module)
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5><i class="fas fa-{{ $module['icon'] }} me-2"></i>{{ $module['name'] }}</h5>
                                <ul class="list-unstyled mt-2">
                                    @foreach ($module['items'] as $item)
                                        <li>
                                            @php $route = $module['slug'] . '.' . $item['slug']; @endphp
                                            
                                            @if (Route::has($route))
                                                <a href="{{ route($route) }}" class="text-decoration-none">
                                                    <i class="fas fa-cog fa-sm me-1"></i> {{ $item['name'] }}
                                                </a>
                                            @else
                                                <span class="text-muted"><i class="fas fa-cog fa-sm me-1"></i> {{ $item['name'] }} (coming soon)</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
