<script src="{{ asset('storage/assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('storage/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('storage/assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('storage/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('storage/assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('storage/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('storage/assets/libs/peity/jquery.peity.min.js') }}"></script>
<script src="{{ asset('storage/assets/libs/morris.js/morris.min.js') }}"></script>
<script src="{{ asset('storage/assets/libs/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('storage/assets/js/pages/dashboard.init.js') }}"></script>
<script src="{{ asset('storage/assets/js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
  @if(Session::has('message'))
  toastr.options =
  {
  	"closeButton" : true,
  	"progressBar" : true
  }
  		toastr.success("{{ session('message') }}");
  @endif

  @if(Session::has('error'))
  toastr.options =
  {
  	"closeButton" : true,
  	"progressBar" : true
  }
  		toastr.error("{{ session('error') }}");
  @endif

  @if(Session::has('info'))
  toastr.options =
  {
  	"closeButton" : true,
  	"progressBar" : true
  }
  		toastr.info("{{ session('info') }}");
  @endif

  @if(Session::has('warning'))
  toastr.options =
  {
  	"closeButton" : true,
  	"progressBar" : true
  }
  		toastr.warning("{{ session('warning') }}");
  @endif
</script>
@stack('scripts')
