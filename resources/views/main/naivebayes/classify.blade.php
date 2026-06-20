@extends('layout')
@section('title','Hasil Klasifikasi')
@section('content')
<style>
	.nb-detail-head {
		background: #f5f7fb;
		border: 1px solid #dfe5f2;
		border-radius: .5rem;
		padding: .75rem 1rem;
		margin-bottom: 1rem;
	}
	.nb-detail-section {
		border: 1px solid #dfe5f2;
		border-radius: .5rem;
		margin-bottom: 1rem;
		overflow: hidden;
		background: #fff;
	}
	.nb-detail-section-title {
		background: #f3f5fa;
		border-bottom: 1px solid #dfe5f2;
		padding: .65rem 1rem;
		font-weight: 700;
	}
	.nb-detail-section-body {
		padding: .75rem;
	}
	.nb-predict-box {
		border: 1px solid #cfe2ff;
		background: #eef6ff;
		border-radius: .5rem;
		padding: 1rem;
		font-size: 1.2rem;
		font-weight: 700;
		color: #13324d;
		text-align: center;
		min-height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
	}
	.nb-formula-img {
		max-width: 100%;
		height: 40px;
		object-fit: contain;
	}
</style>

<div class="modal fade" tabindex="-1" id="modalCalcClass" aria-labelledby="modalCalcClassLabel" role="dialog"
	data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modalCalcClassLabel" class="modal-title">Hitung Klasifikasi</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p>Pilih tipe data yang akan dihitung hasil klasifikasinya.</p>
				<form id="formCalcClass">@csrf
					<div class="position-relative">
						<select class="form-select" name="tipe" id="calc-select" required>
							<option value="train">Data Training (Data Latih)</option>
							<option value="test" selected>Data Testing (Data Uji)</option>
						</select>
						<div class="invalid-feedback" id="calc-error"></div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-x"></i> Batal</button>
				<button type="submit" class="btn btn-primary" form="formCalcClass"><i class="fas fa-calculator"></i> Hitung</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" id="modalResetClass" aria-labelledby="modalResetClassLabel" role="dialog"
	data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 id="modalResetClassLabel" class="modal-title text-white">Reset Klasifikasi?</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<p>Anda akan mereset hasil klasifikasi. Pilih tipe data yang akan direset hasil klasifikasinya.</p>
				<form id="formResetClass">@csrf
					<div class="position-relative">
						<select class="form-select" name="tipe" id="reset-select" required>
							<option value="" selected>Pilih</option>
							<option value="train">Data Training (Data Latih)</option>
							<option value="test">Data Testing (Data Uji)</option>
							<option value="all">Semua</option>
						</select>
						<div class="invalid-feedback" id="reset-error"></div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-x"></i> Batal</button>
				<button type="submit" class="btn btn-danger" form="formResetClass"><i class="fas fa-check"></i> Reset</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" id="modalDetailClass" aria-labelledby="modalDetailClassLabel" role="dialog"
	data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modalDetailClassLabel" class="modal-title"><i class="fas fa-calculator me-2"></i>Detail Perhitungan Naive Bayes</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="detail-meta" class="nb-detail-head"></div>
				<div class="alert alert-info mb-3">
					<div class="d-flex flex-wrap align-items-center gap-3">
						<div class="d-flex align-items-center gap-2">
							<b>Rumus Posterior</b>
							<img class="nb-formula-img" src="https://latex.codecogs.com/png.image?%5Cdpi%7B130%7D%20P%28C%5Cmid%20X%29%3D%5Cfrac%7BP%28C%29%5Cprod_%7Bi%3D1%7D%5EnP%28x_i%5Cmid%20C%29%7D%7BP%28X%29%7D"
								alt="Rumus Posterior">
						</div>
						<div class="d-flex align-items-center gap-2">
							<b>Rumus Gaussian</b>
							<img class="nb-formula-img" src="https://latex.codecogs.com/png.image?%5Cdpi%7B130%7D%20P%28x_i%5Cmid%20C%29%3D%5Cfrac%7B1%7D%7B%5Csigma_i%5Csqrt%7B2%5Cpi%7D%7D%5Cexp%5Cleft%28-%5Cfrac%7B1%7D%7B2%7D%5Cleft%28%5Cfrac%7Bx_i-%5Cmu_i%7D%7B%5Csigma_i%7D%5Cright%29%5E2%5Cright%29"
								alt="Rumus Gaussian">
						</div>
						<div class="d-flex align-items-center gap-2">
							<b>Rumus Likelihood</b>
							<img class="nb-formula-img" src="https://latex.codecogs.com/png.image?%5Cdpi%7B130%7D%20P%28X%5Cmid%20Y%29%3D%5Cprod_%7Bi%3D1%7D%5En%20P%28x_i%5Cmid%20Y%29"
								alt="Rumus Likelihood">
						</div>
					</div>
				</div>
				<div class="nb-detail-section">
					<div class="nb-detail-section-title">1. Prior Probability</div>
					<div class="nb-detail-section-body">
						<div class="table-responsive">
							<table class="table table-bordered mb-0">
								<thead>
									<tr>
										<th>Kelas</th>
										<th>Jumlah</th>
										<th>Prior</th>
									</tr>
								</thead>
								<tbody id="detail-prior-body"></tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="nb-detail-section">
					<div class="nb-detail-section-title">2. Likelihood Tiap Atribut</div>
					<div class="nb-detail-section-body">
						<div class="table-responsive">
							<table class="table table-bordered mb-0">
								<thead>
									<tr>
										<th>Atribut</th>
										<th>Tipe</th>
										<th>Input</th>
										<th>P(Layak)</th>
										<th>P(Tidak Layak)</th>
									</tr>
								</thead>
								<tbody id="detail-steps"></tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="row g-3">
					<div class="col-lg-8">
						<div class="nb-detail-section mb-0">
							<div class="nb-detail-section-title">3. Hasil Perhitungan Akhir</div>
							<div class="nb-detail-section-body">
								<div class="table-responsive">
									<table class="table table-bordered mb-0">
										<thead>
											<tr>
												<th>Kriteria</th>
												<th>Skor</th>
											</tr>
										</thead>
										<tbody id="detail-final-body"></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="nb-predict-box" id="detail-prediction">Prediksi: -</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="btn-group mb-2" role="button">
			<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCalcClass"><i class="fas fa-calculator"></i> Hitung</button>
			<button type="button" class="btn btn-danger" id="resetBtn" data-bs-toggle="modal" data-bs-target="#modalResetClass" disabled><i class="fa-solid fa-arrow-rotate-right"></i> Reset</button>
			<button class="btn btn-success dropdown-toggle" id="expBtn" type="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
				<i class="fas fa-download"></i> Ekspor <i class="fa-solid fa-caret-down"></i>
			</button>
			<ul class="dropdown-menu">
				<li><a class="dropdown-item" href="{{route('class.export','test')}}">Data Testing</a></li>
				<li><a class="dropdown-item" href="{{route('class.export','train')}}">Data Training</a></li>
				<li><a class="dropdown-item" href="{{route('class.export','all')}}">Semua Data</a></li>
			</ul>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered nowrap" id="table-classify" width="100%">
				<thead>
					<tr>
						<th>#</th>
						<th>ID Pelanggan</th>
						<th>Nama</th>
						<th>Tipe Data</th>
						<th>{{$hasil[true]}}</th>
						<th>{{$hasil[false]}}</th>
						<th>Kelas Prediksi</th>
						<th>Kelas Asli (Opsional)</th>
						<th>Aksi</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
@endsection

@section('js')
<script type="text/javascript">
	let dt_classify = $("#table-classify"), errmsg="";
	$(document).ready(function () {
		try {
			dt_classify = dt_classify.DataTable({
				stateSave: true,
				lengthChange: false,
				serverSide: true,
				processing: true,
				responsive: false,
				scrollX: true,
				searching: false,
				ajax: "{{ route('class.datatable') }}",
				columns: [
					{ data: "id" },
					{ data: "id_pelanggan" },
					{ data: "name" },
					{ data: "type" },
					{ data: "true" },
					{ data: "false" },
					{ data: "predicted" },
					{ data: "real" },
					{ data: "id" }
				],
				columnDefs: [{
					targets: 0,
					render: function (data, type, full, meta) {
						return meta.settings._iDisplayStart + meta.row + 1;
					}
				}, {
					orderable: false,
					className: "text-center",
					targets: -1,
					render: function (data) {
						return `<button class="btn btn-sm btn-primary detail-record" data-id="${data}" data-bs-toggle="modal" data-bs-target="#modalDetailClass"><i class="fas fa-circle-info"></i> Lihat Detail</button>`;
					}
				}],
				language: { url: "https://cdn.datatables.net/plug-ins/2.0.0/i18n/id.json" },
				drawCallback: function(){
					if(this.api().page.info().recordsTotal===0) $('#expBtn, #resetBtn').prop('disabled',true);
					else $('#expBtn, #resetBtn').prop('disabled',false);
				}
			}).on("dt-error", function (e, settings, techNote, message) {
				errorDT(message);
			});
		} catch (dterr) {
			initError(dterr.message);
		}
	});

	$(document).on("click", ".detail-record", function () {
		let classId = $(this).data("id");
		$("#detail-meta").html("Memuat...");
		$("#detail-prior-body").empty();
		$("#detail-steps").empty();
		$("#detail-final-body").empty();
		$("#detail-prediction").text("Prediksi: -");
		$("#modalDetailClass").LoadingOverlay('show');

		$.get(`{{ url('class/detail') }}/${classId}`, function (res) {
			const meta = res.meta, detail = res.detail;
			const prior = res.prior_count ?? { total: 0, true: 0, false: 0 };

			const actual = meta.real === "-" ? "-" : meta.real;
			$("#detail-meta").html(
				`<b>${meta.nama}</b> (${meta.id_pelanggan}, ${meta.daya_terpasang} VA) - ${meta.type}<br>` +
				`Prediksi: <b>${meta.predicted}</b> | Aktual: <b>${actual}</b>`
			);

			$("#detail-prior-body").html(
				`<tr><td>Layak</td><td>${prior.true}</td><td>${detail.prior.true}</td></tr>` +
				`<tr><td>Tidak Layak</td><td>${prior.false}</td><td>${detail.prior.false}</td></tr>`
			);

			let rows = "";
			detail.steps.forEach(step => {
				rows += `<tr>
					<td>${step.atribut}</td>
					<td>${step.type}</td>
					<td>${step.input ?? "-"}</td>
					<td>${step.prob_true}</td>
					<td>${step.prob_false}</td>
				</tr>`;
			});
			$("#detail-steps").html(rows);

			$("#detail-final-body").html(
				`<tr><td>Likelihood Layak</td><td>${detail.likelihood.true}</td></tr>` +
				`<tr><td>Likelihood Tidak Layak</td><td>${detail.likelihood.false}</td></tr>` +
				`<tr><td>Skor Layak</td><td>${detail.posterior.true}</td></tr>` +
				`<tr><td>Skor Tidak Layak</td><td>${detail.posterior.false}</td></tr>`
			);
			$("#detail-prediction").text(`Prediksi: ${meta.predicted.toUpperCase()}`);
		}).fail(function (xhr, st) {
			let em = xhr.responseJSON?.message ?? `Kesalahan HTTP ${xhr.status} ${xhr.statusText}`;
			console.warn(xhr.responseJSON?.message ?? st);
			$("#detail-meta").html(`<span class="text-danger">${em}</span>`);
		}).always(function () {
			$("#modalDetailClass").LoadingOverlay('hide');
		});
	});

	$("#formResetClass").on("submit",function(e){
		e.preventDefault();
		$.ajax({
			type: "DELETE",
			data: $("#formResetClass").serialize(),
			dataType: 'JSON',
			url: "{{route('class.reset')}}",
			beforeSend: function(){
				$("#modalResetClass").LoadingOverlay('show');
				resetvalidation();
			}, complete: function(){
				$("#modalResetClass").LoadingOverlay('hide');
			}, success: function () {
				if ($.fn.DataTable.isDataTable("#table-classify")) dt_classify.draw();
				$("#modalResetClass").modal('hide');
				iziToast.success({title: "Berhasil direset",displayMode: 2});
			}, error: function (xhr, st) {
				if (xhr.status === 422 || xhr.status === 400) errmsg = xhr.responseJSON.message;
				else {
					console.warn(xhr.responseJSON.message ?? st);
					errmsg = `Kesalahan HTTP ${xhr.status} ${xhr.statusText}`;
				}
				$('#reset-select').addClass('is-invalid');
				$("#reset-error").text(xhr.responseJSON.message);
				$("#modalResetClass").modal("handleUpdate");
				iziToast.error({title: "Gagal reset",message: errmsg,displayMode: 2});
			}
		});
	});

	$("#formCalcClass").on("submit",function(e){
		e.preventDefault();
		$.ajax({
			url: "{{ route('class.create') }}",
			type: "POST",
			data: $("#formCalcClass").serialize(),
			dataType: 'JSON',
			beforeSend: function(){
				resetvalidation();
				$("#modalCalcClass").LoadingOverlay('show');
			}, complete: function(){
				$("#modalCalcClass").LoadingOverlay('hide');
			}, success: function () {
				if ($.fn.DataTable.isDataTable("#table-classify")) dt_classify.draw();
				$("#modalCalcClass").modal('hide');
				iziToast.success({title: "Berhasil dihitung",displayMode: 2});
			}, error: function (xhr, st) {
				if (xhr.status === 422 || xhr.status === 400) errmsg = xhr.responseJSON.message;
				else {
					console.warn(xhr.responseJSON.message ?? st);
					errmsg = `Kesalahan HTTP ${xhr.status} ${xhr.statusText}`;
				}
				$('#calc-select').addClass('is-invalid');
				$("#calc-error").text(xhr.responseJSON.message);
				$("#modalCalcClass").modal("handleUpdate");
				iziToast.error({title: "Gagal hitung",message: errmsg,displayMode: 2});
			}
		});
	});
</script>
@endsection
