@extends('backend.layouts.master')

@push('title')
    Dashboard
@endpush

@push('css')
<style>
    body{
        background: #F3F3F3; /* bg-light */
    }

    /* ===== 3D CARD ===== */
    .shortcut-card{
        position: relative;
        border-radius: 18px;
        padding: 22px;
        color: #fff;
        overflow: hidden;
        transform-style: preserve-3d;
        transition: all .35s ease;
        box-shadow:
            0 15px 30px rgba(0,0,0,.35),
            inset 0 0 0 rgba(255,255,255,.2);
    }

    .shortcut-card::before{
        content:'';
        position:absolute;
        inset:0;
        background: linear-gradient(
            120deg,
            rgba(255,255,255,.25),
            transparent 60%
        );
        opacity:0;
        transition:.35s;
    }

    .shortcut-card:hover{
        transform: translateY(-10px) rotateX(8deg);
        box-shadow:
            0 30px 60px rgba(0,0,0,.55),
            inset 0 0 15px rgba(255,255,255,.25);
    }

    .shortcut-card:hover::before{
        opacity:1;
    }

    /* ===== ICON ===== */
    .shortcut-icon{
        font-size: 42px;
        opacity:.95;
        transform: translateZ(40px);
        text-shadow: 0 10px 25px rgba(0,0,0,.4);
    }

    /* ===== CARD TEXT ===== */
    .shortcut-card h5{
        font-weight: 700;
        letter-spacing: .5px;
    }

    .shortcut-card small{
        opacity:.9;
    }

    /* ===== COLOR THEMES ===== */
    .bg-units{ background: linear-gradient(135deg,#667eea,#764ba2); }
    .bg-customers{ background: linear-gradient(135deg,#43cea2,#185a9d); }
    .bg-suppliers{ background: linear-gradient(135deg,#f7971e,#ffd200); }
    .bg-products{ background: linear-gradient(135deg,#00c6ff,#0072ff); }
    .bg-purchase{ background: linear-gradient(135deg,#232526,#414345); }
    .bg-sales{ background: linear-gradient(135deg,#ff416c,#ff4b2b); }
    .bg-stock{ background: linear-gradient(135deg,#485563,#29323c); }
    .bg-employee{ background: linear-gradient(135deg,#11998e,#38ef7d); }
    .bg-salary{ background: linear-gradient(135deg,#56ab2f,#a8e063); }
    .bg-expense{ background: linear-gradient(135deg,#f7971e,#f83600); }
    .bg-report{ background: linear-gradient(135deg,#1d4350,#a43931); }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- TITLE --}}
    <div class="row my-4">
        <div class="col-12">
            <div class="bg-secondary text-white p-4 rounded-4 shadow">
                <h4 class="mb-1">Dashboard</h4>
                <small>
                    Welcome to {{ auth()->user()->company_name ?? '' }} Dashboard
                </small><br>
                <small>
                    software version: 20.05.26
                </small>
            </div>
        </div>
    </div>

    {{-- SHORTCUT GRID --}}
    <div class="row g-4">

        {{-- Units --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('units.index') }}" class="text-decoration-none">
                <div class="shortcut-card bg-units d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Units</h5>
                        <small>Manage Units</small>
                    </div>
                    <i class="mdi mdi-scale-balance shortcut-icon"></i>
                </div>
            </a>
        </div>

        {{-- Customers --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('customers.index') }}" class="text-decoration-none">
                <div class="shortcut-card bg-customers d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Customers</h5>
                        <small>Customer List</small>
                    </div>
                    <i class="mdi mdi-account-group shortcut-icon"></i>
                </div>
            </a>
        </div>

        {{-- Suppliers --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('suppliers.index') }}" class="text-decoration-none">
                <div class="shortcut-card bg-suppliers d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Suppliers</h5>
                        <small>Supplier Records</small>
                    </div>
                    <i class="mdi mdi-truck-delivery shortcut-icon"></i>
                </div>
            </a>
        </div>

        {{-- Products --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('products.index') }}" class="text-decoration-none">
                <div class="shortcut-card bg-products d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Products</h5>
                        <small>Product Catalog</small>
                    </div>
                    <i class="mdi mdi-cube-outline shortcut-icon"></i>
                </div>
            </a>
        </div>

        {{-- Purchases --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('purchases.index') }}" class="text-decoration-none">
                <div class="shortcut-card bg-purchase d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Purchases</h5>
                        <small>Purchase Records</small>
                    </div>
                    <i class="mdi mdi-cart-arrow-down shortcut-icon"></i>
                </div>
            </a>
        </div>

        {{-- Sales --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('sales.index') }}" class="text-decoration-none">
                <div class="shortcut-card bg-sales d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Sales</h5>
                        <small>Sales Overview</small>
                    </div>
                    <i class="mdi mdi-cart-check shortcut-icon"></i>
                </div>
            </a>
        </div>

        {{-- Stock --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('stocks.index') }}" class="text-decoration-none">
                <div class="shortcut-card bg-stock d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Stock</h5>
                        <small>Inventory</small>
                    </div>
                    <i class="mdi mdi-warehouse shortcut-icon"></i>
                </div>
            </a>
        </div>

        {{-- Employees --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('employees.index') }}" class="text-decoration-none">
                <div class="shortcut-card bg-employee d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Employees</h5>
                        <small>Team Members</small>
                    </div>
                    <i class="mdi mdi-account-tie shortcut-icon"></i>
                </div>
            </a>
        </div>

        {{-- Salaries --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('salaries.index') }}" class="text-decoration-none">
                <div class="shortcut-card bg-salary d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Salaries</h5>
                        <small>Payroll</small>
                    </div>
                    <i class="mdi mdi-cash-multiple shortcut-icon"></i>
                </div>
            </a>
        </div>

        {{-- Expenses --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('expenses.index') }}" class="text-decoration-none">
                <div class="shortcut-card bg-expense d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Expenses</h5>
                        <small>Cost Tracking</small>
                    </div>
                    <i class="mdi mdi-cash-remove shortcut-icon"></i>
                </div>
            </a>
        </div>

        {{-- Reports --}}
        <div class="col-xl-3 col-lg-4 col-md-6">
            <a href="{{ route('sales.reports') }}" class="text-decoration-none">
                <div class="shortcut-card bg-report d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Reports</h5>
                        <small>Analytics</small>
                    </div>
                    <i class="mdi mdi-file-chart shortcut-icon"></i>
                </div>
            </a>
        </div>

    </div>

</div>
@endsection
