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
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset('be/images/favicon.png') }}" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="main-panel">
        <div class="content-wrapper d-flex align-items-center auth px-0">
          <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
              <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                <div class="brand-logo">
                  <img src="{{ asset('be/images/logo.svg') }}" alt="logo">
                </div>
                    <h4>Hello! let's get started</h4>
                    <h6 class="font-weight-light">Sign in to continue.</h6>
                <form class="pt-3" action="{{route('login-user')}}" method="POST">
                @if(Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                @if(Session::has('fail'))
                    <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                @endif
                    @csrf
                <div class="form-group">
                    <input type="email" class="form-control form-control-lg" placeholder="Email" name="email">
                    <span class="text-danger">@error('email') {{ $message }} @enderror</span>
                </div>
                    <div class="form-group">
                        <input type="password" class="form-control form-control-lg" placeholder="Password" name='password'>
                        <span class="text-danger">@error('password') {{ $message }} @enderror</span>
                    </div>
                    <div class="mt-3">
                        <button type ="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                    </div>
                    <div class="my-2 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                        <label class="form-check-label text-muted">
                            <input type="checkbox" class="form-check-input">
                            Keep me signed in
                        </label>
                        </div>
                        <a href="#" class="auth-link text-black">Forgot password?</a>
                        </div>
                    <div class="text-center mt-4 font-weight-light">
                        Don't have an account? <a href="{{route('register')}}" class="text-primary">Create</a>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="{{ asset('be/vendors/base/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="{{ asset('be/js/template.js') }}"></script>
  <!-- endinject -->
</body>

</html>
