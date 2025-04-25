<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">
  <div class="d-flex align-items-center justify-content-between">
    <!-- App Logo and Title -->
    <a href="/" class="logo d-flex align-items-center">
      <span class="d-none d-lg-block">@yield('title', 'Name App')</span>
    </a>
    <!-- Sidebar toggle button -->
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div>

  @auth
  <div class="d-flex align-items-center ms-auto dropdown pe-4">
    <a class="nav-link dropdown-toggle text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->Users_Name }}
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
      <li><a class="dropdown-item" href="#"><i class="bi bi-gear"></i> Settings</a></li>
      <li><hr class="dropdown-divider"></li>
      <li>
        <form method="POST" action="{{ url('logout') }}">
          @csrf
          <button type="submit" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
      </li>
    </ul>
  </div>
  @endauth
</header>
<!-- End Header -->

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <!-- Full ERP + CRM Menu Blade Template -->
  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Home -->
    <li class="nav-item">
      <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ url('/') }}">
        <i class="bi bi-house"></i><span>Home</span>
      </a>
    </li>

    <!-- Login -->
    @guest
    <li class="nav-item">
      <a class="nav-link {{ Request::is('login') ? 'active' : '' }}" href="{{ url('/login') }}">
        <i class="bi bi-box-arrow-in-right"></i><span>Login</span>
      </a>
    </li>
    @endguest

    <!-- Dashboard -->
    @if(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif')
    <li class="nav-item">
      <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
        <i class="bi bi-speedometer2"></i><span>Dashboard</span>
      </a>
    </li>
    @endif

    <!-- SALES -->
    @if(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif')
    <li class="nav-item">
      <a class="nav-link {{ Request::is('sales/*') ? '' : 'collapsed' }}" data-bs-target="#sales-menu" data-bs-toggle="collapse" href="#">
        <i class="bi bi-cart-check"></i><span>Sales</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="sales-menu" class="nav-content collapse {{ Request::is('sales/*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
        <li><a href="{{ url('/sales/estimates') }}" class="{{ Request::is('sales/estimates') ? 'active' : '' }}"><i class="bi bi-circle"></i>Estimates</a></li>
        <li><a href="{{ url('/sales/quotations') }}" class="{{ Request::is('sales/quotations') ? 'active' : '' }}"><i class="bi bi-circle"></i>Quotations</a></li>
        <li><a href="{{ url('/sales/orders') }}" class="{{ Request::is('sales/orders') ? 'active' : '' }}"><i class="bi bi-circle"></i>Sales Orders</a></li>
        <li><a href="{{ url('/sales/invoices') }}" class="{{ Request::is('sales/invoices') ? 'active' : '' }}"><i class="bi bi-circle"></i>Invoices</a></li>
        <li><a href="{{ url('/sales/returns') }}" class="{{ Request::is('sales/returns') ? 'active' : '' }}"><i class="bi bi-circle"></i>Returns</a></li>
        <li><a href="{{ url('/sales/contracts') }}" class="{{ Request::is('sales/contracts') ? 'active' : '' }}"><i class="bi bi-circle"></i>Contracts</a></li>
        <li><a href="{{ url('/sales/goals') }}" class="{{ Request::is('sales/goals') ? 'active' : '' }}"><i class="bi bi-circle"></i>Sales Goals</a></li>
        <li><a href="{{ url('/sales/reports') }}" class="{{ Request::is('sales/reports') ? 'active' : '' }}"><i class="bi bi-circle"></i>Reports</a></li>
      </ul>
    </li>
    @endif

    <!-- PURCHASING -->
    <li class="nav-item">
      <a class="nav-link {{ Request::is('purchasing/*') ? '' : 'collapsed' }}" data-bs-target="#purchasing-menu" data-bs-toggle="collapse" href="#">
        <i class="bi bi-box-seam"></i><span>Purchasing</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="purchasing-menu" class="nav-content collapse {{ Request::is('purchasing/*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
        <li><a href="{{ url('/purchasing/requests') }}" class="{{ Request::is('purchasing/requests') ? 'active' : '' }}"><i class="bi bi-circle"></i>Requests</a></li>
        @if(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif')
        <li><a href="{{ url('/purchasing/orders') }}" class="{{ Request::is('purchasing/orders') ? 'active' : '' }}"><i class="bi bi-circle"></i>Purchase Orders</a></li>
        <li><a href="{{ url('/purchasing/rfqs') }}" class="{{ Request::is('purchasing/rfqs') ? 'active' : '' }}"><i class="bi bi-circle"></i>RFQs</a></li>
        <li><a href="{{ url('/purchasing/agreements') }}" class="{{ Request::is('purchasing/agreements') ? 'active' : '' }}"><i class="bi bi-circle"></i>Agreements</a></li>
        <li><a href="{{ url('/purchasing/pricelists') }}" class="{{ Request::is('purchasing/pricelists') ? 'active' : '' }}"><i class="bi bi-circle"></i>Price Lists</a></li>
        <li><a href="{{ url('/purchasing/suppliers') }}" class="{{ Request::is('purchasing/suppliers') ? 'active' : '' }}"><i class="bi bi-circle"></i>Suppliers</a></li>
        <li><a href="{{ url('/purchasing/rating') }}" class="{{ Request::is('purchasing/rating') ? 'active' : '' }}"><i class="bi bi-circle"></i>Rating</a></li>
        @endif
      </ul>
    </li>

    <!-- PRODUCTION -->
    @if(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif')
      <li class="nav-item">
        <a class="nav-link {{ Request::is('production/*') ? '' : 'collapsed' }}" data-bs-target="#production-menu" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gear-wide-connected"></i><span>Production</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="production-menu" class="nav-content collapse {{ Request::is('production/*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">

          <li>
            <a href="{{ url('/production/orders') }}" class="{{ Request::is('production/orders') ? 'active' : '' }}">
              <i class="bi bi-circle"></i>Production Jobs
            </a>
          </li>
          <li>
            <a href="{{ url('/production/bom') }}" class="{{ Request::is('production/bom') ? 'active' : '' }}">
              <i class="bi bi-circle"></i>BOM
            </a>
          </li>

          <li>
            <a href="{{ url('/production/planning') }}" class="{{ Request::is('production/planning') ? 'active' : '' }}">
              <i class="bi bi-circle"></i>Planning
            </a>
          </li>

          <li>
            <a class="{{ Request::is('production/workorders*') ? '' : 'collapsed' }}"
               data-bs-target="#workorders-menu" data-bs-toggle="collapse" href="#">
              <i class="bi bi-circle"></i><span>Work Orders</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="workorders-menu"
                class="nav-content collapse {{ Request::is('production/workorders*') ? 'show' : '' }}"
                data-bs-parent="#production-menu">

              <li>
                <a href="{{ url('/production/workorders') }}"
                   class="{{ Request::is('production/workorders') ? 'active' : '' }}">
                  <i class="bi bi-circle"></i>All Orders
                </a>
              </li>
              <li>
                <a href="{{ url('/production/workorders/uteco') }}"
                   class="{{ Request::is('production/workorders/uteco') ? 'active' : '' }}">
                  <i class="bi bi-circle"></i>Uteco
                </a>
              </li>
              <li>
                <a href="{{ url('/production/workorders/machine/conversion') }}"
                   class="{{ Request::is('production/workorders/machine/conversion') ? 'active' : '' }}">
                  <i class="bi bi-circle"></i>Conversion
                </a>
              </li>
              <li>
                <a href="{{ url('/production/workorders/machine/slitter') }}"
                   class="{{ Request::is('production/workorders/machine/slitter') ? 'active' : '' }}">
                  <i class="bi bi-circle"></i>Slitter
                </a>
              </li>
              <li>
                <a href="{{ url('/production/workorders/machine/silkscreen') }}"
                   class="{{ Request::is('production/workorders/machine/silkscreen') ? 'active' : '' }}">
                  <i class="bi bi-circle"></i>Silkscreen
                </a>
              </li>
              <li>
                <a href="{{ url('/production/workorders/machine/siat') }}"
                   class="{{ Request::is('production/workorders/machine/siat') ? 'active' : '' }}">
                  <i class="bi bi-circle"></i>Siat (Tapes)
                </a>
              </li>
            </ul>
          </li>

          
          <li>
            <a href="{{ url('/production/downtime') }}" class="{{ Request::is('production/downtime') ? 'active' : '' }}">
              <i class="bi bi-circle"></i>Machine Downtime
            </a>
          </li>
          <li>
            <a href="{{ url('/production/efficiency') }}" class="{{ Request::is('production/efficiency') ? 'active' : '' }}">
              <i class="bi bi-circle"></i>Efficiency
            </a>
          </li>
        </ul>
      </li>
    @endif


    <!-- INVENTORY -->
    @if(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif')
    <li class="nav-item">
      <a class="nav-link {{ Request::is('inventory/*') ? '' : 'collapsed' }}" data-bs-target="#inventory-menu" data-bs-toggle="collapse" href="#">
        <i class="bi bi-boxes"></i><span>Inventory</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="inventory-menu" class="nav-content collapse {{ Request::is('inventory/*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
        <li><a href="{{ url('/inventory/items') }}" class="{{ Request::is('inventory/items') ? 'active' : '' }}"><i class="bi bi-circle"></i>Items</a></li>
        <li><a href="{{ url('/inventory/movements') }}" class="{{ Request::is('inventory/movements') ? 'active' : '' }}"><i class="bi bi-circle"></i>Movements</a></li>
        <li><a href="{{ url('/inventory/warehouses') }}" class="{{ Request::is('inventory/warehouses') ? 'active' : '' }}"><i class="bi bi-circle"></i>Warehouses</a></li>
        <li><a href="{{ url('/inventory/barcodes') }}" class="{{ Request::is('inventory/barcodes') ? 'active' : '' }}"><i class="bi bi-circle"></i>Barcodes</a></li>
        <li><a href="{{ url('/inventory/lots') }}" class="{{ Request::is('inventory/lots') ? 'active' : '' }}"><i class="bi bi-circle"></i>Lots</a></li>
        <li><a href="{{ url('/inventory/alerts') }}" class="{{ Request::is('inventory/alerts') ? 'active' : '' }}"><i class="bi bi-circle"></i>Alerts</a></li>
      </ul>
    </li>
    @endif

    <!-- CRM -->
    @if(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif')
    <li class="nav-item">
      <a class="nav-link {{ Request::is('crm/*') ? '' : 'collapsed' }}" data-bs-target="#crm-menu" data-bs-toggle="collapse" href="#">
        <i class="bi bi-people"></i><span>CRM</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="crm-menu" class="nav-content collapse {{ Request::is('crm/*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
        <li><a href="{{ url('/crm/clients') }}" class="{{ Request::is('crm/clients') ? 'active' : '' }}"><i class="bi bi-circle"></i>Clients</a></li>
        <li><a href="{{ url('/crm/contacts') }}" class="{{ Request::is('crm/contacts') ? 'active' : '' }}"><i class="bi bi-circle"></i>Contacts</a></li>
        <li><a href="{{ url('/crm/activities') }}" class="{{ Request::is('crm/activities') ? 'active' : '' }}"><i class="bi bi-circle"></i>Activities</a></li>
        <li><a href="{{ url('/crm/opportunities') }}" class="{{ Request::is('crm/opportunities') ? 'active' : '' }}"><i class="bi bi-circle"></i>Opportunities</a></li>
        <li><a href="{{ url('/crm/leads') }}" class="{{ Request::is('crm/leads') ? 'active' : '' }}"><i class="bi bi-circle"></i>Leads</a></li>
        <li><a href="{{ url('/crm/campaigns') }}" class="{{ Request::is('crm/campaigns') ? 'active' : '' }}"><i class="bi bi-circle"></i>Campaigns</a></li>
        <li><a href="{{ url('/crm/segments') }}" class="{{ Request::is('crm/segments') ? 'active' : '' }}"><i class="bi bi-circle"></i>Segmentation</a></li>
      </ul>
    </li>
    @endif

    <!-- ACCOUNTING -->
    @if(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif')
    <li class="nav-item">
      <a class="nav-link {{ Request::is('accounting/*') ? '' : 'collapsed' }}" data-bs-target="#accounting-menu" data-bs-toggle="collapse" href="#">
        <i class="bi bi-cash-coin"></i><span>Accounting</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="accounting-menu" class="nav-content collapse {{ Request::is('accounting/*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
        <li><a href="{{ url('/accounting/invoices') }}" class="{{ Request::is('accounting/invoices') ? 'active' : '' }}"><i class="bi bi-circle"></i>Invoices</a></li>
        <li><a href="{{ url('/accounting/payments') }}" class="{{ Request::is('accounting/payments') ? 'active' : '' }}"><i class="bi bi-circle"></i>Payments</a></li>
        <li><a href="{{ url('/accounting/expenses') }}" class="{{ Request::is('accounting/expenses') ? 'active' : '' }}"><i class="bi bi-circle"></i>Expenses</a></li>
        <li><a href="{{ url('/accounting/journals') }}" class="{{ Request::is('accounting/journals') ? 'active' : '' }}"><i class="bi bi-circle"></i>Journals</a></li>
        <li><a href="{{ url('/accounting/taxes') }}" class="{{ Request::is('accounting/taxes') ? 'active' : '' }}"><i class="bi bi-circle"></i>Taxes</a></li>
        <li><a href="{{ url('/accounting/reconciliation') }}" class="{{ Request::is('accounting/reconciliation') ? 'active' : '' }}"><i class="bi bi-circle"></i>Reconciliation</a></li>
        <li><a href="{{ url('/accounting/balance') }}" class="{{ Request::is('accounting/balance') ? 'active' : '' }}"><i class="bi bi-circle"></i>Balance Sheet</a></li>
      </ul>
    </li>
    @endif

    <!-- HR -->
    <!-- HR MODULE -->
    @if(Auth::user()?->fonction?->Fonction_Desc === 'Adjoin administratif')
    <li class="nav-item">
      <a class="nav-link {{ Request::is('hr/*') ? '' : 'collapsed' }}" data-bs-target="#hr-menu" data-bs-toggle="collapse" href="#">
        <i class="bi bi-person-badge"></i><span>HR</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="hr-menu" class="nav-content collapse {{ Request::is('hr/*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ url('/hr/clock') }}" class="{{ Request::is('hr/clock') ? 'active' : '' }}">
            <i class="bi bi-upc-scan"></i><span>Clock In/Out</span>
          </a>
        </li>
        <li>
          <a href="{{ url('/hr/attendance') }}" class="{{ Request::is('hr/attendance') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i><span>Attendance</span>
          </a>
        </li>
        <li>
          <a href="{{ url('/hr/employees') }}" class="{{ Request::is('hr/employees') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i><span>Employees</span>
          </a>
        </li>
        <li>
          <a href="{{ url('/hr/leaves') }}" class="{{ Request::is('hr/leaves') ? 'active' : '' }}">
            <i class="bi bi-calendar-minus"></i><span>Leaves</span>
          </a>
        </li>
        <li>
          <a href="{{ url('/hr/payroll') }}" class="{{ Request::is('hr/payroll') ? 'active' : '' }}">
            <i class="bi bi-currency-dollar"></i><span>Payroll</span>
          </a>
        </li>
        <li>
          <a href="{{ url('/hr/evaluations') }}" class="{{ Request::is('hr/evaluations') ? 'active' : '' }}">
            <i class="bi bi-clipboard-check"></i><span>Evaluations</span>
          </a>
        </li>
        <li>
          <a href="{{ url('/hr/training') }}" class="{{ Request::is('hr/training') ? 'active' : '' }}">
            <i class="bi bi-journal-code"></i><span>Training</span>
          </a>
        </li>
        <li>
          <a href="{{ url('/hr/recruitment') }}" class="{{ Request::is('hr/recruitment') ? 'active' : '' }}">
            <i class="bi bi-person-plus"></i><span>Recruitment</span>
          </a>
        </li>
      </ul>
    </li>
    @endif

    <!-- Tools -->
    <li class="nav-item">
      <a class="nav-link {{ Request::is('tools') ? 'active' : '' }}" href="{{ url('/tools') }}">
        <i class="bi bi-tools"></i><span>Tools</span>
      </a>
    </li>

  </ul>

</aside>
<!-- End Sidebar -->
