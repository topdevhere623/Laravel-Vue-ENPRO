<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\SwitchInfo;
use App\Models\WireAssemblyInfo;
use App\Models\OldTransformerTankInfo;
use App\Models\TransformerTankInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExcelLoadController extends Controller
{
    public function importExcel(Request $request, $modelName)
    {
        ini_set('memory_limit', '2048M');
        ini_set('set_time_limit', '3600');
        ini_set('max_execution_time', '3600');
        $this->deleteRows($modelName);
        $getFile = $request['file'];
        $excelImportName = "App\\Imports\\{$modelName}Import";
        Excel::import(new $excelImportName, $getFile);
        return true;
    }

    private function deleteRows($modelName)
    {
        if (in_array($modelName , WireAssemblyInfo::CHILD_MODELS)) {
            WireAssemblyInfo::whereHas('WirePhaseInfo', function($query) use ($modelName){
                $query->whereHas('WireInfo', function($query) use ($modelName){
                    $query->whereHas($modelName);
                });
            })->forceDelete();
        }
        if (in_array($modelName , SwitchInfo::CHILD_MODELS)) {
            SwitchInfo::whereHas('OldSwitchInfo', function($query) use ($modelName){
                $query->whereHas($modelName);
            })->forceDelete();
        }
        if ($modelName == 'OldTransformerTankInfo') {
            $models = ['OldTransformerTankInfo', 'TransformerTankInfo', 'TransformerEndInfo', 'OldTransformerEndInfo', 'NoLoadTest', 'ShortCircuitTest'];
            foreach ($models as $model) {
                $fullModelName = "App\\Models\\{$model}";
                $fullModelName::query()->forceDelete();
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                $fullModelName::query()->truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        }
    }

    public function indexView()
    {
        return view('backend.loadExсel.index', ['models' => config('loadexcel'), 'content' => ['name' => 'Загрузка из Excel']]);
    }
}
