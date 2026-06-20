@extends('layout')
@section('title', 'Laporan Klasifikasi')
@section('content')
<style>
	.report-table-wrap {
		border: 1px solid #dfe5f2;
		border-radius: .5rem;
		overflow: hidden;
	}
	#table-report-classification {
		margin-bottom: 0;
	}
	#table-report-classification th,
	#table-report-classification td {
		vertical-align: middle;
		font-size: .875rem;
	}
	#table-report-classification th.wrap-col {
		white-space: normal !important;
		line-height: 1.2;
	}
	#table-report-classification thead th {
		position: sticky;
		top: 0;
		z-index: 2;
		background: #f4f6fb;
	}
	#table-report-classification tbody tr:nth-child(even) {
		background: #fbfcff;
	}
	#table-report-classification td.numeric-col {
		text-align: right;
		font-family: Consolas, "Courier New", monospace;
	}
	#table-report-classification td.center-col {
		text-align: center;
	}
</style>
<div class="card">
	<div class="card-header d-flex justify-content-between align-items-center">
		<b>Laporan Detail Klasifikasi Naive Bayes</b>
		<small class="text-muted">Dicetak: {{ now()->format('d-m-Y H:i') }}</small>
	</div>
	<div class="card-body">
		<div class="btn-group mb-2" role="group">
			<div class="btn-group" role="group">
				<button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown"
					aria-expanded="false">
					<i class="fas fa-download"></i> Ekspor
					<i class="fa-solid fa-caret-down"></i>
				</button>
				<ul class="dropdown-menu">
					<li>
						<a class="dropdown-item" href="{{ route('result.report.export.excel') }}">
							<i class="fas fa-file-excel"></i> Excel
						</a>
					</li>
					<li>
						<a class="dropdown-item" href="{{ route('result.report.export.csv') }}">
							<i class="fas fa-file-csv"></i> CSV
						</a>
					</li>
					<li>
						<a class="dropdown-item" href="{{ route('result.report.export.pdf') }}" target="_blank"
							rel="noopener">
							<i class="fas fa-file-pdf"></i> PDF
						</a>
					</li>
				</ul>
			</div>
		</div>
		<div class="table-responsive report-table-wrap">
			<table class="table table-bordered table-hover nowrap" id="table-report-classification" style="width: 100%">
				<thead>
					<tr>
						<th>#</th>
						<th class="text-center">Tipe</th>
						<th class="text-center">ID Pelanggan</th>
						<th class="text-center">Nama</th>
						@foreach($atributs as $atribut)
						@php
							$header = $atribut->name;
							$wrapClass = '';
							if ($atribut->slug === 'kepemilikan_rumah') {
								$header = 'Kepemilikan<br>Rumah';
								$wrapClass = 'wrap-col';
							} elseif (
								$atribut->slug === 'tanggungan' ||
								str_contains(strtolower($atribut->name), 'tanggungan')
							) {
								$header = 'Jumlah<br>Tanggungan';
								$wrapClass = 'wrap-col';
							} elseif (
								str_contains(strtolower($atribut->name), 'rerata') &&
								str_contains(strtolower($atribut->name), 'listrik')
							) {
								$header = 'Rerata Pemakaian<br>Listrik (KWH)';
								$wrapClass = 'wrap-col';
							}
						@endphp
						<th class="{{$wrapClass}} text-center" data-bs-toggle="tooltip" title="{{$atribut->desc ?? ''}}">
							{!! $header !!}
						</th>
						@endforeach
						<th class="wrap-col text-center">Probabilitas<br>Layak</th>
						<th class="wrap-col text-center">Probabilitas<br>Tidak Layak</th>
						<th class="text-center">Kesimpulan</th>
					</tr>
				</thead>
				<tbody>
					@forelse($rows as $row)
					<tr>
						<td class="center-col">{{ $loop->iteration }}</td>
						<td class="center-col">{{ $row['type'] }}</td>
						<td>{{ $row['id_pelanggan'] }}</td>
						<td>{{ $row['name'] }}</td>
						@foreach($atributs as $atribut)
						<td>{{ $row['attributes'][$atribut->slug] ?? '-' }}</td>
						@endforeach
						<td class="numeric-col">{{ number_format((float) $row['prob_true'], 12, '.', '') }}</td>
						<td class="numeric-col">{{ number_format((float) $row['prob_false'], 12, '.', '') }}</td>
						<td class="center-col">{{ $stat[$row['conclusion']] ?? '-' }}</td>
					</tr>
					@empty
					<tr>
						<td colspan="{{ 8 + $atributs->count() }}" class="text-center">
							Belum ada data klasifikasi.
						</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection
@section('js')
<script type="text/javascript">
	$(document).ready(function () {
		const reportTable = $("#table-report-classification").DataTable({
			stateSave: true,
			lengthChange: false,
			responsive: false,
			scrollX: true,
			scrollCollapse: true,
			autoWidth: false,
			order: [],
			language: {
				url: "https://cdn.datatables.net/plug-ins/2.0.0/i18n/id.json"
			}
		});
		setTimeout(function () {
			reportTable.columns.adjust();
		}, 0);
		reportTable.on("draw", function () {
			reportTable.columns.adjust();
		});
		$(window).on("resize", function () {
			reportTable.columns.adjust();
		});
	});
</script>
@endsection
