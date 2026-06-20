<!DOCTYPE html>
<html>

<head>
	<title>
		@yield('title') | Aplikasi Klasifikasi Penerima Subsidi Listrik
	</title>
	<!-- Favicons -->
	<link rel="apple-touch-icon" href="{{asset('assets/img/favicon/apple-touch-icon.png')}}" sizes="180x180">
	<link rel="icon" href="{{asset('assets/img/favicon/favicon-32x32.png')}}" sizes="32x32" type="image/png">
	<link rel="icon" href="{{asset('assets/img/favicon/favicon-16x16.png')}}" sizes="16x16" type="image/png">
	<link rel="mask-icon" href="{{asset('assets/img/favicon/safari-pinned-tab.svg')}}" color="#563d7c">
	<link rel="icon" href="{{asset('assets/img/favicon/favicon.ico')}}">
	<meta name="msapplication-config" content="{{asset('assets/img/favicons/browserconfig.xml')}}">
	<meta name="theme-color" content="#563d7c">
	<meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">

	<!-- Vendor CSS -->
	{{--
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	--}}

	<!-- Fontawesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
		integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />

	<!-- Volt CSS -->
	<link type="text/css" href="{{asset('css/volt.css')}}" rel="stylesheet">

	<!-- Core -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
	</script>

	<!-- Vendor JS -->
	<script src="{{asset('assets/js/on-screen.umd.min.js')}}"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"
		integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

	<!-- Smooth scroll -->
	<script src="{{asset('assets/js/smooth-scroll.polyfills.min.js')}}"></script>

	<!-- Volt JS -->
	<script src="{{asset('assets/js/volt.js')}}"></script>
	<style>
		:root {
			--auth-bg: #ecebe7;
			--auth-bg-accent: #e1e7ee;
			--auth-ink: #293241;
			--auth-muted: #6b7280;
			--auth-card: #ffffff;
			--auth-outline: #e4e7ec;
			--auth-primary: #1f2a44;
			--auth-primary-strong: #111827;
			--auth-accent: #f4b740;
		}
		.auth-body {
			font-family: "Work Sans", "Sora", sans-serif;
			background: var(--auth-bg);
			color: var(--auth-ink);
			min-height: 100vh;
		}
		.auth-main {
			min-height: 100vh;
			display: flex;
			align-items: stretch;
		}
		.auth-shell {
			display: grid;
			grid-template-columns: minmax(320px, 460px) 1fr;
			gap: 2.5rem;
			width: 100%;
			padding: 3rem 4rem;
			background:
				radial-gradient(circle at 5% 10%, rgba(255, 255, 255, 0.7), transparent 40%),
				linear-gradient(135deg, var(--auth-bg), var(--auth-bg-accent));
		}
		.auth-card {
			background: var(--auth-card);
			border-radius: 18px;
			box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
			padding: 2.5rem 2.5rem 2.25rem;
			display: flex;
			align-items: center;
		}
		.auth-card-inner {
			width: 100%;
		}
		.auth-title {
			font-family: "Sora", sans-serif;
			font-weight: 700;
			font-size: 2.1rem;
			line-height: 1.25;
			margin-bottom: 2rem;
			color: var(--auth-primary);
		}
		.auth-field {
			margin-bottom: 1.5rem;
		}
		.auth-field label {
			font-size: 0.9rem;
			color: var(--auth-muted);
			margin-bottom: 0.35rem;
		}
		.auth-input {
			width: 100%;
			border: none;
			border-bottom: 1px solid var(--auth-outline);
			border-radius: 0;
			padding: 0.6rem 0;
			font-size: 1rem;
			background: transparent;
			color: var(--auth-ink);
		}
		.auth-input:focus {
			outline: none;
			border-bottom-color: var(--auth-primary);
			box-shadow: 0 2px 0 0 var(--auth-primary);
		}
		.auth-options {
			display: flex;
			align-items: center;
			justify-content: space-between;
			font-size: 0.9rem;
			margin: 0.5rem 0 1.5rem;
		}
		.auth-button {
			background: var(--auth-primary);
			color: #fff;
			border: none;
			border-radius: 999px;
			padding: 0.75rem 1rem;
			font-weight: 600;
			width: 100%;
			transition: transform 0.2s ease, box-shadow 0.2s ease;
		}
		.auth-button:hover {
			transform: translateY(-1px);
			box-shadow: 0 10px 24px rgba(31, 42, 68, 0.2);
		}
		.auth-hero {
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 2rem;
		}
		.auth-hero-inner {
			text-align: center;
			max-width: 460px;
		}
		.auth-logo {
			width: 140px;
			height: auto;
			margin-bottom: 1.5rem;
		}
		.auth-hero-title {
			font-family: "Sora", sans-serif;
			font-weight: 600;
			font-size: 1.6rem;
			line-height: 1.4;
			color: var(--auth-primary-strong);
		}
		.auth-footnote {
			margin-top: 2.5rem;
			text-align: center;
			font-size: 0.9rem;
			color: var(--auth-muted);
		}
		@media (max-width: 992px) {
			.auth-shell {
				grid-template-columns: 1fr;
				padding: 2rem;
			}
			.auth-hero {
				order: -1;
				padding: 1rem;
			}
			.auth-card {
				padding: 2rem;
			}
		}
	</style>
</head>

<body class="auth-body">
	<main class="auth-main">
		@if(View::hasSection('auth_content'))
			@yield('auth_content')
		@else
			<section class="mt-5 bg-soft d-flex align-items-center">
				<div class="container">
					<div class="row justify-content-center">
						@yield('back')
						<div class="col d-flex align-items-center justify-content-center">
							<div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
								<div class="text-center text-md-center mb-4 mt-md-0">
									<h1 class="h3">@yield('header')</h1>
									@hasSection('desc')
									<p class="mb-4">@yield('desc')</p>
									@endif
								</div>
								<x-alert />
								<x-no-script />
								<x-caps-lock />
								@yield('form')
							</div>
						</div>
					</div>
				</div>
			</section>
			<footer class="p-5 mb-4">
				<div class="container">
					<div class="col-12 d-flex align-items-center justify-content-center">
						<p class="mb-0 text-center">
							© <span class="current-year"></span> Data Mining
						</p>
					</div>
				</div>
			</footer>
		@endif
	</main><script type="text/javascript" src="{{ asset('assets/js/capslock.js') }}"></script>
	@yield('js')
</body>

</html>

