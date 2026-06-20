<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassificationReportExport implements FromCollection, ShouldAutoSize, WithHeadings
{
	private Collection $atributs;
	private Collection $rows;
	private array $stat;

	public function __construct(Collection $atributs, Collection $rows, array $stat)
	{
		$this->atributs = $atributs;
		$this->rows = $rows;
		$this->stat = $stat;
	}
	public function headings(): array
	{
		$headings = ['Tipe', 'Nama', 'ID Pelanggan', 'Daya Terpasang'];
		foreach ($this->atributs as $atribut) {
			$headings[] = $atribut->name;
		}
		$headings[] = 'Probabilitas Layak';
		$headings[] = 'Probabilitas Tidak Layak';
		$headings[] = 'Kesimpulan';

		return $headings;
	}
	public function collection()
	{
		return $this->rows->map(function ($row) {
			$data = [
				$row['type'],
				$row['name'],
				$row['id_pelanggan'],
				$row['daya_terpasang']
			];

			foreach ($this->atributs as $atribut) {
				$data[] = $row['attributes'][$atribut->slug] ?? '-';
			}

			$data[] = $row['prob_true'];
			$data[] = $row['prob_false'];
			$data[] = $this->stat[$row['conclusion']] ?? '-';

			return $data;
		});
	}
}
