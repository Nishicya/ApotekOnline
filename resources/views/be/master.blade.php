<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kapella Bootstrap Admin Dashboard Template</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{ asset('be/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('be/vendors/base/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="{{ asset('be/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('be/css1/azia.css') }}">
    <link rel="stylesheet" href="{{ asset('be/css1/azia.min.css') }}">
    <!-- endinject -->
    <link rel="shortcut icon" href="{{ asset('be/images/favicon.png') }}" />
    <link href="{{ asset('be/lib1/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('be/lib1/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('be/lib1/typicons.font/typicons.css') }}" rel="stylesheet">
    <link href="{{ asset('be/lib1/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('fe/css/style.css') }}"/>
  </head>
  <body>
    <div class="container-scroller">
      <!-- NAVBAR -->
      @yield('navbar')
      <!-- NAVBAR -->

      <!-- PROFILE -->
      @yield('profile')
      <!-- PROFILE -->

      <!-- partial -->
      @yield('content')
      <!-- partial -->

      <!-- CHARTS -->
      @yield('penjualan')
      <!-- CHARTS -->

      <!-- MEDS -->
      @yield('obat')
      <!-- MEDS -->
    </div>
    <script>  
      document.querySelectorAll('.az-header-menu .nav-link').forEach(link => {
        if (window.location.pathname.includes(link.getAttribute('href'))) {
          link.parentElement.classList.add('active', 'show');
        }
      }); 
    </script>
      <!-- SweetAlert 2 -->
      <script>
        function deleteConfirm(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, keep it'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form after confirmation
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- main-panel ends -->
    
      <!-- page-body-wrapper ends -->

		<!-- container-scroller -->
    <!-- base:js -->
    <script src="{{ asset('be/vendors/base/vendor.bundle.base.js') }}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <script src="{{ asset('be/js/template.js') }}"></script>
    <script src="{{ asset('be/js1/azia.js') }}"></script>
    <!-- endinject -->
    <!-- plugin js for this page -->
    <!-- End plugin js for this page -->
    <script src="{{ asset('be/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('be/vendors/progressbar.js/progressbar.min.js') }}"></script>
		<script src="{{ asset('be/vendors/chartjs-plugin-datalabels/chartjs-plugin-datalabels.js') }}"></script>
		<script src="{{ asset('be/vendors/justgage/raphael-2.1.4.min.js') }}"></script>
		<script src="{{ asset('be/vendors/justgage/justgage.js') }}"></script>
    <script src="{{ asset('be/js/jquery.cookie.js') }}" type="text/javascript"></script>
    <script src="{{ asset('be/js1/jquery.vmap.sampledata.js') }}" type="text/javascript"></script>
    <!-- Custom js for this page-->
    <script src="{{ asset('be/js/dashboard.js') }}"></script>
    <script src="{{ asset('be/js1/dashboard.sampledata.js') }}"></script>
    <script src="{{ asset('be/vendors/chart.js/Chart.min.js')}}"></script>
    <script src="{{ asset('be/js/chart.js')}}"></script>
    <script src="{{ asset('be/js/file-upload.js')}}"></script>
    <script src="{{ asset('be/js/feather.min.js') }}"></script>
    <script src="{{ asset('be/js/iziToast.min.js') }}"></script>
		<script src="{{ asset('fe/js/main.js') }}"></script>

    <!-- End custom js for this page-->
  </body>
</html>