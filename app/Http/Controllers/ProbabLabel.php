<?php

namespace App\Http\Controllers;

use App\Models\Atribut;
use App\Models\Classification;
use App\Models\NilaiAtribut;
use App\Models\Probability;
use App\Models\TrainingData;
use App\Models\TestingData;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class ProbabLabel extends Controller
{
	public static array $label = [
		0 => 'Tidak Layak',	//FALSE: Tidak berhak mendapatkan bantuan sosial
		1 => 'Layak'				//TRUE : Berhak mendapatkan bantuan sosial
	];
	public static function preprocess(string $type)
	{ //Preprocessing: Mengisi nilai yang hilang (Impute missing values)
		try {
			$novals = 0;
			if ($type === 'test') $data = new TestingData();
			else $data = new TrainingData();
			foreach (Atribut::get() as $attr) {
				$missing = $data->whereNull($attr->slug)->get();
				$novals += count($missing);
				if (count($missing) > 0) {
					if ($attr->type === 'numeric') //Jika Numerik, rata-rata yang dicari
						$avg = $data->avg($attr->slug);
					else { //Jika Kategorikal, paling sering muncul yang dicari
						$most = $data->select($attr->slug)->whereNotNull($attr->slug)
							->groupBy($attr->slug)->orderByRaw("COUNT(*) desc")->first();
						// if (empty($most[$attr->slug])) continue;
					}
					$data->whereNull($attr->slug)
						->update([$attr->slug => $most[$attr->slug] ?? $avg]);
				}
			}
			return $novals;
		} catch (QueryException $e) {
			Log::error($e);
			return false;
		}
	}
	public static function hitungProbab($data)
	{ //Proses perhitungan klasifikasi
		$detail = self::explainProbab($data);
		return [
			'true' => $detail['posterior']['true'],
			'false' => $detail['posterior']['false'],
			'predict' => $detail['predict']
		];
	}
	public static function explainProbab($data): array
	{
		$semuadata = TrainingData::count();
		$prior = Probability::probabKelas(); //Prior
		$likelihood['true'] = $likelihood['false'] = $evidence = 1;
		$steps = [];
		foreach (Atribut::get() as $at) {
			if ($at->type === 'categorical') {
				$probabilitas = Probability::firstWhere('nilai_atribut_id', $data[$at->slug]);
				$nilai = NilaiAtribut::find($data[$at->slug]);
				$probabs = [
					'true' => json_decode($probabilitas->true ?? '0'),
					'false' => json_decode($probabilitas->false ?? '0')
				];
				$likelihood['true'] *= $probabs['true'];
				$likelihood['false'] *= $probabs['false'];
				$evidencePart = $semuadata === 0 ? 0 :
					TrainingData::where($at->slug, $data[$at->slug])->count() / $semuadata;
				$evidence *= $evidencePart;
				$steps[] = [
					'atribut' => $at->name,
					'type' => 'categorical',
					'input' => $nilai->name ?? '-',
					'input_raw' => $data[$at->slug],
					'prob_true' => $probabs['true'],
					'prob_false' => $probabs['false']
				];
			} else {
				$probabilitas = Probability::where('atribut_id', $at->id)
					->whereNull('nilai_atribut_id')->first();
				$trues = json_decode($probabilitas->true ?? '{"mean":0,"sd":0}');
				$falses = json_decode($probabilitas->false ?? '{"mean":0,"sd":0}');
				$total = json_decode($probabilitas->total ?? '{"mean":0,"sd":0}');
				$lhTrue = self::normalDistribution($data[$at->slug], $trues->sd, $trues->mean);
				$lhFalse = self::normalDistribution($data[$at->slug], $falses->sd, $falses->mean);
				$evidencePart = self::normalDistribution($data[$at->slug], $total->sd, $total->mean);
				$likelihood['true'] *= $lhTrue;
				$likelihood['false'] *= $lhFalse;
				$evidence *= $evidencePart;
				$steps[] = [
					'atribut' => $at->name,
					'type' => 'numeric',
					'input' => $data[$at->slug],
					'prob_true' => $lhTrue,
					'prob_false' => $lhFalse,
					'mean_true' => $trues->mean,
					'sd_true' => $trues->sd,
					'mean_false' => $falses->mean,
					'sd_false' => $falses->sd
				];
			}
		}
		$posterior = [ //Posterior
			'true' => $evidence == 0 ? 0 : ($prior['true'] * $likelihood['true']) / $evidence,
			'false' => $evidence == 0 ? 0 : ($prior['false'] * $likelihood['false']) / $evidence
		];
		return [
			'prior' => $prior,
			'likelihood' => $likelihood,
			'posterior' => $posterior,
			'predict' => $posterior['true'] >= $posterior['false'],
			'steps' => $steps
		];
	}
	public static function resetProbab(): void
	{ //Reset Probabilitas
		if (Probability::count() > 0) Probability::truncate();
		if (Classification::count() > 0) Classification::truncate();
	}
	public static function normalDistribution(int|float $x, float|int $sd, float|int $mean)
	{
		if ((float) $sd == 0.0) return (float) $x == (float) $mean ? 1.0 : 0.0;
		return (1 / ($sd * sqrt(2 * pi()))) * exp(-0.5 * pow(($x - $mean) / $sd, 2));
	}
	/**
	 * Simpangan Baku
	 *
	 * @param array $a
	 * @param bool $sample [optional] Defaults to false
	 * @return float|bool The standard deviation or false on error.
	 */
	public static function stats_standard_deviation(array $a, bool $sample = false)
	{
		$n = count($a);
		if ($n === 0) return false;
		if ($sample && $n === 1) return false;
		$mean = array_sum($a) / $n;
		$carry = 0.0;
		foreach ($a as $val) $carry += pow(((float) $val) - $mean, 2);
		if ($sample) --$n;
		return sqrt($carry / $n);
	}
}
