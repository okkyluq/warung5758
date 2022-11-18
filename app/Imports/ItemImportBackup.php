<?php

namespace App\Imports;

use App\Item;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;

class ItemImport implements ToCollection, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(Collection $row)
    {
        dd($row);
        // return new Item([
        //     //
        // ]);
        
        return response()->json(collect($row));
    }

    public function startRow(): int
    {
        return 5;
    }
}
