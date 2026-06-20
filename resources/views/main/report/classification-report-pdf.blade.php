<!doctype html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Laporan Klasifikasi</title>
	<style>
		body { font-family: Arial, sans-serif; margin: 20px; color: #111; }
		h1 { margin: 0 0 10px 0; }
		p { margin: 0 0 10px 0; }
		table { border-collapse: collapse; width: 100%; margin-top: 10px; margin-bottom: 16px; }
		th, td { border: 1px solid #333; padding: 6px 8px; font-size: 11px; }
		th { background: #efefef; text-align: left; }
		.text-center { text-align: center; }
		@media print { .no-print { display: none; } }
	</style>
</head>
<body>
	<div class="no-print" style="margin-bottom: 16px;">
		<button onclick="window.print()">Cetak / Simpan PDF</button>
	</div>
	<h1>Laporan Detail Klasifikasi Naive Bayes</h1>
	<p>Tanggal cetak: {{ now()->format('d-m-Y H:i') }}</p>

	<table>
		<thead>
			<tr>
				<th>#</th>
				<th>Tipe</th>
				<th>Nama</th>
				<th>ID Pelanggan</th>
				@foreach($atributs as $atribut)
				<th>{{ $atribut->name }}</th>
				@endforeach
				<th>Probabilitas Layak</th>
				<th>Probabilitas Tidak Layak</th>
				<th>Kesimpulan</th>
			</tr>
		</thead>
		<tbody>
			@forelse($rows as $row)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $row['type'] }}</td>
				<td>{{ $row['name'] }}</td>
				<td>{{ $row['id_pelanggan'] }}</td>
				@foreach($atributs as $atribut)
				<td>{{ $row['attributes'][$atribut->slug] ?? '-' }}</td>
				@endforeach
				<td>{{ number_format((float) $row['prob_true'], 12, '.', '') }}</td>
				<td>{{ number_format((float) $row['prob_false'], 12, '.', '') }}</td>
				<td>{{ $stat[$row['conclusion']] ?? '-' }}</td>
			</tr>
			@empty
			<tr>
				<td colspan="{{ 8 + $atributs->count() }}" class="text-center">Belum ada data klasifikasi.</td>
			</tr>
			@endforelse
		</tbody>
	</table>
</body>
</html>
