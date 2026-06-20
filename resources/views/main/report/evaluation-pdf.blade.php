<!doctype html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Laporan Evaluasi Model</title>
	<style>
		body { font-family: Arial, sans-serif; margin: 20px; color: #111; }
		h1, h2 { margin: 0 0 10px 0; }
		p { margin: 0 0 8px 0; }
		table { border-collapse: collapse; width: 100%; margin-top: 10px; margin-bottom: 16px; }
		th, td { border: 1px solid #333; padding: 6px 8px; font-size: 12px; }
		th { background: #efefef; text-align: left; }
		.text-center { text-align: center; }
		@media print { .no-print { display: none; } }
	</style>
</head>
<body>
	<div class="no-print" style="margin-bottom: 16px;">
		<button onclick="window.print()">Cetak / Simpan PDF</button>
	</div>
	<h1>Laporan Evaluasi Model Naive Bayes</h1>
	<p>Tanggal cetak: {{ now()->format('d-m-Y H:i') }}</p>

	<h2>Ringkasan Testing</h2>
	<table>
		<thead>
			<tr>
				<th>TP</th>
				<th>FP</th>
				<th>FN</th>
				<th>TN</th>
				<th>Total</th>
				<th>Accuracy (%)</th>
				<th>Precision (%)</th>
				<th>Recall (%)</th>
				<th>F1 (%)</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{{ $data['test']['tp'] }}</td>
				<td>{{ $data['test']['fp'] }}</td>
				<td>{{ $data['test']['fn'] }}</td>
				<td>{{ $data['test']['tn'] }}</td>
				<td>{{ $data['test']['total'] }}</td>
				<td>{{ round($performa['test']['accuracy'], 2) }}</td>
				<td>{{ round($performa['test']['precision'], 2) }}</td>
				<td>{{ round($performa['test']['recall'], 2) }}</td>
				<td>{{ round($performa['test']['f1'], 2) }}</td>
			</tr>
		</tbody>
	</table>

	<h2>Ringkasan Training</h2>
	<table>
		<thead>
			<tr>
				<th>TP</th>
				<th>FP</th>
				<th>FN</th>
				<th>TN</th>
				<th>Total</th>
				<th>Accuracy (%)</th>
				<th>Precision (%)</th>
				<th>Recall (%)</th>
				<th>F1 (%)</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{{ $data['train']['tp'] }}</td>
				<td>{{ $data['train']['fp'] }}</td>
				<td>{{ $data['train']['fn'] }}</td>
				<td>{{ $data['train']['tn'] }}</td>
				<td>{{ $data['train']['total'] }}</td>
				<td>{{ round($performa['train']['accuracy'], 2) }}</td>
				<td>{{ round($performa['train']['precision'], 2) }}</td>
				<td>{{ round($performa['train']['recall'], 2) }}</td>
				<td>{{ round($performa['train']['f1'], 2) }}</td>
			</tr>
		</tbody>
	</table>

	<h2>Detail Prediksi Data Testing</h2>
	<table>
		<thead>
			<tr>
				<th>Nama</th>
				<th>ID Pelanggan</th>
				<th>Prediksi</th>
				<th>Aktual</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			@foreach($detailTest as $row)
			<tr>
				<td>{{ $row->name }}</td>
				<td>{{ $row->id_pelanggan }}</td>
				<td>{{ $stat[$row->predicted] }}</td>
				<td>{{ $stat[$row->real] }}</td>
				<td>{{ $row->predicted == $row->real ? 'Benar' : 'Salah' }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</body>
</html>
