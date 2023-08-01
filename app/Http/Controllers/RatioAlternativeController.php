<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Employe;
use App\Models\Ratio_alternative;
use Illuminate\Http\Request;
use App\Http\Controllers\RatioCriteriaController;
use Illuminate\Support\Facades\Log;

class RatioAlternativeController extends Controller
{

    const IR = array(
        0.01,
        0.58,
        0.90,
        1.12,
        1.24,
        1.32,
        1.41,
    );

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        try {
//
//            $employe = Employe::select('id', 'name')->get()->toArray();
//            $criteria = Criteria::all()->toArray();
//            $ratio = Ratio_alternative::join('criterias', 'ratio_alternatives.criteria_id', '=', 'criterias.id')
//                ->join('employes as v_employes', 'ratio_alternatives.v_alternative_id', '=', 'v_employes.id')
//                ->join('employes as h_employes', 'ratio_alternatives.h_alternative_id', '=', 'h_employes.id')
//                ->select('ratio_alternatives.value', 'v_employes.name as v_name', 'h_employes.name as h_name', 'criterias.name as criterias_name', 'criterias.id as criterias_id', 'v_employes.id as v_id', 'h_employes.id as h_id')
//                ->get()->toArray();
//            $data = (object)[
//                'employe' => $employe,
//                'criteria' => $criteria,
//                'ratio' => $ratio,
//            ];
//        } catch (\Throwable $th) {
//            return redirect('criteria')->with(['message' => 'data belum lengkap']);
//            $data = null;
//        }
//        //  dd($ratio);
//
//        return view('pages.alternative')->with('data', $data);
        $criteria = Criteria::orderBy('id')->get();;
        foreach ($criteria as $c) {
            $c->alternative = Alternative::where('criteria_id', $c->id)->orderBy('id')->get();
//            $matrixRatioRow = self::showAlternative($c->alternative);
            $dataMatrix = self::showAlternative($c);
            $c->matrix = $dataMatrix;
            $eigen = self::eigen($dataMatrix);
            $c->eigen = $eigen;
            Log::debug(json_encode($c->eigen));
            $sumCol = 0;
            if ($dataMatrix)
                $sumCol = $dataMatrix['sumCol'];

            $lamda = self::lamda($sumCol, $eigen);
            $c->lamda = $lamda;
        }

        $ratio = self::data();

        $data = (object)[
            'criteria' => $criteria,
            'ratio' => $ratio
        ];

        return view('pages.ratioAlternative')->with('data', $data);
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
            'criteria' => 'required',
            'v_alternative' => 'required',
            'h_alternative' => 'required',
            'value' => 'numeric',
        ]);

        if (Ratio_alternative::where('v_alternative_id', $request->v_alternative)
            ->where('h_alternative_id', $request->h_alternative)
            ->where('criteria_id', $request->criteria)
            ->count()
        ) {
            return redirect()->back()->with('message', 'Data Sudah Pernah disimpan');
        }

        if ($request->v_alternative == $request->h_alternative) {
            Ratio_alternative::create([
                'criteria_id' => $request->criteria,
                'v_alternative_id' => $request->v_alternative,
                'h_alternative_id' => $request->h_alternative,
                'value' => 1,
            ]);
        } else {
            Ratio_alternative::create([
                'criteria_id' => $request->criteria,
                'v_alternative_id' => $request->v_alternative,
                'h_alternative_id' => $request->h_alternative,
                'value' => $request->value,
            ]);

            Ratio_alternative::create([
                'criteria_id' => $request->criteria,
                'h_alternative_id' => $request->v_alternative,
                'v_alternative_id' => $request->h_alternative,
                'value' => (1 / $request->value),
            ]);
        }

        return redirect()->back()->with('message', 'Input Data Sukses')->withInput();
    }


    /**
     * Display the matrix resource.
     *
     * @return array
     */
    public static function showAlternative($criteria)
    {
        $alternative = Alternative::where('criteria_id', $criteria->id)->orderBy('id')->get();
        $matrix = array();
        $eigen = array();

        foreach ($alternative as $matrixColumn) {
            $column = $matrixColumn['id'];
            $nameColumn = $matrixColumn['description'];

            foreach ($alternative as $matrixRow) {
                $row = $matrixRow['id'];
                $nameRow = $matrixRow['description'];

                $dataRatio = Ratio_alternative::where("v_alternative_id", $column)
                    ->where("h_alternative_id", $row)
                    ->select('value')
                    ->first();

                if ($dataRatio) {
//                    $value = $dataRatio->select('value')->first();
                    $value = $dataRatio->value;
                } else {
                    $value = "N/A";
                }
                $matrix[$nameRow][$nameColumn] = $value;
            }
        }

        foreach ($matrix as $columnName => $columnVal) {
            $devider = self::sumMatrix($columnVal);

            foreach ($columnVal as $valueName => $valueMatrix) {
                if ($devider == "Data belum lengkap")
                    $count = $devider;
                else
                    $count = $valueMatrix / (int)$devider;

                $eigen[$columnName][$valueName] = $count;
            }
            $matrix[$columnName] = array_merge($columnVal, array('sumCol' => $devider));
        }

        return RatioCriteriaController::reverseMatrix($matrix);
    }

    public static function eigen($array): array
    {
        $data = array();

        foreach (RatioCriteriaController::reverseMatrix($array) as $key => $value) {
            $sumEigen = 0;
            $devieder = $value['sumCol'];
            foreach ($value as $name => $eigenVal) {
                if ($name == 'sumCol') {
                    continue;
                }
                if ($devieder == "Data belum lengkap") {
                    $data[$key][$name] = "N/A";
                    $sumEigen = 0;
                } else {
                    $counted = $eigenVal / $devieder;
                    $data[$key][$name] = $counted;
                    $sumEigen += $counted;
                }
            }
            $data[$key]['sumEigen'] = $sumEigen;
        }

        foreach (RatioCriteriaController::reverseMatrix($data) as $columnName => $columnVal) {
            $devider = self::sumMatrix($columnVal);

            $data[$columnName] = array_merge($columnVal, array('totalEigen' => $devider));
        }

        return $data;
    }

    public static function lamda($arraysumCOl, $arrayEigen)
    {
        $sumCol = $arraysumCOl;
        $avgEigen = array();
        $sumLamda = 0;
        foreach ($arrayEigen as $nameAlternative => $value) {
            Log::debug("-----------" . $nameAlternative);
            if ($nameAlternative == 'sumEigen') {
                continue;
            }
            $dataQuantity = (count($value) - 1);
//            $avgEigen[$nameAlternative] = $value['totalEigen'] / $dataQuantity;
            $avgEigen[$nameAlternative] = self::getAverage($value['totalEigen'], $dataQuantity);
//            Log::debug("avgeigen " . $avgEigen[$nameAlternative]);
//            $lamda[$nameAlternative] = $avgEigen[$nameAlternative] * $sumCol[$nameAlternative];
            $lamda[$nameAlternative] = self::getMultiply($avgEigen[$nameAlternative], $sumCol[$nameAlternative]);
//            Log::debug("nameAlternative " . $lamda[$nameAlternative] . " " . "sumLamda " . $sumLamda);
            if (is_numeric($lamda[$nameAlternative]) && is_numeric($sumLamda)) {
                $sumLamda += $lamda[$nameAlternative];
                $CI = ($sumLamda - $dataQuantity) / ($dataQuantity - 1);
                $constant = round($CI / self::IR[$dataQuantity - 2], 5);
            } else {
                $sumLamda = "N/A";
                $CI = "N/A";
                $constant = "N/A";
            }

        }


        return [
            "avgEigen" => $avgEigen,
            "sumCol" => $sumCol,
            "rawlamda" => $lamda,
            "sumLamda" => $sumLamda,
            "CI" => $CI,
            "constant" => $constant,
            "IR" => self::IR[$dataQuantity - 2]
        ];
    }

    public function massUpdate(Request $request)
    {
        foreach ($request->except(['_token', 'criteria', 'alternative']) as $key => $value) {
            $keyID = Employe::getIdfromName($key);
            $crtID = Criteria::getIdfromName($request->criteria);
            $altID = Employe::getIdfromName($request->alternative);
            if ($keyID == $altID) {
                continue;
            };
            Ratio_alternative::where([
                ['criteria_id', '=', $crtID],
                ['v_alternative_id', '=', $keyID],
                ['h_alternative_id', '=', $altID],
            ])->update([
                'value' => (1 / $value),
            ]);
            Ratio_alternative::where([
                ['criteria_id', '=', $crtID],
                ['v_alternative_id', '=', $altID],
                ['h_alternative_id', '=', $keyID],
            ])->update([
                'value' => $value,
            ]);
        }

        return redirect()->back()->with('message', 'Input Data Sukses');
    }

    public function destroy($criterias_id, $v_id, $h_id)
    {
        $ratio = Ratio_alternative::where('criteria_id', $criterias_id)
            ->where("v_alternative_id", $v_id)
            ->where("h_alternative_id", $h_id)->first();
        $reverseratio = Ratio_alternative::where('criteria_id', $criterias_id)
            ->where("v_alternative_id", $h_id)
            ->where("h_alternative_id", $v_id)->first();

        $ratio->delete();
        $reverseratio->delete();

        return redirect()->back()->with(["message" => "delet data perbanfdingan " . $ratio->value . " dan " . $reverseratio->value]);
    }

    public static function data()
    {
        $data = Ratio_alternative::join('alternatives as v_alternatives', 'ratio_alternatives.v_alternative_id', '=', 'v_alternatives.id')
            ->join('alternatives as h_alternatives', 'ratio_alternatives.h_alternative_id', '=', 'h_alternatives.id')
            ->join('criterias', 'ratio_alternatives.criteria_id', '=', 'criterias.id')
            ->select('ratio_alternatives.value', 'v_alternatives.description as v_name', 'h_alternatives.description as h_name', 'v_alternatives.id as v_id', 'h_alternatives.id as h_id', 'criterias.name as criteria')
            ->orderBy('ratio_alternatives.criteria_id', 'ASC')->get();

        return $data;
    }

    public function getRatio($alt1, $alt2)
    {
        $ratio = Ratio_alternative::where("v_alternative_id", $alt1)
            ->where("h_alternative_id", $alt2)->first();

        if ($ratio)
            return $ratio->value;

        return "";
    }

    public function getTotalRatio($alternative, $alt)
    {
        $jumlah = 0;
        foreach ($alternative as $key => $val) {
            $ratio = Ratio_alternative::where("v_alternative_id", $val['id'])
                ->where("h_alternative_id", $alt)->first();

            if (!$ratio) {
                return "Data Belum Lengkap";
            } else {
                $r = $ratio->value;
                $jumlah = $jumlah + $r;
            }
        }
        return $jumlah;
    }

    public static function getTotalRatioByAlt($alt)
    {
        $ratio = Ratio_alternative::where("h_alternative_id", $alt)->sum('value');
        return $ratio;
    }

    public static function sumMatrixWithHeader($array)
    {
        try {
            $total = 0;
            foreach ($array as $key => $value) {
                if ($key == 0) {
                    continue;
                }
                $total += $value;
            }

            return $total;
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return "Data belum lengkap";
        }
    }

    public static function sumMatrix($array)
    {
        try {
            $total = 0;
            foreach ($array as $key => $value) {
                $total += $value;
            }

            return $total;
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return "Data belum lengkap";
        }

    }

    public static function getAverage($value, $divider)
    {
        try {
            return round($value / $divider, 3);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return "N/A";
        }
    }

    public static function getMultiply($value1, $value2)
    {
        try {
            return $value1 * $value2;
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
            return "N/A";
        }
    }
}
