<?php

namespace App\Imports;

use App\Http\Controllers\ProbabLabel;
use App\Models\Atribut;
use App\Models\NilaiAtribut;
use App\Models\TestingData;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TestingImport implements ToModel, WithHeadingRow, WithValidation
{
	public function prepareForValidation($data, $index)
	{
		$data['id_pelanggan'] = isset($data['id_pelanggan'])
			? (string) $data['id_pelanggan']
			: null;
		$data['daya_terpasang'] = isset($data['daya_terpasang'])
			? (int) $data['daya_terpasang']
			: null;
		return $data;
	}
	/**
	 * @param array $row
	 *
	 * @return \Illuminate\Database\Eloquent\Model|null
	 */
	public function model(array $row)
	{
		$rows = [];
		$rows['nama'] = ucfirst($row['nama']);
		$rows['id_pelanggan'] = strtoupper(trim((string) ($row['id_pelanggan'] ?? '')));
		$rows['daya_terpasang'] = (int) ($row['daya_terpasang'] ?? 0);
		foreach (Atribut::get() as $attr) {
			if ($attr->type === 'categorical') {
				if (empty($row[$attr->slug])) $row[$attr->slug] = null;
				else {
					$foreign = NilaiAtribut::firstWhere(
						'name',
						'like',
						"%{$row[$attr->slug]}%"
					);
					$row[$attr->slug] = $foreign->id ?? null;
				}
			}
			$rows[$attr->slug] = $row[$attr->slug];
		}
		$status = trim((string) ($row['status'] ?? ''));
		$rows['status'] = $status === '' ? null : array_search( //array_search secara case insensitive
			strtolower($status),
			array_map('strtolower', ProbabLabel::$label)
		);
		if ($rows['status'] === false) $rows['status'] = null;

		$existing = TestingData::where('id_pelanggan', $rows['id_pelanggan'])
			->where('daya_terpasang', $rows['daya_terpasang'])
			->first();
		if ($existing) {
			$existing->fill($rows);
			$existing->save();
			return null;
		}
		return new TestingData($rows);
	}
	public function rules(): array
	{
		$rules['nama'] = ['bail', 'required', 'string'];
		$rules['id_pelanggan'] = ['bail', 'required', 'string', 'max:50'];
		$rules['daya_terpasang'] = ['bail', 'required', 'integer', 'min:1'];
		foreach (Atribut::get() as $attr) {
			if ($attr->type === 'categorical')
				$rules[$attr->slug] = ['nullable', 'string'];
			else $rules[$attr->slug] = ['nullable', 'numeric'];
		}
		$rules['status'] = ['bail', 'nullable', Rule::in(ProbabLabel::$label)];
		return $rules;
	}
}
