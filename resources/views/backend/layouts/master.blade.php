<!doctype html>
<html lang="en">
    <head>
        @include('backend.layouts.head')
    </head>

    <body data-topbar="colored">
        <div id="layout-wrapper">
            <header id="page-topbar">
                @include('backend.layouts.header')
            </header>
            <div class="vertical-menu">
               @include('backend.layouts.sidebar')
            </div>
            <div class="main-content">
                <div class="page-content">
                    @yield('content')
                </div>
                <footer class="footer">
                    @include('backend.layouts.footer')
                </footer>
            </div>
        </div>
        <div class="right-bar">
            @include('backend.layouts.rightbar')
        </div>
        <div class="rightbar-overlay"></div>
        @include('backend.layouts.scripts')
    </body>
</html>
