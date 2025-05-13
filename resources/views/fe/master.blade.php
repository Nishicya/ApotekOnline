<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>Electro - HTML Ecommerce Template</title>

		<!-- Google font -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

		<!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="{{ asset('fe/css/bootstrap.min.css') }}"/>

		<!-- Slick -->
		<link type="text/css" rel="stylesheet" href="{{ asset('fe/css/slick.css') }}"/>
		<link type="text/css" rel="stylesheet" href="{{ asset('fe/css/slick-theme.css') }}"/>

		<!-- nouislider -->
		<link type="text/css" rel="stylesheet" href="{{ asset('fe/css/nouislider.min.css') }}"/>

		<!-- Font Awesome Icon -->
		<link rel="stylesheet" href="{{ asset('fe/css/font-awesome.min.css') }}">

		<!-- Custom stlylesheet -->
		<link type="text/css" rel="stylesheet" href="{{ asset('fe/css/style.css') }}"/>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

    </head>
	<style>
		#header {
			box-shadow: 0 1px 5px rgba(0,0,0,0.1);
		}
		
		.main-nav {
			display: flex;
			list-style: none;
		}
		
		.main-nav li {
			position: relative;
		}
		
		.main-nav li a {
			color: #333;
			font-size: 16px;
			font-weight: 500;
			text-decoration: none;
			transition: color 0.3s;
		}
		
		.main-nav li.active a,
		.main-nav li a:hover {
			color: #e61937;
		}
		
		.header-ctn {
			display: flex;
			align-items: center;
			justify-content: flex-end;
		}
		
		.header-ctn .dropdown {
			margin-right: 15px;
		}
		
		.header-ctn .dropdown-toggle img {
			border-radius: 50%;
			border: 2px solid #e61937;
			box-shadow: 0 0 5px rgba(0,0,0,0.3);
		}
	</style>
	<body>
		<!-- HEADER -->
		<header>
			<!-- TOP HEADER -->
			<div id="top-header">
				<div class="container">
					<ul class="header-links pull-left">
						<li><a href="#"><i class="fa fa-phone"></i> +021-95-51-84</a></li>
						<li><a href="#"><i class="fa fa-envelope-o"></i> email@email.com</a></li>
						<li><a href="#"><i class="fa fa-map-marker"></i> 1734 Stonecoal Road</a></li>
					</ul>
					<ul class="header-links pull-right">
						<li><a href="#"><i class="fa fa-dollar"></i> USD</a></li>
						<li><a href="#"><i class="fa fa-user-o"></i> My Account</a></li>
					</ul>
				</div>
			</div>
			<!-- /TOP HEADER -->

			<!-- HEADER -->
			@yield('header')
			<!-- /HEADER -->

			<!-- NAVIGATION -->
			@yield('navbar')
			<!-- /NAVIGATION -->

			<!-- BANNER -->
			@yield('banner')
			<!-- /BANNER -->

			<!-- SECTION -->
			@yield('content')
			<!-- /SECTION -->

			<!-- NEWSLETTER -->
			@yield('newsletter')
			<!-- /NEWSLETTER -->

			<!-- FOOTER -->
			@yield('footer')
			<!-- /FOOTER -->

			<!-- PROFILE -->
			@yield('profile')
			<!-- /PROFILE -->

		<!-- jQuery Plugins -->
		<script src="{{ asset('fe/js/jquery.min.js') }}"></script>
		<script src="{{ asset('fe/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('fe/js/slick.min.js') }}"></script>
		<script src="{{ asset('fe/js/nouislider.min.js') }}"></script>
		<script src="{{ asset('fe/js/jquery.zoom.min.js') }}"></script>
		<script src="{{ asset('fe/js/main.js') }}"></script>

	</body>
</html>