@extends('layout')
@section('title', 'Dashboard')
@section('content')
@php($roleLabel = auth()->user()?->getRoleLabel())
<p>Selamat datang di Aplikasi Klasifikasi Kelayakan Calon Penerima Subsidi Listrik,
	{{ $roleLabel }}. Aplikasi ini menggunakan Naive Bayes sebagai algoritma klasifikasi.</p>
<div class="row">
	<div class="col-md-4 mb-3 mb-md-0">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-start justify-content-between">
					<div class="content-left">
						<span>Data Training</span>
						<div class="d-flex align-items-end mt-2">
							<h3 class="mb-0 me-2">{{ $train }}</h3>
						</div>
					</div>
					<span class="badge bg-primary rounded p-2">
						<i class="fas fa-file-lines"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 mb-3 mb-md-0">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-start justify-content-between">
					<div class="content-left">
						<span>Data Testing</span>
						<div class="d-flex align-items-end mt-2">
							<h3 class="mb-0 me-2">{{ $test }}</h3>
						</div>
					</div>
					<span class="badge bg-success rounded p-2">
						<i class="fas fa-file-lines"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 mb-3 mb-md-0">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-start justify-content-between">
					<div class="content-left">
						<span>Total Data</span>
						<div class="d-flex align-items-end mt-2">
							<h3 class="mb-0 me-2">{{$total}}</h3>
						</div>
					</div>
					<span class="badge bg-secondary rounded p-2">
						<i class="fas fa-file-lines"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row mt-4">
	<div class="col-lg-4 mb-3">
		<div class="card h-100">
			<div class="card-header"><b>Distribusi Kelas</b></div>
			<div class="card-body">
				<canvas id="chart-distribusi"></canvas>
			</div>
		</div>
	</div>
	<div class="col-lg-4 mb-3">
		<div class="card h-100">
			<div class="card-header"><b>Performa Model (Testing Berlabel)</b></div>
			<div class="card-body">
				<canvas id="chart-performa"></canvas>
			</div>
		</div>
	</div>
	<div class="col-lg-4 mb-3">
		<div class="card h-100">
			<div class="card-header"><b>Tren Klasifikasi (7 Hari)</b></div>
			<div class="card-body">
				<canvas id="chart-tren"></canvas>
			</div>
		</div>
	</div>
</div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
	const classDist = @json($classDist);
	const modelPerf = @json($modelPerf);
	const trend = @json($trend);

	new Chart(document.getElementById('chart-distribusi'), {
		type: 'bar',
		data: {
			labels: classDist.labels,
			datasets: [
				{ label: 'Training', data: classDist.train, backgroundColor: '#0d6efd' },
				{ label: 'Testing', data: classDist.test, backgroundColor: '#198754' }
			]
		},
		options: { responsive: true, maintainAspectRatio: false }
	});

	new Chart(document.getElementById('chart-performa'), {
		type: 'radar',
		data: {
			labels: modelPerf.labels,
			datasets: [{
				label: 'Persentase',
				data: modelPerf.values,
				backgroundColor: 'rgba(13,110,253,0.2)',
				borderColor: '#0d6efd',
				pointBackgroundColor: '#0d6efd'
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			scales: { r: { suggestedMin: 0, suggestedMax: 100 } }
		}
	});

	new Chart(document.getElementById('chart-tren'), {
		type: 'line',
		data: {
			labels: trend.labels,
			datasets: [{
				label: 'Jumlah Klasifikasi',
				data: trend.values,
				borderColor: '#dc3545',
				backgroundColor: 'rgba(220,53,69,0.2)',
				fill: true,
				tension: 0.2
			}]
		},
		options: { responsive: true, maintainAspectRatio: false }
	});
</script>
@endsection
