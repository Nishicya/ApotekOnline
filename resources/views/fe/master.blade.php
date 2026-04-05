<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>@yield('page_title', 'HEALTHIFY - Home')</title>

		<!-- Google font -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

		<!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="{{ asset('fe/assets/css/bootstrap.min.css') }}"/>

		<!-- Slick -->
		<link type="text/css" rel="stylesheet" href="{{ asset('fe/assets/css/slick.css') }}"/>
		<link type="text/css" rel="stylesheet" href="{{ asset('fe/assets/css/slick-theme.css') }}"/>

		<!-- nouislider -->
		<link type="text/css" rel="stylesheet" href="{{ asset('fe/assets/css/nouislider.min.css') }}"/>

		<!-- Font Awesome Icon -->
		<link rel="stylesheet" href="{{ asset('fe/assets/css/font-awesome.min.css') }}">

		<!-- Custom stlylesheet -->
		<link type="text/css" rel="stylesheet" href="{{ asset('fe/assets/css/style.css') }}"/>
		<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

		<!-- jQuery Plugins -->
		<script src="{{ asset('fe/assets/js/jquery.min.js') }}"></script>
		<script src="{{ asset('fe/assets/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('fe/assets/js/slick.min.js') }}"></script>
		<script src="{{ asset('fe/assets/js/nouislider.min.js') }}"></script>
		<script src="{{ asset('fe/assets/js/jquery.zoom.min.js') }}"></script>
		<script src="{{ asset('fe/assets/js/main.js') }}"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
					<ul class="header-links justify-content-center">
						<li><a href="#"><i class="fa fa-phone"></i> +021-95-51-84</a></li>
						<li><a href="#"><i class="fa fa-envelope-o"></i> email@email.com</a></li>
						<li><a href="#"><i class="fa fa-map-marker"></i> 1734 Stonecoal Road</a></li>
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

		

	</body>
	<script>
		// Global Cart Auto-Refresh
		let cartRefreshInterval = null;
		let isAddingToCart = false; // Flag to prevent duplicate requests
		
		function startCartAutoRefresh() {
			if (cartRefreshInterval === null) {
				cartRefreshInterval = setInterval(function() {
					if (typeof updateCartDropdown === 'function') {
						updateCartDropdown();
					}
				}, 2000); // Refresh setiap 2 detik
			}
		}
		
		function stopCartAutoRefresh() {
			if (cartRefreshInterval !== null) {
				clearInterval(cartRefreshInterval);
				cartRefreshInterval = null;
			}
		}
		
		$(document).ready(function() {
			// Remove any existing handlers to prevent duplicates
			$(document).off('show.bs.dropdown', '.dropdown');
			$(document).off('hide.bs.dropdown', '.dropdown');
			$(document).off('click', '.add-to-cart-btn');
			
			// Start auto-refresh ketika dropdown dibuka
			$(document).on('show.bs.dropdown', '.dropdown', function() {
				if ($(this).find('.cart-dropdown').length) {
					console.log('[Cart] Dropdown opened - fetching items immediately');
					// Fetch cart items immediately saat dropdown dibuka
					if (typeof updateCartDropdown === 'function') {
						updateCartDropdown();
					}
					startCartAutoRefresh();
				}
			});
			
			// Stop auto-refresh ketika dropdown ditutup
			$(document).on('hide.bs.dropdown', '.dropdown', function() {
				if ($(this).find('.cart-dropdown').length) {
					console.log('[Cart] Dropdown closed - stopping auto-refresh');
					stopCartAutoRefresh();
				}
			});
			
			// Global Add to Cart Handler (SINGLE HANDLER ONLY)
			$(document).on('click', '.add-to-cart-btn', function(e) {
				e.preventDefault();
				
				if (isAddingToCart) {
					console.warn('[Cart] Already adding to cart, preventing duplicate request');
					return false;
				}
				
				let button = $(this);
				let productId = button.data('product-id');
				let quantity = 1;
				
				console.log('[Cart] Add to cart clicked for product', productId);
				
				@auth('pelanggan')
					isAddingToCart = true;
					button.prop('disabled', true);
					button.addClass('btn-loading');
					button.find('.btn-text').text('Menambahkan...');
					
					$.ajax({
						url: '{{ route("keranjang.add") }}',
						type: 'POST',
						timeout: 10000,
						data: {
							_token: '{{ csrf_token() }}',
							id_obat: productId,
							quantity: quantity
						},
						success: function(response) {
							console.log('[Cart] Add to cart success:', response);
							if (response.success) {
								$('.keranjang-count').text(response.cart_count);
								
								// Force immediate cart update dan buka dropdown
								if (typeof updateCartDropdown === 'function') {
									setTimeout(function() {
										updateCartDropdown();
										// Auto-open dropdown cart
										$('.dropdown:has(.cart-dropdown)').find('.dropdown-toggle').dropdown('toggle');
									}, 200);
								}
								
								Swal.fire({
									icon: 'success',
									title: 'Berhasil!',
									text: response.message,
									timer: 2000,
									showConfirmButton: false
								});
							} else {
								Swal.fire({
									icon: 'error',
									title: 'Gagal',
									text: response.message
								});
							}
						},
						error: function(xhr, status, error) {
							console.error('[Cart] Add to cart error:', status, error);
							let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
							
							if (status === 'timeout') {
								errorMessage = 'Request timeout';
							}
							
							Swal.fire({
								icon: 'error',
								title: 'Error',
								text: errorMessage
							});
						},
						complete: function() {
							button.prop('disabled', false);
							button.removeClass('btn-loading');
							button.find('.btn-text').text('Add to Cart');
							isAddingToCart = false;
						}
					});
				@else
					Swal.fire({
						icon: 'info',
						title: 'Login Required',
						text: 'Anda harus login terlebih dahulu',
						confirmButtonText: 'Login',
						showCancelButton: true,
						cancelButtonText: 'Cancel'
					}).then((result) => {
						if (result.isConfirmed) {
							window.location.href = '{{ route("signin") }}';
						}
					});
				@endauth
			});
		});
	</script>
</html>