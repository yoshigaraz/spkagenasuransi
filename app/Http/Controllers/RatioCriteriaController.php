<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Employe;
use App\Models\Ratio_criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RatioCriteriaController extends Controller
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
        $data = self::generate();

//        } catch (\Throwable $th) {
//            return redirect('criteria')->with(['message' => "data belum lengkap"]);
//            $data = null;
//        }
        return view('pages.ratioCriteria')->with('data', $data);
    }

    /**
     *
     * @param array Matrix Source
     * @param array Matrix Eigen
     *
     * @return array Matrix data array
     *
     */

    public static function lamda($arraysumCOl, $arrayEigen)
    {
        $sumCol = $arraysumCOl;
        $avgEigen = array();
        $sumLamda = 0;
        foreach ($arrayEigen as $nameCriteria => $value) {
            if ($nameCriteria == 'sumEigen') {
                continue;
            }
            $dataQuantity = (count($value) - 1);
            $avgEigen[$nameCriteria] = $value['totalEigen'] / $dataQuantity;
            $lamda[$nameCriteria] = $avgEigen[$nameCriteria] * $sumCol[$nameCriteria];
            $sumLamda += $lamda[$nameCriteria];
            $CI = ($sumLamda - $dataQuantity) / ($dataQuantity - 1);
            $constant = $CI / self::IR[$dataQuantity - 2];
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

    public static function generate()
    {
        $matrix = RatioCriteriaController::showCriteria();
        $eigen = RatioCriteriaController::eigen($matrix);

        $sumCol = 0;
        if ($matrix)
            $sumCol = $matrix['sumCol'];

        $lamda = self::lamda($sumCol, $eigen);

        $criteria = Criteria::orderBy('id')->get()->toArray();
        $ratio = RatioCriteriaController::data();
//        Log::debug($ratio);
//        self::getRatios();

        $data = (object)[
            'matrix' => $matrix,
            'eigen' => $eigen,
            'lamda' => $lamda,
            'criteria' => $criteria,
            'ratio' => $ratio,
//            'ratioCriteria' => self::getRatios()
        ];

        return $data;
    }

    /**
     * Show the data from resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function data()
    {
        $data = Ratio_criteria::join('criterias as v_criterias', 'ratio_criterias.v_criteria_id', '=', 'v_criterias.id')
            ->join('criterias as h_criterias', 'ratio_criterias.h_criteria_id', '=', 'h_criterias.id')
            ->select('ratio_criterias.value', 'v_criterias.name as v_name', 'h_criterias.name as h_name', 'v_criterias.id as v_id', 'h_criterias.id as h_id')
            ->orderBy('v_name', 'ASC')->get()->toArray();

        return $data;
    }


    /**
     * Display the matrix resource.
     *
     * @return array
     */
    public static function showCriteria()
    {
        $criteria = Criteria::orderBy('id')->get();
        $matrix = array();
        $eigen = array();
        foreach ($criteria as $matrixColumn) {
            $column = $matrixColumn['id'];
            $nameColumn = $matrixColumn['name'];
            $sumCol = 0;
            $validate_exist = Ratio_criteria::Where('v_criteria_id', $column)
                ->orWhere('h_criteria_id', $column)->count();
            if ($validate_exist < 1) {
                continue;
            }
            foreach ($criteria as $matrixRow) {
                $row = $matrixRow['id'];
                $nameRow = $matrixRow['name'];
                $dataRatio = Ratio_criteria::where('v_criteria_id', $column)
                    ->where('h_criteria_id', $row);

                if ($column == $row) {
                    $value = 1;
                } else if ($dataRatio->count() == 0) {
                    continue;
                }

                if ($column != $row) {
                    $value = $dataRatio->select('value')->first();
                    $value = $value->value;
                }
                $matrix[$nameRow][$nameColumn] = $value;
            }
        }
        foreach ($matrix as $columnName => $columnVal) {
            $devider = self::sumMatrix($columnVal);

            foreach ($columnVal as $valueName => $valueMatrix) {
                $count = $valueMatrix / (int)$devider;
                $eigen[$columnName][$valueName] = $count;
            }
            $matrix[$columnName] = array_merge($columnVal, array('sumCol' => $devider));
        }
        return self::reverseMatrix($matrix);
    }

    /**
     * Count Row array data
     *
     * @param  Array
     * @return Array
     */
    public static function eigen($array): array
    {
        $data = array();

        foreach (self::reverseMatrix($array) as $key => $value) {
            $sumEigen = 0;
            $devieder = $value['sumCol'];
            foreach ($value as $name => $eigenVal) {
                if ($name == 'sumCol') {
                    continue;
                }
                $counted = $eigenVal / $devieder;
                $data[$key][$name] = $counted;
                $sumEigen += $counted;
            }
            $data[$key]['sumEigen'] = $sumEigen;
        }

        foreach (self::reverseMatrix($data) as $columnName => $columnVal) {
            $devider = self::sumMatrix($columnVal);

            $data[$columnName] = array_merge($columnVal, array('totalEigen' => $devider));
        }

        return $data;
    }

    /**
     * Count Row array data
     *
     * @param  Array
     * @return interger
     */
    public static function sumMatrix($array)
    {
        $total = 0;
        foreach ($array as $key => $value) {
            $total += $value;
        }

        return $total;
    }


    /**
     * Reverse array data
     *
     * @param  Array
     * @return Array
     */
    public static function reverseMatrix($array)
    {
        $newArray = array();
        foreach ($array as $keyCol => $data) {
            foreach ($data as $keyRow => $value) {
                $newArray[$keyRow][$keyCol] = $value;
            }
        }

        return $newArray;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ratio_criteria $ratio_criteria
     * @return \Illuminate\Http\Response
     */
    public function destroy($v_id, $h_id)
    {

        $ratio = Ratio_criteria::where("v_criteria_id", $v_id)
            ->where("h_criteria_id", $h_id)->first();
        $reverseratio = Ratio_criteria::where("v_criteria_id", $h_id)
            ->where("h_criteria_id", $v_id)->first();

        $ratio->delete();
        $reverseratio->delete();

        return redirect()->back()->with(["message" => "delet data perbanfdingan " . $ratio->value . " dan " . $reverseratio->value]);
    }

    /**
     * Get the value of Ratio
     */
    public function getRatio($car1, $car2)
    {
        $ratio = Ratio_criteria::where("v_criteria_id", $car1)
            ->where("h_criteria_id", $car2)->first();

        if ($ratio)
            return $ratio->value;

        return "";
    }

    public function getRatios()
    {
        $criteria = Criteria::orderBy('id')->get()->toArray();
        $data = array();

        foreach ($criteria as $c1 => $vcl) {
            $temp = array();
            foreach ($criteria as $c2) {
                $ratio = self::getRatio($c1, $c2);
                array_push($temp, $ratio);
            }
            array_push($data, $temp);
        }

//        Log::debug($data);
        return $data;
    }

    /**
     * Get the total of each Ratio
     */
    public function getTotalRatio($criteria, $cat)
    {
        Log::debug("------cat " . $cat);
        $jumlah = 0;
        foreach ($criteria as $key => $val) {
            $ratio = Ratio_criteria::where("v_criteria_id", $val['id'])
                ->where("h_criteria_id", $cat)->first();

            Log::debug(json_encode($ratio));

            if (!$ratio) {
                return "Data Belum Lengkap";
            } else {
                $r = $ratio->value;
                $jumlah = $jumlah + $r;
            }
        }
        return $jumlah;
    }
}
