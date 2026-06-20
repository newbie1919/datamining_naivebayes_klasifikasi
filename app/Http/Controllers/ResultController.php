<?php

namespace App\Http\Controllers;

use App\Exports\ClassificationReportExport;
use App\Models\Atribut;
use App\Models\Classification;
use App\Models\NilaiAtribut;
use App\Models\TestingData;
use App\Models\TrainingData;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResultController extends Controller
{
	public function report()
	{
		if (Classification::count() === 0) {
			return to_route('home')->withWarning(
				'Belum ada hasil klasifikasi untuk ditampilkan pada laporan'
			);
		}
		[$atributs, $stat, $rows] = $this->classificationReportData();

		return view('main.report.classification-report', compact('rows', 'atributs', 'stat'));
	}
	public function exportClassificationCsv()
	{
		if (Classification::count() === 0) {
			return to_route('home')->withWarning(
				'Belum ada hasil klasifikasi untuk diekspor'
			);
		}
		[$atributs, $stat, $rows] = $this->classificationReportData();
		$filename = 'laporan_klasifikasi_' . time() . '.csv';

		return response()->streamDownload(function () use ($atributs, $stat, $rows) {
			$out = fopen('php://output', 'w');
			$header = ['Tipe', 'Nama', 'ID Pelanggan', 'Daya Terpasang'];
			foreach ($atributs as $atribut) {
				$header[] = $atribut->name;
			}
			$header[] = 'Probabilitas Layak';
			$header[] = 'Probabilitas Tidak Layak';
			$header[] = 'Kesimpulan';

			fputcsv($out, ['Laporan Detail Klasifikasi Naive Bayes']);
			fputcsv($out, ['Dicetak pada', Carbon::now()->format('Y-m-d H:i:s')]);
			fputcsv($out, []);
			fputcsv($out, $header);

			foreach ($rows as $row) {
				$line = [
					$row['type'],
					$row['name'],
					$row['id_pelanggan'],
					$row['daya_terpasang']
				];
				foreach ($atributs as $atribut) {
					$line[] = $row['attributes'][$atribut->slug] ?? '-';
				}
				$line[] = $row['prob_true'];
				$line[] = $row['prob_false'];
				$line[] = $stat[$row['conclusion']] ?? '-';
				fputcsv($out, $line);
			}
			fclose($out);
		}, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
	}
	public function exportClassificationPdf()
	{
		if (Classification::count() === 0) {
			return to_route('home')->withWarning(
				'Belum ada hasil klasifikasi untuk diekspor'
			);
		}
		[$atributs, $stat, $rows] = $this->classificationReportData();
		return view('main.report.classification-report-pdf', compact('rows', 'atributs', 'stat'));
	}
	public function exportClassificationExcel()
	{
		if (Classification::count() === 0) {
			return to_route('home')->withWarning(
				'Belum ada hasil klasifikasi untuk diekspor'
			);
		}
		[$atributs, $stat, $rows] = $this->classificationReportData();
		return Excel::download(
			new ClassificationReportExport($atributs, $rows, $stat),
			'laporan_klasifikasi_' . time() . '.xlsx'
		);
	}

	public function __invoke()
	{ //Tampilkan halaman Performa
		if (Classification::count() === 0) {
			return to_route('home')->withWarning(
				'Belum ada hasil klasifikasi untuk melihat performa model'
			);
		}
		$data = ['train' => $this->cm('train'), 'test' => $this->cm('test')];
		$performa = [
			'train' => $this->performa($data['train']),
			'test' => $this->performa($data['test'])
		];
		$stat = ProbabLabel::$label;
		$detailTest = Classification::where('type', 'test')
			->whereNotNull('real')
			->orderByDesc('id')
			->get(['name', 'id_pelanggan', 'daya_terpasang', 'predicted', 'real']);
		return view('main.performa', compact('data', 'performa', 'stat', 'detailTest'));
	}

	public function exportCsv(): StreamedResponse
	{
		$data = ['train' => $this->cm('train'), 'test' => $this->cm('test')];
		$performa = [
			'train' => $this->performa($data['train']),
			'test' => $this->performa($data['test'])
		];
		$stat = ProbabLabel::$label;
		$filename = 'evaluasi_model_' . time() . '.csv';

		return response()->streamDownload(function () use ($data, $performa, $stat) {
			$out = fopen('php://output', 'w');
			fputcsv($out, ['Laporan Evaluasi Model']);
			fputcsv($out, ['Dicetak pada', Carbon::now()->format('Y-m-d H:i:s')]);
			fputcsv($out, []);

			foreach (['test' => 'Testing', 'train' => 'Training'] as $type => $label) {
				fputcsv($out, ["Ringkasan {$label}"]);
				fputcsv($out, ['TP', 'FP', 'FN', 'TN', 'Total']);
				fputcsv($out, [
					$data[$type]['tp'],
					$data[$type]['fp'],
					$data[$type]['fn'],
					$data[$type]['tn'],
					$data[$type]['total']
				]);
				fputcsv($out, ['Accuracy', 'Precision', 'Recall', 'F1']);
				fputcsv($out, [
					round($performa[$type]['accuracy'], 2),
					round($performa[$type]['precision'], 2),
					round($performa[$type]['recall'], 2),
					round($performa[$type]['f1'], 2)
				]);
				fputcsv($out, []);
			}

			fputcsv($out, ['Detail Prediksi Data Testing']);
			fputcsv($out, [
				'Nama',
				'ID Pelanggan',
				'Daya Terpasang',
				'Prediksi',
				'Aktual',
				'Status'
			]);
			Classification::where('type', 'test')
				->whereNotNull('real')
				->orderBy('id')
				->chunk(200, function ($rows) use ($out, $stat) {
					foreach ($rows as $row) {
						$pred = $stat[$row->predicted];
						$real = $stat[$row->real];
						fputcsv($out, [
							$row->name,
							$row->id_pelanggan,
							$row->daya_terpasang,
							$pred,
							$real,
							$row->predicted == $row->real ? 'Benar' : 'Salah'
						]);
					}
				});
			fclose($out);
		}, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
	}

	public function exportPdf()
	{
		if (Classification::count() === 0) {
			return to_route('home')->withWarning(
				'Belum ada hasil klasifikasi untuk mengekspor evaluasi'
			);
		}
		$data = ['train' => $this->cm('train'), 'test' => $this->cm('test')];
		$performa = [
			'train' => $this->performa($data['train']),
			'test' => $this->performa($data['test'])
		];
		$stat = ProbabLabel::$label;
		$detailTest = Classification::where('type', 'test')
			->whereNotNull('real')
			->orderByDesc('id')
			->get(['name', 'id_pelanggan', 'daya_terpasang', 'predicted', 'real']);
		return view('main.report.evaluation-pdf', compact('data', 'performa', 'stat', 'detailTest'));
	}
	private function classificationReportData(): array
	{
		$atributs = Atribut::orderBy('id')->get(['id', 'name', 'slug', 'type', 'desc']);
		$stat = ProbabLabel::$label;
		$rows = $this->classificationReportRows($atributs);

		return [$atributs, $stat, $rows];
	}
	private function classificationReportRows($atributs)
	{
		$attrSlugs = $atributs->pluck('slug')->all();
		$selectedColumns = array_merge(['id_pelanggan', 'daya_terpasang'], $attrSlugs);

		$training = TrainingData::select($selectedColumns)->get();
		$testing = TestingData::select($selectedColumns)->get();
		$trainingMap = $training->keyBy(function ($row) {
			return $row->id_pelanggan . '|' . $row->daya_terpasang;
		});
		$testingMap = $testing->keyBy(function ($row) {
			return $row->id_pelanggan . '|' . $row->daya_terpasang;
		});

		$categoricalIds = $atributs->where('type', 'categorical')->pluck('id');
		$nilaiMap = NilaiAtribut::whereIn('atribut_id', $categoricalIds)
			->get(['id', 'atribut_id', 'name'])
			->groupBy('atribut_id')
			->map(function ($items) {
				return $items->pluck('name', 'id');
			});

		return Classification::orderByDesc('id')
			->get(['type', 'name', 'id_pelanggan', 'daya_terpasang', 'true', 'false', 'predicted'])
			->map(function ($row) use ($atributs, $nilaiMap, $trainingMap, $testingMap) {
				$key = $row->id_pelanggan . '|' . $row->daya_terpasang;
				$source = $row->type === 'train' ? $trainingMap->get($key) : $testingMap->get($key);
				$values = [];

				foreach ($atributs as $atribut) {
					$raw = $source->{$atribut->slug} ?? null;
					if ($raw === null) {
						$values[$atribut->slug] = '-';
						continue;
					}

					if ($atribut->type === 'categorical') {
						$values[$atribut->slug] = $nilaiMap[$atribut->id][$raw] ?? $raw;
					} else {
						$values[$atribut->slug] = $raw;
					}
				}

				return [
					'type' => Classification::$tipedata[$row->type] ?? $row->type,
					'name' => $row->name,
					'id_pelanggan' => $row->id_pelanggan,
					'daya_terpasang' => $row->daya_terpasang,
					'attributes' => $values,
					'prob_true' => $row->true,
					'prob_false' => $row->false,
					'conclusion' => $row->predicted
				];
			});
	}
	private static function cm(string $type)
	{ //Hitung data Confusion Matrix
		$tp = Classification::where('type', $type)->whereNotNull('real')->where('predicted', true)
			->where('real', true)->count(); //True Positive
		$fp = Classification::where('type', $type)->whereNotNull('real')->where('predicted', true)
			->where('real', false)->count(); //False Positive
		$fn = Classification::where('type', $type)->whereNotNull('real')->where('predicted', false)
			->where('real', true)->count(); //False Negative
		$tn = Classification::where('type', $type)->whereNotNull('real')->where('predicted', false)
			->where('real', false)->count(); //True Negative
		$total = $tp + $fp + $fn + $tn;
		return [
			'tp' => $tp, 'fp' => $fp, 'fn' => $fn, 'tn' => $tn, 'total' => $total
		];
	}
	private static function performa(array $data)
	{ //Hitung Akurasi, Presisi, Recall, dan F1-score
		if ($data['total'] === 0) $accu = $prec = $rec = $f1 = 0;
		else {
			$accu = (($data['tp'] + $data['tn']) / $data['total']) * 100;
			$prec = ($data['tp'] + $data['fp']) === 0 ? 0 : ($data['tp'] / ($data['tp'] + $data['fp'])) * 100;
			$rec = ($data['tp'] + $data['fn']) === 0 ? 0 : ($data['tp'] / ($data['tp'] + $data['fn'])) * 100;
			$f1 = ($prec + $rec) === 0 ? 0 : 2 * ($prec * $rec) / ($prec + $rec);
		}
		return [
			'accuracy' => $accu, 'precision' => $prec, 'recall' => $rec, 'f1' => $f1
		];
	}
}
