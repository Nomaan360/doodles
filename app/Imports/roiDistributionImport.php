<?php

namespace App\Imports;

use App\Models\roiDistributionModel;
use Maatwebsite\Excel\Concerns\ToModel;

class roiDistributionImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new roiDistributionModel([
            //
        ]);
    }
}
