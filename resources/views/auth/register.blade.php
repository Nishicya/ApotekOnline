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
                <h4>New here?</h4>
                <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
                <form class="pt-3" action="{{route('register-user')}}" method="POST">
                  @csrf
                  <div class="form-group">
                    <input type="text" class="form-control form-control-lg" placeholder="Username" name="name" value="{{ old('name') }}" required>
                    <span class="text-danger">@error('name') {{ $message }} @enderror</span>
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control form-control-lg" placeholder="Email" name="email" value="{{ old('email') }}" required>
                    <span class="text-danger">@error('email') {{ $message }} @enderror</span>
                  </div>
                  <div class="form-group">
                    <input type="no_hp" class="form-control form-control-lg" placeholder="Phone Number" name="no_hp" required>
                    <span class="text-danger">@error('no_hp') {{ $message }} @enderror</span>
                  </div>
                  <div class="form-group">
                    <select class="form-control form-control-lg" name="role" id="role" required>
                      <option disabled selected>Select Role</option>
                      <option value="admin">Admin</option>
                      <option value="apoteker">Apoteker</option>
                      <option value="pemilik">Pemilik</option>
                      <option value="kasir">Kasir</option>
                      <option value="karyawan">Karyawan</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" name="password" placeholder="Password" required>
                    <span class="text-danger">@error('password') {{ $message }} @enderror</span>
                  </div>
                  <div class="mt-3">
                    <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Sign Up</button>
                  </div>
                  <div class="text-center mt-4 font-weight-light">
                    Already have an account? <a href="{{route('login')}}" class="text-primary">Login</a>
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
