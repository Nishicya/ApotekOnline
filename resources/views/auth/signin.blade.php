<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Signup Form</title>
    <link rel="stylesheet" href="{{asset('auth/styles.css')}}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <div class="form-box login">
            <form method="POST" action="{{ route('signin-user') }}">
                @csrf
                <h1>Login</h1>
                <div class="input-box">
                    <input name="email" type="text" class="form-control form-control-lg" id="email" placeholder="Email" required value="{{ old('email') }}">
                    <i class='bx bxs-user'></i>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-box">
                    <input name="password" type="password" class="form-control form-control-lg" id="password" placeholder="Password">
                    <i class='bx bxs-lock-alt' ></i>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="forgot-link">
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                <p>or login with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-google' ></i></a>
                    <a href="#"><i class='bx bxl-facebook' ></i></a>
                    <a href="#"><i class='bx bxl-github' ></i></a>
                    <a href="#"><i class='bx bxl-linkedin' ></i></a>
                </div>
            </form>
        </div>

        <div class="form-box register">
            <form  method="POST" action="{{ route('register-user') }}">
                <h1>Registration</h1>
                @csrf
                <!-- Ensure this directive is present -->
                @if(Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif
                @if(Session::has('fail'))
                    <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                @endif
                <div class="input-box">
                    <input name="nama_pelanggan" type="text" class="form-control form-control-lg" id="nama_pelanggan" placeholder="Username" required value="{{ old('nama_pelanggan') }}">
                    <i class='bx bxs-user'></i>
                    @if ($errors->has('nama_pelanggan'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('nama_pelanggan') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-box">
                    <input name="email" type="email" class="form-control form-control-lg" id="email" placeholder="Email" required value="{{ old('email') }}">
                    <i class='bx bxs-envelope' ></i>
                    @if ($errors->has('email'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-box">
                    <input name="no_hp" type="no_hp" class="form-control form-control-lg" id="no_hp" placeholder="Phone Number" required value="{{ old('no_hp') }}">
                    <i class='bx bxs-phone'></i>
                    @if ($errors->has('no_hp'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('no_hp') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-box">
                    <input name="password" type="password" class="form-control form-control-lg" id="password" placeholder="Password">
                    <i class='bx bxs-lock-alt' ></i>
                    @if ($errors->has('password'))
                        <span class="invalid-feedback" style="display: block;" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
                <button type="submit" class="btn">Register</button>
                <p>or register with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-google' ></i></a>
                    <a href="#"><i class='bx bxl-facebook' ></i></a>
                    <a href="#"><i class='bx bxl-github' ></i></a>
                    <a href="#"><i class='bx bxl-linkedin' ></i></a>
                </div>
            </form>
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button class="btn register-btn">Register</button>
            </div>

            <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1>
                <p>Already have an account?</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>

    <script src="{{asset('auth/login/main.js')}}"></script>
</body>
</html>