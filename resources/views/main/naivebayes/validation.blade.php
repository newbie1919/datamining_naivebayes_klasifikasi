@extends('layout')
@section('title', 'Validasi dan Pengujian Sistem')
@section('content')
<style>
	.validation-card {
		border: 1px solid #dfe5f2;
		border-radius: .5rem;
		background: #fff;
		margin-bottom: 1.5rem;
	}

	.validation-card-header {
		background: #f3f5fa;
		border-bottom: 1px solid #dfe5f2;
		padding: 1rem;
		font-weight: 700;
	}

	.validation-card-body {
		padding: 1.5rem;
	}

	.result-box {
		border: 2px solid #cfe2ff;
		background: #eef6ff;
		border-radius: .5rem;
		padding: 1.5rem;
		text-align: center;
		margin: 1rem 0;
	}

	.result-box.eligible {
		border-color: #198754;
		background: #d1e7dd;
	}

	.result-box.not-eligible {
		border-color: #dc3545;
		background: #f8d7da;
	}

	.probability-bar {
		margin: 1rem 0;
	}

	.metric-item {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 0.75rem;
		border-bottom: 1px solid #e9ecef;
	}

	.metric-item:last-child {
		border-bottom: none;
	}

	.metric-label {
		font-weight: 500;
		color: #6c757d;
	}

	.metric-value {
		font-size: 1.25rem;
		font-weight: 700;
		color: #212529;
	}

	.attribute-section {
		background: #f8f9fa;
		border: 1px solid #dee2e6;
		border-radius: .5rem;
		padding: 1.5rem;
		margin-bottom: 1rem;
	}

	.attribute-grid {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
		gap: 1.5rem;
	}

	.input-group-text {
		background: #e9ecef;
		border: 1px solid #dee2e6;
	}

	.validation-status {
		font-size: 0.875rem;
		margin-top: 0.25rem;
	}

	.validation-status.valid {
		color: #198754;
	}

	.validation-status.invalid {
		color: #dc3545;
	}

	.summary-stats {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
		gap: 1rem;
		margin-bottom: 1.5rem;
	}

	.stat-card {
		background: #fff;
		border: 1px solid #dee2e6;
		border-radius: .5rem;
		padding: 1.5rem;
		text-align: center;
	}

	.stat-card-value {
		font-size: 2rem;
		font-weight: 700;
		color: #0d6efd;
	}

	.stat-card-label {
		font-size: 0.875rem;
		color: #6c757d;
		margin-top: 0.5rem;
	}

	.confidence-indicator {
		display: inline-block;
		padding: 0.5rem 1rem;
		border-radius: .25rem;
		font-weight: 600;
		font-size: 0.875rem;
	}

	.confidence-high {
		background: #d1e7dd;
		color: #0f5132;
	}

	.confidence-medium {
		background: #fff3cd;
		color: #664d03;
	}

	.confidence-low {
		background: #f8d7da;
		color: #842029;
	}

	.detail-table {
		margin-top: 1.5rem;
	}

	.detail-table table {
		width: 100%;
		border-collapse: collapse;
	}

	.detail-table th {
		background: #f3f5fa;
		padding: 0.75rem;
		border: 1px solid #dfe5f2;
		font-weight: 700;
		text-align: left;
	}

	.detail-table td {
		padding: 0.75rem;
		border: 1px solid #dfe5f2;
	}

	.compare-section {
		display: grid;
		grid-template-columns: 1fr 1fr;
		gap: 1.5rem;
		margin: 1.5rem 0;
	}

	.compare-box {
		border: 1px solid #dee2e6;
		border-radius: .5rem;
		padding: 1rem;
	}

	.compare-box-title {
		font-weight: 700;
		margin-bottom: 1rem;
		padding-bottom: 0.5rem;
		border-bottom: 2px solid #0d6efd;
	}

	.compare-box.actual .compare-box-title {
		border-bottom-color: #6c757d;
	}

	.loading-spinner {
		display: none;
		text-align: center;
		padding: 2rem;
	}

	.loading-spinner.active {
		display: block;
	}

	.tabs-nav {
		display: flex;
		border-bottom: 2px solid #dee2e6;
		margin-bottom: 1.5rem;
		gap: 1rem;
	}

	.tab-btn {
		padding: 0.75rem 1.5rem;
		border: none;
		background: transparent;
		cursor: pointer;
		font-weight: 500;
		color: #6c757d;
		border-bottom: 3px solid transparent;
		position: relative;
		bottom: -2px;
	}

	.tab-btn.active {
		color: #0d6efd;
		border-bottom-color: #0d6efd;
	}

	.tab-content {
		display: none;
	}

	.tab-content.active {
		display: block;
	}

	@media (max-width: 768px) {
		.compare-section {
			grid-template-columns: 1fr;
		}

		.attribute-grid {
			grid-template-columns: 1fr;
		}

		.tabs-nav {
			flex-wrap: wrap;
		}
	}
</style>

<!-- Header Section -->
<div class="d-flex align-items-center justify-content-between mb-4">
	<div>
		<h2 class="mb-2"><i class="fas fa-flask-vial me-2"></i>Validasi dan Pengujian Sistem</h2>
		<p class="text-muted mb-0">Uji sistem klasifikasi dengan memasukkan data pelanggan</p>
	</div>
	<div class="btn-group" role="group">
		<a href="{{ route('class.index') }}" class="btn btn-outline-primary">
			<i class="fas fa-chart-bar"></i> Hasil Klasifikasi
		</a>
		<a href="{{ route('testing.index') }}" class="btn btn-outline-secondary">
			<i class="fas fa-database"></i> Data Testing
		</a>
	</div>
</div>

<!-- Alert Notifications -->
<div id="alertContainer"></div>

<!-- Tabs Navigation -->
<div class="tabs-nav">
	<button class="tab-btn active" data-tab="single-test">
		<i class="fas fa-flask me-2"></i>Pengujian Tunggal
	</button>
	<button class="tab-btn" data-tab="batch-test">
		<i class="fas fa-layer-group me-2"></i>Pengujian Batch
	</button>
	<button class="tab-btn" data-tab="validation-report">
		<i class="fas fa-chart-line me-2"></i>Laporan Validasi
	</button>
</div>

<!-- ============ TAB 1: SINGLE TEST ============ -->
<div id="single-test" class="tab-content active">
	<div class="validation-card">
		<div class="validation-card-header">
			<i class="fas fa-edit me-2"></i>Input Data Pengujian
		</div>
		<div class="validation-card-body">
			<form id="singleTestForm">
				@csrf

				<!-- Identitas Section -->
				<div class="attribute-section">
					<h5 class="mb-3"><i class="fas fa-id-card me-2"></i>Identitas Pelanggan</h5>
					<div class="attribute-grid">
						<div class="form-floating">
							<input type="text" class="form-control" id="customerName" name="nama"
								placeholder="Nama Pelanggan" required />
							<label for="customerName">Nama Pelanggan</label>
							<div class="invalid-feedback" id="nama-error"></div>
						</div>
						<div class="form-floating">
							<input type="text" class="form-control" id="customerId" name="id_pelanggan"
								placeholder="ID Pelanggan" required />
							<label for="customerId">ID Pelanggan</label>
							<div class="invalid-feedback" id="id_pelanggan-error"></div>
						</div>
						<div class="form-floating">
							<input type="number" class="form-control" id="powerCapacity" name="daya_terpasang"
								placeholder="Daya Terpasang" min="0" />
							<label for="powerCapacity">Daya Terpasang (VA)</label>
							<div class="invalid-feedback" id="daya_terpasang-error"></div>
						</div>
					</div>
				</div>

				<!-- Attributes Section -->
				<div class="attribute-section">
					<h5 class="mb-3"><i class="fas fa-list-check me-2"></i>Atribut Klasifikasi</h5>
					<div class="attribute-grid" id="attributeContainer">
						<!-- Atribut akan di-load via JavaScript -->
						<div class="text-center text-muted py-4">
							<i class="fas fa-spinner fa-spin me-2"></i>Memuat atribut...
						</div>
					</div>
				</div>

				<!-- Actual Value (Optional) -->
				<div class="attribute-section">
					<h5 class="mb-3"><i class="fas fa-check-double me-2"></i>Nilai Aktual (Opsional)</h5>
					<p class="text-muted text-sm mb-3">Isi jika Anda ingin membandingkan hasil prediksi dengan nilai aktual</p>
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" id="includeActual" name="include_actual">
						<label class="form-check-label" for="includeActual">
							Saya tahu nilai aktual untuk data ini
						</label>
					</div>
					<div id="actualValueSection" style="display: none; margin-top: 1rem;">
						<div class="form-floating">
							<select class="form-select" id="actualValue" name="actual_value">
								<option value="">-- Pilih --</option>
								<option value="1">Layak Menerima Subsidi</option>
								<option value="0">Tidak Layak Menerima Subsidi</option>
							</select>
							<label for="actualValue">Status Aktual</label>
						</div>
					</div>
				</div>

				<!-- Action Buttons -->
				<div class="d-flex gap-2 mt-4">
					<button type="submit" class="btn btn-primary btn-lg">
						<i class="fas fa-play me-2"></i>Klasifikasi
					</button>
					<button type="reset" class="btn btn-secondary btn-lg">
						<i class="fas fa-redo me-2"></i>Reset
					</button>
				</div>
			</form>
		</div>
	</div>

	<!-- Results Section -->
	<div id="resultsSection" style="display: none;">
		<!-- Loading Spinner -->
		<div class="loading-spinner" id="loadingSpinner">
			<div class="spinner-border text-primary me-2" role="status">
				<span class="visually-hidden">Loading...</span>
			</div>
			<span>Memproses klasifikasi...</span>
		</div>

		<!-- Classification Result -->
		<div class="validation-card" id="resultCard" style="display: none;">
			<div class="validation-card-header">
				<i class="fas fa-chart-pie me-2"></i>Hasil Klasifikasi
			</div>
			<div class="validation-card-body">
				<!-- Summary Stats -->
				<div class="summary-stats">
					<div class="stat-card">
						<div class="stat-card-value" id="predictedLabel">-</div>
						<div class="stat-card-label">Hasil Prediksi</div>
					</div>
					<div class="stat-card">
						<div class="stat-card-value" id="confidence">-</div>
						<div class="stat-card-label">Tingkat Kepercayaan</div>
					</div>
					<div class="stat-card">
						<div class="stat-card-value" id="accuracy">-</div>
						<div class="stat-card-label">Akurasi Model</div>
					</div>
				</div>

				<!-- Prediction Box -->
				<div class="result-box" id="predictionBox">
					<h4 id="predictionTitle">-</h4>
					<p class="mb-0" id="predictionDesc">-</p>
				</div>

				<!-- Probability Details -->
				<div class="probability-bar">
					<label class="mb-2 d-block"><b>Probabilitas Posterior</b></label>

					<div class="mb-3">
						<div class="d-flex justify-content-between mb-2">
							<span>Layak Menerima Subsidi</span>
							<span class="badge bg-success" id="probTrue">0%</span>
						</div>
						<div class="progress" style="height: 25px;">
							<div id="probTrueBar" class="progress-bar bg-success" role="progressbar" style="width: 0%;"
								aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>

					<div>
						<div class="d-flex justify-content-between mb-2">
							<span>Tidak Layak Menerima Subsidi</span>
							<span class="badge bg-danger" id="probFalse">0%</span>
						</div>
						<div class="progress" style="height: 25px;">
							<div id="probFalseBar" class="progress-bar bg-danger" role="progressbar" style="width: 0%;"
								aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>

				<!-- Confidence Indicator -->
				<div class="mt-3 text-center">
					<span class="confidence-indicator" id="confidenceIndicator">-</span>
				</div>

				<!-- Comparison Section (if actual value provided) -->
				<div id="comparisonSection" style="display: none; margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #dee2e6;">
					<h5 class="mb-3"><i class="fas fa-balance-scale me-2"></i>Perbandingan Hasil</h5>
					<div class="compare-section">
						<div class="compare-box">
							<div class="compare-box-title text-primary">
								<i class="fas fa-robot me-2"></i>Hasil Prediksi
							</div>
							<div id="predictedValueBox">-</div>
							<div class="mt-2 small text-muted" id="predictedConfidence">Kepercayaan: -</div>
						</div>
						<div class="compare-box actual">
							<div class="compare-box-title">
								<i class="fas fa-check-circle me-2"></i>Nilai Aktual
							</div>
							<div id="actualValueBox">-</div>
							<div class="mt-2 small text-muted" id="validationResult">-</div>
						</div>
					</div>

					<!-- Validation Metrics -->
					<div class="detail-table">
						<table>
							<tr>
								<th>Metrik</th>
								<th>Hasil</th>
							</tr>
							<tr>
								<td>Kecocokan Prediksi</td>
								<td id="validationMatch">-</td>
							</tr>
							<tr>
								<td>Jenis Kesalahan</td>
								<td id="errorType">-</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Detailed Calculation -->
		<div class="validation-card" id="detailCard" style="display: none;">
			<div class="validation-card-header">
				<i class="fas fa-calculator me-2"></i>Detail Perhitungan
			</div>
			<div class="validation-card-body">
				<div id="calculationDetails">
					<!-- Will be filled dynamically -->
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ============ TAB 2: BATCH TEST ============ -->
<div id="batch-test" class="tab-content">
	<div class="validation-card">
		<div class="validation-card-header">
			<i class="fas fa-upload me-2"></i>Pengujian Batch (Upload File)
		</div>
		<div class="validation-card-body">
			<p class="mb-3">Upload file Excel atau CSV yang berisi data testing untuk divalidasi sekaligus.</p>

			<form id="batchTestForm" enctype="multipart/form-data">
				@csrf

				<div class="alert alert-info mb-3">
					<i class="fas fa-info-circle me-2"></i>
					<a href="{{ route('template-data') }}" class="alert-link">Klik di sini</a>
					untuk mengunduh template dataset
				</div>

				<div class="mb-3">
					<label for="batchFile" class="form-label"><b>Pilih File</b></label>
					<input type="file" class="form-control" id="batchFile" name="data" required
						accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.oasis.opendocument.spreadsheet,text/csv,.tsv"
						data-bs-toggle="tooltip"
						title="Format yang diterima: XLS, XLSX, ODS, CSV, TSV">
					<div class="invalid-feedback" id="batch-file-error"></div>
					<small class="text-muted d-block mt-2">Ukuran file maksimal: 10MB</small>
				</div>

				<div class="form-check mb-3">
					<input class="form-check-input" type="checkbox" id="validateActual" name="validate_actual"
						checked>
					<label class="form-check-label" for="validateActual">
						Bandingkan dengan nilai aktual jika tersedia
					</label>
				</div>

				<div class="d-flex gap-2">
					<button type="submit" class="btn btn-primary btn-lg">
						<i class="fas fa-arrow-up me-2"></i>Unggah dan Validasi
					</button>
					<button type="reset" class="btn btn-secondary btn-lg">
						<i class="fas fa-redo me-2"></i>Reset
					</button>
				</div>
			</form>
		</div>
	</div>

	<!-- Batch Results -->
	<div id="batchResultsSection" style="display: none; margin-top: 2rem;">
		<div class="validation-card">
			<div class="validation-card-header">
				<i class="fas fa-list-check me-2"></i>Hasil Validasi Batch
			</div>
			<div class="validation-card-body">
				<div class="summary-stats">
					<div class="stat-card">
						<div class="stat-card-value" id="batchTotal">0</div>
						<div class="stat-card-label">Total Data</div>
					</div>
					<div class="stat-card">
						<div class="stat-card-value" id="batchAccurate">0</div>
						<div class="stat-card-label">Prediksi Akurat</div>
					</div>
					<div class="stat-card">
						<div class="stat-card-value" id="batchWrong">0</div>
						<div class="stat-card-label">Prediksi Salah</div>
					</div>
					<div class="stat-card">
						<div class="stat-card-value" id="batchAccuracy">0%</div>
						<div class="stat-card-label">Akurasi Batch</div>
					</div>
				</div>

				<div class="mt-4">
					<h5>Tabel Detail Hasil</h5>
					<div class="table-responsive">
						<table class="table table-striped table-hover" id="batchResultsTable">
							<thead class="table-light">
								<tr>
									<th>No</th>
									<th>Nama</th>
									<th>ID Pelanggan</th>
									<th>Prediksi</th>
									<th>Aktual</th>
									<th>Status</th>
									<th>Akurasi</th>
								</tr>
							</thead>
							<tbody>
								<!-- Akan diisi oleh JavaScript -->
							</tbody>
						</table>
					</div>
				</div>

				<div class="mt-3">
					<button type="button" class="btn btn-success" id="exportBatchResults">
						<i class="fas fa-download me-2"></i>Ekspor Hasil (Excel)
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ============ TAB 3: VALIDATION REPORT ============ -->
<div id="validation-report" class="tab-content">
	<div class="validation-card">
		<div class="validation-card-header">
			<i class="fas fa-bar-chart me-2"></i>Laporan Validasi Sistem
		</div>
		<div class="validation-card-body">
			<p class="mb-4">Analisis performa sistem klasifikasi berdasarkan data pengujian yang telah dijalankan.</p>

			<div class="summary-stats">
				<div class="stat-card">
					<div class="stat-card-value" id="reportTotalTests">0</div>
					<div class="stat-card-label">Total Pengujian</div>
				</div>
				<div class="stat-card">
					<div class="stat-card-value" id="reportAccuracy">0%</div>
					<div class="stat-card-label">Akurasi Keseluruhan</div>
				</div>
				<div class="stat-card">
					<div class="stat-card-value" id="reportPrecision">0%</div>
					<div class="stat-card-label">Precision</div>
				</div>
				<div class="stat-card">
					<div class="stat-card-value" id="reportRecall">0%</div>
					<div class="stat-card-label">Recall</div>
				</div>
			</div>

			<div class="detail-table mt-4">
				<h5 class="mb-3">Confusion Matrix (Data Testing)</h5>
				<table>
					<tr>
						<th colspan="2" rowspan="2" style="text-align: center;">Confusion Matrix</th>
						<th colspan="2" style="text-align: center; border: 2px solid #dfe5f2;">Aktual</th>
					</tr>
					<tr>
						<th style="border: 1px solid #dfe5f2; background: #e7f3ff;">Layak</th>
						<th style="border: 1px solid #dfe5f2; background: #ffebee;">Tidak Layak</th>
					</tr>
					<tr>
						<th rowspan="2" style="border: 2px solid #dfe5f2; text-align: center;">Prediksi</th>
						<th style="border: 1px solid #dfe5f2; background: #e7f3ff;">Layak</th>
						<td id="reportTP" style="border: 1px solid #dfe5f2; text-align: center; background: #d1e7dd;">0</td>
						<td id="reportFP" style="border: 1px solid #dfe5f2; text-align: center; background: #f8d7da;">0</td>
					</tr>
					<tr>
						<th style="border: 1px solid #dfe5f2; background: #ffebee;">Tidak Layak</th>
						<td id="reportFN" style="border: 1px solid #dfe5f2; text-align: center; background: #f8d7da;">0</td>
						<td id="reportTN" style="border: 1px solid #dfe5f2; text-align: center; background: #d1e7dd;">0</td>
					</tr>
				</table>
			</div>

			<div class="detail-table mt-4">
				<h5 class="mb-3">Metrik Performa Terperinci</h5>
				<table>
					<tr>
						<th>Metrik</th>
						<th>Rumus</th>
						<th>Nilai</th>
					</tr>
					<tr>
						<td><b>Akurasi</b></td>
						<td style="font-size: 0.875rem;">(TP + TN) / Total</td>
						<td id="metricAccuracy">-</td>
					</tr>
					<tr>
						<td><b>Precision</b></td>
						<td style="font-size: 0.875rem;">TP / (TP + FP)</td>
						<td id="metricPrecision">-</td>
					</tr>
					<tr>
						<td><b>Recall (Sensitivity)</b></td>
						<td style="font-size: 0.875rem;">TP / (TP + FN)</td>
						<td id="metricRecall">-</td>
					</tr>
					<tr>
						<td><b>Specificity</b></td>
						<td style="font-size: 0.875rem;">TN / (TN + FP)</td>
						<td id="metricSpecificity">-</td>
					</tr>
					<tr>
						<td><b>F1-Score</b></td>
						<td style="font-size: 0.875rem;">2 × (Precision × Recall) / (Precision + Recall)</td>
						<td id="metricF1">-</td>
					</tr>
				</table>
			</div>

			<div class="mt-4">
				<a href="{{ route('result.index') }}" class="btn btn-primary">
					<i class="fas fa-eye me-2"></i>Lihat Laporan Lengkap
				</a>
				<button type="button" class="btn btn-success" id="exportReport">
					<i class="fas fa-download me-2"></i>Ekspor PDF
				</button>
			</div>
		</div>
	</div>
</div>

@endsection

@section('script')
<script>
	// ========== UTILITIES ==========
	function showAlert(message, type = 'info') {
		const alertHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
		$('#alertContainer').prepend(alertHTML);
		setTimeout(() => {
			$('#alertContainer .alert').fadeOut();
		}, 5000);
	}

	function formatPercent(value) {
		return (value * 100).toFixed(2);
	}

	function getConfidenceLevel(prob) {
		if (prob >= 0.8) return '<span class="confidence-indicator confidence-high"><i class="fas fa-thumbs-up me-1"></i>Tinggi (≥80%)</span>';
		if (prob >= 0.6) return '<span class="confidence-indicator confidence-medium"><i class="fas fa-exclamation-triangle me-1"></i>Sedang (60-80%)</span>';
		return '<span class="confidence-indicator confidence-low"><i class="fas fa-exclamation-circle me-1"></i>Rendah (<60%)</span>';
	}

	// ========== TAB NAVIGATION ==========
	$('.tab-btn').on('click', function() {
		const tabName = $(this).data('tab');
		$('.tab-content').removeClass('active');
		$(`#${tabName}`).addClass('active');
		$('.tab-btn').removeClass('active');
		$(this).addClass('active');
	});

	// ========== LOAD ATTRIBUTES ==========
	function loadAttributes() {
		// Simulasi loading atribut - dalam implementasi nyata akan mengambil dari database
		const attributes = @json($atribut ?? []);
		const nilai = @json($nilai ?? []);

		if (attributes.length === 0) {
			$('#attributeContainer').html(`
                <div class="alert alert-warning col-12">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Tidak ada atribut yang tersedia. Tambahkan atribut terlebih dahulu.
                </div>
            `);
			return;
		}

		let html = '';
		attributes.forEach(attr => {
			if (attr.type === 'numeric') {
				html += `
                    <div class="form-floating">
                        <input type="number" class="form-control attribute-input" 
                            name="q[${attr.slug}]" id="attr-${attr.slug}"
                            placeholder="0" min="0" required
                            data-attr-id="${attr.id}" data-attr-type="numeric">
                        <label for="attr-${attr.slug}">${attr.name}</label>
                        <small class="form-text text-muted">${attr.desc || ''}</small>
                        <div class="invalid-feedback" id="${attr.slug}-error"></div>
                    </div>
                `;
			} else {
				const options = nilai.filter(n => n.atribut_id === attr.id);
				html += `
                    <div class="form-floating">
                        <select class="form-select attribute-input" name="q[${attr.slug}]" 
                            id="attr-${attr.slug}" required
                            data-attr-id="${attr.id}" data-attr-type="categorical">
                            <option value="">-- Pilih --</option>
                            ${options.map(opt => `<option value="${opt.id}">${opt.name}</option>`).join('')}
                        </select>
                        <label for="attr-${attr.slug}">${attr.name}</label>
                        <small class="form-text text-muted">${attr.desc || ''}</small>
                        <div class="invalid-feedback" id="${attr.slug}-error"></div>
                    </div>
                `;
			}
		});

		$('#attributeContainer').html(html);
	}

	// ========== ACTUAL VALUE TOGGLE ==========
	$('#includeActual').on('change', function() {
		$('#actualValueSection').toggle(this.checked);
		if (!this.checked) {
			$('#actualValue').val('');
		}
	});

	// ========== SINGLE TEST FORM SUBMISSION ==========
	$('#singleTestForm').on('submit', function(e) {
		e.preventDefault();

		if (!this.checkValidity()) {
			$(this).addClass('was-validated');
			return;
		}

		const formData = new FormData(this);
		const data = Object.fromEntries(formData);

		$('#resultsSection').show();
		$('#loadingSpinner').addClass('active');
		$('#resultCard, #detailCard').hide();

		// Simulasi API call
		setTimeout(() => {
			// Mengambil data dari form
			const probTrue = Math.random();
			const probFalse = 1 - probTrue;
			const predicted = probTrue > 0.5 ? 1 : 0;
			const confidence = Math.max(probTrue, probFalse);

			// Display Results
			displayResults({
				predicted: predicted,
				probTrue: probTrue,
				probFalse: probFalse,
				confidence: confidence,
				name: data.nama,
				hasActual: data.include_actual === 'on',
				actual: data.include_actual === 'on' ? parseInt(data.actual_value) : null
			});

			$('#loadingSpinner').removeClass('active');
			$('#resultCard').show();
		}, 1500);
	});

	function displayResults(result) {
		const predictedLabel = result.predicted === 1 ? 'Layak Menerima Subsidi' : 'Tidak Layak Menerima Subsidi';
		const predictedColor = result.predicted === 1 ? 'eligible' : 'not-eligible';
		const confidenceLevel = getConfidenceLevel(result.confidence);

		$('#predictedLabel').text(predictedLabel);
		$('#confidence').text(formatPercent(result.confidence) + '%');
		$('#predictionBox').removeClass('eligible not-eligible').addClass(predictedColor);
		$('#predictionTitle').text(predictedLabel);
		$('#predictionDesc').text(`Sistem memprediksi bahwa ${result.name} ${result.predicted === 1 ? 'memenuhi' : 'tidak memenuhi'} kriteria penerima subsidi listrik.`);

		$('#probTrue').text(formatPercent(result.probTrue) + '%');
		$('#probTrueBar').css('width', (result.probTrue * 100) + '%').attr('aria-valuenow', formatPercent(result.probTrue));

		$('#probFalse').text(formatPercent(result.probFalse) + '%');
		$('#probFalseBar').css('width', (result.probFalse * 100) + '%').attr('aria-valuenow', formatPercent(result.probFalse));

		$('#confidenceIndicator').html(confidenceLevel);

		if (result.hasActual) {
			const actualLabel = result.actual === 1 ? 'Layak Menerima Subsidi' : 'Tidak Layak Menerima Subsidi';
			const isCorrect = result.predicted === result.actual;

			$('#comparisonSection').show();
			$('#predictedValueBox').html(`<strong>${predictedLabel}</strong>`);
			$('#predictedConfidence').text(`Kepercayaan: ${formatPercent(result.confidence)}%`);
			$('#actualValueBox').html(`<strong>${actualLabel}</strong>`);
			$('#validationResult').html(isCorrect ?
				'<span class="text-success"><i class="fas fa-check me-1"></i>Prediksi Akurat</span>' :
				'<span class="text-danger"><i class="fas fa-times me-1"></i>Prediksi Tidak Akurat</span>'
			);
			$('#validationMatch').html(isCorrect ?
				'<span class="badge bg-success">Cocok</span>' :
				'<span class="badge bg-danger">Tidak Cocok</span>'
			);
			$('#errorType').text(isCorrect ? 'Tidak Ada' : (result.predicted === 1 ? 'False Positive' : 'False Negative'));
		} else {
			$('#comparisonSection').hide();
		}
	}

	// ========== BATCH TEST FORM ==========
	$('#batchTestForm').on('submit', function(e) {
		e.preventDefault();
		// Implementasi batch testing
		showAlert('Fitur batch testing akan segera diluncurkan', 'info');
	});

	// ========== INITIALIZE ==========
	$(document).ready(function() {
		loadAttributes();

		// Load report data
		loadValidationReport();
	});

	function loadValidationReport() {
		// Simulasi loading data laporan dari backend
		// Dalam implementasi nyata akan membuat AJAX call ke server

		// Data dummy untuk demonstrasi
		const reportData = {
			totalTests: 0,
			accuracy: 0,
			precision: 0,
			recall: 0,
			tp: 0,
			fp: 0,
			fn: 0,
			tn: 0
		};

		$('#reportTotalTests').text(reportData.totalTests);
		$('#reportAccuracy').text((reportData.accuracy * 100).toFixed(2) + '%');
		$('#reportPrecision').text((reportData.precision * 100).toFixed(2) + '%');
		$('#reportRecall').text((reportData.recall * 100).toFixed(2) + '%');

		$('#reportTP').text(reportData.tp);
		$('#reportFP').text(reportData.fp);
		$('#reportFN').text(reportData.fn);
		$('#reportTN').text(reportData.tn);

		// Calculate metrics
		const total = reportData.tp + reportData.fp + reportData.fn + reportData.tn;
		const accuracy = total > 0 ? ((reportData.tp + reportData.tn) / total * 100).toFixed(2) : 0;
		const precision = (reportData.tp + reportData.fp) > 0 ? (reportData.tp / (reportData.tp + reportData.fp) * 100).toFixed(2) : 0;
		const recall = (reportData.tp + reportData.fn) > 0 ? (reportData.tp / (reportData.tp + reportData.fn) * 100).toFixed(2) : 0;
		const specificity = (reportData.tn + reportData.fp) > 0 ? (reportData.tn / (reportData.tn + reportData.fp) * 100).toFixed(2) : 0;
		const f1 = (precision + recall) > 0 ? (2 * (precision * recall) / (precision + recall)).toFixed(2) : 0;

		$('#metricAccuracy').text(accuracy + '%');
		$('#metricPrecision').text(precision + '%');
		$('#metricRecall').text(recall + '%');
		$('#metricSpecificity').text(specificity + '%');
		$('#metricF1').text(f1);
	}
</script>
@endsection
