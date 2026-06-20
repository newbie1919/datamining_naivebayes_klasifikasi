@extends('auth.layout')
@section('title', 'Login')

@section('auth_content')
<div class="auth-shell">
	<div class="auth-card">
		<div class="auth-card-inner">
			<h1 class="auth-title">Selamat<br>Datang Kembali</h1>
			<x-alert />
			<x-no-script />
			<x-caps-lock />
			<form action="{{ route('login.submit') }}" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="auth-field">
					<label for="email">Email</label>
					<input type="email" class="auth-input @error('email') is-invalid @enderror"
						placeholder="email@example.com" id="email" name="email" value="{{ old('email') }}" autofocus required>
					@error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="auth-field">
					<label for="password">Kata Sandi</label>
					<div class="password-field">
						<input type="password" placeholder="Kata Sandi" name="password" id="password" minlength="8"
							class="auth-input password-input @error('password') is-invalid @enderror" maxlength="20" required>
						<button type="button" class="password-toggle" id="toggle-password" aria-controls="password"
							aria-label="Tampilkan kata sandi" aria-pressed="false">
							<i class="fa-regular fa-eye" aria-hidden="true"></i>
							<span>Tampilkan</span>
						</button>
					</div>
					@error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
				</div>
				<div class="auth-options">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
						<label class="form-check-label" for="remember">Ingat Kata Sandi</label>
					</div>
					<a href="{{ route('password.forget') }}" class="text-decoration-none">Lupa Kata Sandi</a>
				</div>
				<button type="submit" class="auth-button">Masuk</button>
			</form>
			<div class="auth-footnote">
				Belum Punya Akun? <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Daftar</a>
			</div>
		</div>
	</div>
	<div class="auth-hero">
		<div class="auth-hero-inner">
			<img class="auth-logo" src="https://upload.wikimedia.org/wikipedia/commons/9/97/Logo_PLN.png" alt="Logo PLN">
			<div class="auth-hero-title">Sistem Klasifikasi Penerima<br>Subsidi Listrik</div>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	document.addEventListener('DOMContentLoaded', function () {
		const passwordInput = document.getElementById('password');
		const toggleButton = document.getElementById('toggle-password');

		if (!passwordInput || !toggleButton) {
			return;
		}

		toggleButton.addEventListener('click', function () {
			const isHidden = passwordInput.type === 'password';

			passwordInput.type = isHidden ? 'text' : 'password';
			toggleButton.setAttribute('aria-pressed', String(isHidden));
			toggleButton.setAttribute('aria-label', isHidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
			toggleButton.innerHTML = isHidden
				? '<i class="fa-regular fa-eye-slash" aria-hidden="true"></i><span>Sembunyikan</span>'
				: '<i class="fa-regular fa-eye" aria-hidden="true"></i><span>Tampilkan</span>';
		});
	});
</script>
<style>
	.password-field {
		position: relative;
	}

	.password-input {
		padding-right: 7rem;
	}

	.password-toggle {
		position: absolute;
		top: 50%;
		right: 0;
		transform: translateY(-50%);
		display: inline-flex;
		align-items: center;
		gap: 0.35rem;
		border: 0;
		background: transparent;
		color: #6b7280;
		font-size: 0.875rem;
		font-weight: 600;
		padding: 0;
	}

	.password-toggle:hover {
		color: #1f2a44;
	}
</style>
@endsection
