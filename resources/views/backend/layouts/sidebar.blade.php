 <div data-simplebar class="h-100">
     <div id="sidebar-menu">
         <ul class="metismenu list-unstyled" id="side-menu">
             <li class="menu-title">Main</li>
             <li>
                 <a href="{{ route('dashboard') }}" class="waves-effect">
                     <i class="mdi mdi-home"></i>
                     <span>Dashboard</span>

                 </a>
             </li>
             <li>
                 <a href="javascript: void(0);" class="has-arrow waves-effect">
                     <i class="mdi mdi-account-group"></i>
                     <span>Units</span>
                 </a>
                 <ul class="sub-menu" aria-expanded="false">
                     <li><a href="{{ route('units.index') }}">Unit List</a></li>
                     <li><a href="{{ route('units.create') }}">Add Unit</a></li>
                 </ul>
             </li>
             <li>
                 <a href="javascript: void(0);" class="has-arrow waves-effect">
                     <i class="mdi mdi-account-group"></i>
                     <span>Customers</span>
                 </a>
                 <ul class="sub-menu" aria-expanded="false">
                     <li><a href="{{ route('customers.index') }}">Customer List</a></li>
                     <li><a href="{{ route('customers.create') }}">Add Customer</a></li>
                 </ul>
             </li>

             <li>
                 <a href="javascript: void(0);" class="has-arrow waves-effect">
                     <i class="mdi mdi-account-group"></i>
                     <span>Suppliers</span>
                 </a>
                 <ul class="sub-menu" aria-expanded="false">
                     <li><a href="{{ route('suppliers.index') }}">Supplier List</a></li>
                     <li><a href="{{ route('suppliers.create') }}">Add Supplier</a></li>
                 </ul>
             </li>
             <li>
                 <a href="javascript: void(0);" class="has-arrow waves-effect">
                     <i class="mdi mdi-account-group"></i>
                     <span>Products</span>
                 </a>
                 <ul class="sub-menu" aria-expanded="false">
                     <li><a href="{{ route('products.index') }}">Product List</a></li>
                     <li><a href="{{ route('products.create') }}">Add Product</a></li>
                 </ul>
             </li>
             <li>
                 <a href="javascript: void(0);" class="has-arrow waves-effect">
                     <i class="mdi mdi-account-group"></i>
                     <span>Purchases</span>
                 </a>
                 <ul class="sub-menu" aria-expanded="false">
                     <li><a href="{{ route('purchases.index') }}">Purchase List</a></li>
                     <li><a href="{{ route('purchases.create') }}">Add Purchase</a></li>
                 </ul>
             </li>
             <li>
                 <a href="javascript: void(0);" class="has-arrow waves-effect">
                     <i class="mdi mdi-account-group"></i>
                     <span>Sales</span>
                 </a>
                 <ul class="sub-menu" aria-expanded="false">
                     <li><a href="{{ route('sales.index') }}">Sales List</a></li>
                     <li><a href="{{ route('sales.create') }}">Add Sale</a></li>
                 </ul>
             </li>
             <li>
                 <a href="javascript: void(0);" class="has-arrow waves-effect">
                     <i class="mdi mdi-account-group"></i>
                     <span>Stocks</span>
                 </a>
                 <ul class="sub-menu" aria-expanded="false">
                     <li><a href="{{ route('stocks.index') }}">Stock</a></li>
                     <li><a href="{{ route('stocks.log') }}">Stock Log</a></li>
                 </ul>
             </li>
             <li>
                 <a href="javascript: void(0);" class="has-arrow waves-effect">
                     <i class="mdi mdi-account-group"></i>
                     <span>Reports</span>
                 </a>
                 <ul class="sub-menu" aria-expanded="false">
                     <li><a href="{{ route('purchases.reports') }}">Purchase Report</a></li>
                     <li><a href="{{ route('sales.reports') }}">Sale Report</a></li>
                     <li><a href="{{ route('customers.paymentReports') }}">Payment History Report</a></li>
                 </ul>
             </li>
             <li>
                 <a href="javascript: void(0);" class="has-arrow waves-effect">
                     <i class="mdi mdi-account-group"></i>
                     <span>Expenses</span>
                 </a>
                 <ul class="sub-menu" aria-expanded="false">
                     <li><a href="{{ route('expenses.index') }}">Expense</a></li>
                 </ul>
             </li>
             <li>
                 <a href="javascript: void(0);" class="has-arrow waves-effect">
                     <i class="mdi mdi-account-group"></i>
                     <span>Employees</span>
                 </a>
                 <ul class="sub-menu" aria-expanded="false">
                     <li><a href="{{ route('employees.index') }}">Employee</a></li>
                     <li><a href="{{ route('salaries.index') }}">Salaries</a></li>
                 </ul>
             </li>
         </ul>
     </div>
 </div>
