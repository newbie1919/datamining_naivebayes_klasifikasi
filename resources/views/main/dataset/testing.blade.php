@extends('layout')
@section('title', 'Data Testing')
@section('content')
<div class="modal fade" tabindex="-1" id="modalAddTesting" aria-labelledby="modalAddTestingLabel" role="dialog"
	aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modalAddTestingLabel" class="modal-title">Tambah Data Testing</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addNewTestingForm">@csrf
					<input type="hidden" name="id" id="test_id">
					<div class="form-floating mb-3">
						<input type="text" class="form-control" id="testName" name="nama" placeholder="Nama" required />
						<label for="testName">Nama</label>
						<div class="invalid-feedback" id="name-error"></div>
					</div>
					<div class="form-floating mb-3">
						<input type="text" class="form-control" id="testCustomerId" name="id_pelanggan"
							placeholder="ID Pelanggan" required />
						<label for="testCustomerId">ID Pelanggan</label>
						<div class="invalid-feedback" id="id-pelanggan-error"></div>
					</div>
					@foreach ($atribut as $attr)
					<div class="form-floating mb-3" data-bs-toggle="tooltip" title="{{$attr->desc}}">
						@if ($attr->type === 'numeric')
						<input type="number" class="form-control" min="0" name="q[{{$attr->slug}}]"
							id="test-{{$attr->slug}}" placeholder="123" required>
						@else
						<select name="q[{{$attr->slug}}]" class="form-select" id="test-{{$attr->slug}}" required>
							<option value="">Pilih</option>
							@foreach ($nilai->where('atribut_id', $attr->id) as $sub)
							<option value="{{$sub->id}}">{{$sub->name}}</option>
							@endforeach
						</select>
						@endif
						<label for="test-{{$attr->slug}}">{{$attr->name}}</label>
						<div class="invalid-feedback" id="{{$attr->slug}}-error"></div>
					</div>
					@endforeach
				</form>
			</div>
			<div class="modal-footer">
				<button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">
					<i class="fas fa-x"></i> Batal
				</button>
				<button type="button" class="btn btn-success" data-bs-toggle="modal"
					data-bs-target="#modalImportTesting">
					<i class="fas fa-upload"></i> Upload File
				</button>
				<button type="submit" class="btn btn-primary" form="addNewTestingForm">
					<i class="fas fa-floppy-disk"></i> Simpan
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" tabindex="-1" id="modalImportTesting" aria-labelledby="modalImportTestingLabel"
	data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="modalImportTestingLabel" class="modal-title">
					Upload Data Testing
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="alert alert-info" role="alert">
					<i class="fas fa-info-circle"></i>
					<a href="{{route('template-data')}}" class="alert-link">Klik disini</a>
					untuk mendownload template Dataset
				</div>
				<form id="importTestingData" enctype="multipart/form-data">@csrf
					<input type="file" class="form-control" id="testData" name="data" aria-describedby="importFormats"
						data-bs-toggle="tooltip" title="Format: xls, xlsx, csv, dan tsv"
						accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.oasis.opendocument.spreadsheet,text/csv,.tsv"
						required>
					<div class="invalid-feedback" id="data-error"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">
					<i class="fas fa-x"></i> Batal
				</button>
				<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAddTesting">
					<i class="fas fa-pen"></i> Input Manual
				</button>
				<button type="submit" class="btn btn-primary" form="importTestingData">
					<i class="fas fa-upload"></i> Upload
				</button>
			</div>
		</div>
	</div>
</div>
{{-- <div class="alert alert-info" role="alert">
	<i class="fas fa-circle-info"></i>
	Mohon untuk tidak menginput atau mengupload data yang sama dengan data training
</div> --}}
<div class="row">
	<div class="col-md-4 mb-3">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-start justify-content-between">
					<div class="content-left">
						<span>Jumlah</span>
						<div class="d-flex align-items-end mt-2">
							<h3 class="mb-0 me-2"><span id="total-counter">-</span></h3>
						</div>
					</div>
					<span class="badge bg-primary rounded p-2">
						<i class="fas fa-list-ul"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 mb-3">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-start justify-content-between" data-bs-toggle="tooltip"
					title="Jumlah Data Testing dengan ID Pelanggan duplikat">
					<div class="content-left">
						<span>Duplikat</span>
						<div class="d-flex align-items-end mt-2">
							<h3 class="mb-0 me-2"><span id="total-duplicate">-</span></h3>
						</div>
					</div>
					<span class="badge bg-warning rounded p-2">
						<i class="fas fa-copy"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 mb-3">
		<div class="card">
			<div class="card-body">
				<div class="d-flex align-items-start justify-content-between" data-bs-toggle="tooltip"
					title="Jumlah kolom kosong">
					<div class="content-left">
						<span>Kosong</span>
						<div class="d-flex align-items-end mt-2">
							<h3 class="mb-0 me-2"><span id="total-missing">-</span></h3>
						</div>
					</div>
					<span class="badge bg-danger rounded p-2">
						<i class="fas fa-exclamation-circle"></i>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="card">
	<div class="card-body">
		<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
			<div class="btn-group" role="group">
				<div class="btn-group" role="group">
					<button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
						aria-expanded="false">
						<i class="fas fa-plus"></i> Tambah Data
						<i class="fa-solid fa-caret-down"></i>
					</button>
					<ul class="dropdown-menu">
						<li>
							<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalAddTesting">
								<i class="fas fa-pen"></i> Input Manual
							</a>
						</li>
						<li>
							<a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalImportTesting">
								<i class="fas fa-upload"></i> Upload File
							</a>
						</li>
					</ul>
				</div>
				<button type="button" class="btn btn-danger" id="delete-all" disabled>
					<i class="fas fa-trash"></i> Hapus Data
				</button>
				<a href="{{route('testing.export')}}" class="btn btn-success disabled" id="dlBtn">
					<i class="fas fa-download"></i> Ekspor
				</a>
			</div>
			<div class="input-group" style="max-width: 300px;">
				<span class="input-group-text"><i class="fas fa-search"></i></span>
				<input type="text" class="form-control" id="testing-search" placeholder="Cari data...">
				<button class="btn btn-outline-secondary" type="button" id="testing-clear" aria-label="Clear search">
					<i class="fas fa-xmark"></i>
				</button>
			</div>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered nowrap" id="table-testing" style="width: 100%">
				<thead>
					<tr>
						<th>#</th>
						<th class="text-center">ID Pelanggan</th>
						<th class="text-center">Nama</th>
						@foreach ($atribut as $attr)
						<th class="text-center" data-bs-toggle="tooltip" title="{{$attr->desc}}">
							{{$attr->name}}
						</th>
						@endforeach
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
	let dt_testing = $("#table-testing"), errmsg="";
	const modalForm = $("#modalAddTesting"),modalImport=$('#modalImportTesting');
	$(document).ready(function () {
		try {
			dt_testing = dt_testing.DataTable({
				stateSave: true,
				lengthChange: false,
				serverSide: true,
				processing: true,
				responsive: false,
				scrollX: true,
				searching: true,
				dom: "rtip",
				ajax: "{{ route('testing.create') }}",
				columns: [
					{ data: "id" },
					{ data: "id_pelanggan" },
					{ data: "nama" },
					@foreach ($atribut as $attr)
					{ data: "{{$attr->slug}}" },
					@endforeach
					{ data: "id" }
				], columnDefs: [{
					targets: 0,
					render: function (data, type, full, meta) {
						return meta.settings._iDisplayStart + meta.row + 1;
					}
				},
				@foreach ($atribut as $attr)
				{
					targets: 3 + {{$loop->index}},
					render: function(data) {
						return data??"?";
					}
				},
				@endforeach
				{ //Aksi
					orderable: false,
					responsivePriority: 1,
					className: "text-center",
					targets: -1,
					render: function (data, type, full) {
						return ('<div class="btn-group btn-group-sm" role="group">' +
							`<button class="btn btn-primary edit-record" data-id="${data}" data-bs-toggle="modal" data-bs-target="#modalAddTesting">` +
							'<i class="fas fa-pen-to-square"></i>' +
							'</button>' +
							`<button class="btn btn-danger delete-record" data-id="${data}" data-name="${full['nama']}">` +
							'<i class="fas fa-trash"></i>' +
							'</button>' +
							"</div>");
					}
				}], language: {
					url: "https://cdn.datatables.net/plug-ins/2.0.0/i18n/id.json"
				}, drawCallback: function(){
					let total=this.api().page.info().recordsTotal;
					if(total===0){
						$('#dlBtn').addClass('disabled');
						$("#delete-all").prop('disabled',true);
					}	else {
						$('#dlBtn').removeClass('disabled');
						$("#delete-all").prop('disabled',false);
					}
					$("#total-counter").text(total);
					$.get("{{ route('testing.count') }}", function (data) {
						$('#total-duplicate').text(data.duplicate);
						$("#total-missing").text(data.empty);
					}).fail(function (xhr, st) {
						console.warn(xhr.responseJSON.message ?? st);
						iziToast.error({
							title: "Gagal memuat jumlah",
							message: `Kesalahan HTTP ${xhr.status} ${xhr.statusText}`,
							displayMode: 2
						});
					});
				}
			}).on("dt-error", function (e, settings, techNote, message) {
				errorDT(message);
			});
		} catch (dterr) {
			initError(dterr.message);
		}
		$("#testing-search").on("input", function () {
			dt_testing.search(this.value).draw();
		});
		$("#testing-clear").on("click", function () {
			$("#testing-search").val("");
			dt_testing.search("").draw();
		});
	}).on("click", "#delete-all", function () {
		iziToast.question({
			timeout: 20000,
			overlay: true,
			title: "Hapus semua Data Testing?",
			message: 'Anda akan menghapus semua Data Testing yang akan mereset hasil klasifikasi terkait.',
			position: 'center',
			buttons: [
				['<button><b>Hapus</b></button>', function (instance, toast) {
					instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
					$.LoadingOverlay('show');
					$.ajax({
						type: "DELETE",
						headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
						url: "{{route('testing.clear')}}",
						complete:function(){
							$.LoadingOverlay('hide');
						}, success: function () {
							if ($.fn.DataTable.isDataTable("#table-testing")) dt_testing.draw();
							iziToast.success({
								title: "Semua data berhasil dihapus",displayMode: 2
							});
						}, error: function (xhr, st) {
							console.warn(xhr.responseJSON.message ?? st);
							iziToast.error({
								title: "Gagal hapus",
								message: `Kesalahan HTTP ${xhr.status} ${xhr.statusText}`,
								displayMode: 2
							});
						}
					});
				}, true],
				['<button>Batal</button>', function (instance, toast) {
					instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
				}]
			]
		});
	}).on("click", ".delete-record", function () {
		let test_id = $(this).data("id"), test_name = $(this).data("name");
		iziToast.question({
			timeout: 20000,
			overlay: true,
			title: "Hapus Data Testing?",
			message: `Anda akan menghapus Data Testing ${test_name}.`,
			position: 'center',
			buttons: [
				['<button><b>Hapus</b></button>', function (instance, toast) {
					instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
					$.LoadingOverlay('show');
					$.ajax({
						type: "DELETE",
						headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
						url: 'testing/' + test_id,
						complete: function () {
							$.LoadingOverlay('hide');
						}, success: function () {
							dt_testing.draw();
							iziToast.success({title: "Berhasil dihapus",displayMode: 2});
						}, error: function (xhr, st) {
							if (xhr.status === 404) {
								dt_testing.draw();
								errmsg = `Data Testing ${test_name} tidak ditemukan`;
							} else {
								console.warn(xhr.responseJSON.message ?? st);
								errmsg = `Kesalahan HTTP ${xhr.status} ${xhr.statusText}`;
							}
							iziToast.error({title: "Gagal hapus",message: errmsg,displayMode: 2});
						}
					});
				}, true],
				['<button>Batal</button>', function (instance, toast) {
					instance.hide({ transitionOut: 'fadeOut' }, toast, 'button');
				}]
			]
		});
	}).on("click", ".edit-record", function () {
		let test_id = $(this).data("id");
		$("#modalAddTestingLabel").text("Edit Data Testing");
		$(modalForm).LoadingOverlay('show');
		$.get(`testing/${test_id}/edit`, function (data) {
			$("#test_id").val(data.id);
			$("#testName").val(data.nama);
			$("#testCustomerId").val(data.id_pelanggan);
			@foreach($atribut as $attr)
			$("#test-{{$attr->slug}}").val(data.{{$attr->slug}});
			@endforeach
		}).fail(function (xhr, st) {
			if (xhr.status === 404) {
				dt_testing.draw();
				modalForm.modal('hide');
				errmsg = "Data Testing tidak ditemukan";
			} else {
				console.warn(xhr.responseJSON.message ?? st);
				errmsg = `Kesalahan HTTP ${xhr.status} ${xhr.statusText}`;
			}
			iziToast.error({title: "Gagal memuat data",message: errmsg,displayMode: 2});
		}).always(function () {
			$(modalForm).LoadingOverlay('hide');
		});
	});
	$('#importTestingData').on("submit",function(e) {//form Upload Data
		e.preventDefault();
		$.ajax({
			type: "POST",
			url: "{{route('testing.import')}}",
			dataType: 'JSON',
			data: new FormData(this),
			contentType: false,
			cache: false,
			processData: false,
			beforeSend: function () {
				modalImport.LoadingOverlay('show');
				resetvalidation();
			}, complete: function () {
				modalImport.LoadingOverlay('hide');
			}, success: function (status) {
				if ($.fn.DataTable.isDataTable("#table-testing")) dt_testing.draw();
				modalImport.modal("hide");
				iziToast.success({title: "Berhasil diupload",displayMode: 2});
			}, error: function (xhr, st) {
				$("#testData").addClass("is-invalid");
				$("#data-error").text(xhr.responseJSON.message);
				if (xhr.status === 422) errmsg = xhr.responseJSON.message;
				else {
					console.warn(xhr.responseJSON.message ?? st);
					errmsg = `Kesalahan HTTP ${xhr.status} ${xhr.statusText}`;
				}
				$('#modalImportTesting').modal("handleUpdate");
				iziToast.error({title: "Gagal upload",message: errmsg,displayMode: 2});
			}
		});
	});
	$("#addNewTestingForm").on("submit",function (ev) {//form Input Manual
		ev.preventDefault();
		$.ajax({
			data: $("#addNewTestingForm").serialize(),
			url: "{{ route('testing.store') }}",
			type: "POST",
			beforeSend: function () {
				$(modalForm).LoadingOverlay('show');
				resetvalidation();
			}, complete: function () {
				$(modalForm).LoadingOverlay('hide');
			}, success: function (status) {
				if ($.fn.DataTable.isDataTable("#table-testing")) dt_testing.draw();
				modalForm.modal("hide");
				iziToast.success({title: status.message,displayMode: 2});
			}, error: function (xhr, st) {
				if (xhr.status === 422) {
					if (typeof xhr.responseJSON.errors.nama !== "undefined") {
						$("#testName").addClass("is-invalid");
						$("#name-error").text(xhr.responseJSON.errors.nama);
					}
					if (typeof xhr.responseJSON.errors.id_pelanggan !== "undefined") {
						$("#testCustomerId").addClass("is-invalid");
						$("#id-pelanggan-error").text(xhr.responseJSON.errors.id_pelanggan);
					}
					@foreach($atribut as $attr)
					if (typeof xhr.responseJSON.errors.{{$attr->slug}} !== "undefined") {
						$("#test-{{$attr->slug}}").addClass("is-invalid");
						$("#{{$attr->slug}}-error").text(xhr.responseJSON.errors.{{$attr->slug}});
					}
					@endforeach
					errmsg = xhr.responseJSON.message;
					modalForm.modal("handleUpdate");
				}	else {
					console.warn(xhr.responseJSON.message ?? st);
					errmsg = `Kesalahan HTTP ${xhr.status} ${xhr.statusText}`;
				}
				iziToast.error({title: "Gagal",message: errmsg,displayMode: 2});
			}
		});
	});
	modalForm.on("hidden.bs.modal", function () {
		resetForm("#addNewTestingForm");
		$("#modalAddTestingLabel").text("Tambah Data Testing");
	});
	modalImport.on('hidden.bs.modal',function(){
		resetForm("#importTestingData");
	});
</script>
@endsection
