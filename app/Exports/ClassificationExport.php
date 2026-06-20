<?php

namespace App\Exports;

use App\Http\Controllers\ProbabLabel;
use App\Models\Classification;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ClassificationExport
implements FromQuery, ShouldAutoSize, WithHeadings, WithMapping, WithStrictNullComparison
{
	use Exportable;
	private string $tipe;
	private int $index = 0;
	public function __construct(string $type)
	{
		$this->tipe = $type;
	}
	public function headings(): array
	{
		return array(
			'#',
			'Nama',
			'ID Pelanggan',
			'Daya Terpasang',
			ProbabLabel::$label[true],
			ProbabLabel::$label[false],
			'Kelas Prediksi',
			'Kelas Asli'
		);
	}
	/**
	 * @return \Illuminate\Support\Collection
	 */
	public function query()
	{
		if ($this->tipe === 'train' || $this->tipe === 'test')
			return Classification::query()->where('type', $this->tipe);
		return Classification::query();
	}
	public function map($class): array
	{
		return array(
			++$this->index,
			$class->name,
			$class->id_pelanggan,
			$class->daya_terpasang,
			$class->true ?? 0.00,
			$class->false ?? 0.00,
			ProbabLabel::$label[$class->predicted],
			is_null($class->real) ? '-' : ProbabLabel::$label[$class->real]
		);
	}
}
