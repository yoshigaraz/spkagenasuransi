<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Ratio_criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $criteria = Criteria::all()->toArray();
            $ratio = RatioCriteriaController::data();
            $data = (object)[
                'criteria' => $criteria,
                'ratio' => $ratio,
            ];
        } catch (\Throwable $th) {
            $data = null;
        }

        // dd($data);
        return view('pages.criteria')->with('data', $data);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string',
            'weight' => 'required|numeric',
        ]);

        Criteria::create([
            'name' => $request->name,
            'code' => $request->code,
            'weight' => $request->weight,
        ]);

        return redirect()->back()->with('message', 'Insert Data Criteria Success');
    }

    public function storeRatio(Request $request)
    {
        $request->validate([
            'v_criteria' => 'required',
            'h_criteria' => 'required',
            'value' => 'numeric',
        ]);

        if (Ratio_criteria::where('v_criteria_id', $request->v_criteria)
            ->where('h_criteria_id', $request->h_criteria)
            ->count()
        ) {
            return redirect()->back()->with('message', 'Data Sudah Pernah disimpan');
        }

        if ($request->v_criteria == $request->h_criteria) {
            Ratio_criteria::create(
                [
                    'v_criteria_id' => $request->v_criteria,
                    'h_criteria_id' => $request->h_criteria,
                    'value' => 1,
                ]);
        } else {
            Ratio_criteria::create(
                [
                    'v_criteria_id' => $request->v_criteria,
                    'h_criteria_id' => $request->h_criteria,
                    'value' => $request->value,
                ]);
            Ratio_criteria::create([
                'h_criteria_id' => $request->v_criteria,
                'v_criteria_id' => $request->h_criteria,
                'value' => (1 / $request->value),
            ]);
        }

        return redirect()->back()->with('message', 'Input Data Sukses');
    }

    public function massUpdate(Request $request)
    {

        foreach ($request->except(['_token', 'row']) as $key => $value) {
            $keyID = Criteria::getIdfromName($key);
            $rowID = Criteria::getIdfromName($request->row);
            if ($keyID == $rowID) {
                continue;
            };
            Ratio_criteria::where([
                ['h_criteria_id', '=', $keyID],
                ['v_criteria_id', '=', $rowID],
            ])->update([
                'value' => $value,
            ]);
            Ratio_criteria::where([
                ['h_criteria_id', '=', $rowID],
                ['v_criteria_id', '=', $keyID],
            ])->update([
                'value' => (1 / $value),
            ]);
        }

        return redirect()->back()->with('message', 'Input Data Sukses');
    }

    public function destroy(Criteria $criteria)
    {
        $existance = Ratio_criteria::where('v_criteria_id', $criteria->id)
            ->orWhere('h_criteria_id', $criteria->id)
            ->count();
        if ($existance > 1) {
            return redirect()->back()->with(["message" => "Info : Kriteria memiliki relasi perbandingan!"]);
        } else {
            $criteria->delete();
            return redirect()->back()->with(["message" => "Delete Data sukses"]);
        }
    }
}
