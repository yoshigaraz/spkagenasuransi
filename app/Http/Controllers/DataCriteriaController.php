<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Data_criteria;
use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DataCriteriaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return view
     */
    public function index()
    {
        $employe = Employe::orderBy('name')->get();
        $criteria = Criteria::orderBy('id')->get();

        foreach ($criteria as $c) {
            $c->min = Alternative::where('criteria_id', $c->id)->min('min_value');
            $c->max = Alternative::where('criteria_id', $c->id)->max('max_value');
        }

        $data_criteria = Data_criteria::leftJoin('employes', 'employes.id', '=', 'data_criterias.employe_id')
            ->leftJoin('criterias', 'criterias.id', '=', 'data_criterias.criteria_id')
            ->select('data_criterias.*', 'employes.name as karyawan', 'employes.id as karyawan_id', 'criterias.name as kriteria')->get();
        $listData = array();
        foreach ($data_criteria as $key => $value) {
            $nama = $value['karyawan'];
            $kriteria = $value['kriteria'];
            $period = $value['period'];
            $listData[$nama][$kriteria] = $value['value'];
            $listData[$nama]['karyawan_id'] = $value['karyawan_id'];
            $listData[$nama]['period'] = $period;
        };
        Log::debug(json_encode($listData));
        $data = [
            'employe' => $employe,
            'criteria' => $criteria,
            'listData' => $listData
        ];


        return view('pages.criteriaData')->with('data', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dd($request->all());
        Log::debug(json_encode($request->all()));
        foreach ($request->except(['_token', 'employe_id', 'period']) as $key => $value) {
            Data_criteria::updateOrCreate(
                ['employe_id' => $request->employe_id, 'criteria_id' => $key, 'period' => $request->period],
                ['value' => $value]
            );
        };

        return redirect()->back()->with('message', 'Input Data Sukses');
    }

    public function destroy($id)
    {

        Data_criteria::where('employe_id', $id)->delete();

        return redirect()->back()->with('message', 'Delete Data Sukses');
    }

}
