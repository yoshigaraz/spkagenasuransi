<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Ratio_alternative;
use Illuminate\Http\Request;

class AlternativeController extends Controller
{
    public function index()
    {
        $criteria = Criteria::orderBy('id')->get()->toArray();
        $alternative = Alternative::join('criterias', 'criterias.id', '=', 'alternatives.criteria_id')
            ->orderBy('criteria_id')->orderBy('id')
            ->select(['alternatives.*','criterias.name as criteria'])
            ->get()->toArray();

        $data = (object)[
            'criteria' => $criteria,
            'alternative' => $alternative,
        ];

        return view('pages.alternative')->with('data', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'criteria' => 'required|string',
            'description' => 'required|string',
            'min_value' => 'required|numeric',
            'max_value' => 'required|numeric',
            'weight' => 'required|numeric',
        ]);

        Alternative::create([
            'criteria_id' => $request->criteria,
            'description' => $request->description,
            'min_value' => $request->min_value,
            'max_value' => $request->max_value,
            'weight' => $request->weight,
        ]);

        return redirect()->back()->with('message', 'Insert Data Alternative Success');
    }

    public function destroy(Alternative $alternative)
    {
        $existance = Ratio_alternative::where('v_alternative_id', $alternative->id)
            ->orWhere('h_alternative_id', $alternative->id)
            ->count();
        if ($existance > 1) {
            return redirect()->back()->with(["message" => "Info : Alternatif memiliki relasi perbandingan!"]);
        } else {
            $alternative->delete();
            return redirect()->back()->with(["message" => "Delete Data sukses"]);
        }
    }

    public function getAlternativeByCriteria(Request $request)
    {
        $alternative = Alternative::where('criteria_id', $request->criteria)
            ->orderBy('id')
            ->get();

        if ($request->ajax()){
            $data = view('components.alternative_select', compact('alternative'))->render();
            return response()->json(['options' => $data]);
        }
        return response()->json(['alternative' => $alternative], 200);
    }
}
