<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Data_criteria;
use App\Models\Employe;
use App\Models\Ratio_alternative;
use App\Models\Ratio_criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Symfony\Component\VarDumper\Cloner\Data;

class RankController extends Controller
{
    public function index()
    {
        $period = Session::get('period');
        $criteria = Criteria::orderBy('id')->get();
        $conventional = $this->conventional($period);
        $saw = $this->saw($period);
        $ahp = $this->ahp($period);
//        Log::debug(json_encode($ahp));

        return view('pages.rank', [
            'period' => $period,
            'conventional' => $conventional,
            'saw' => $saw,
            'ahp' => $ahp,
            'criteria' => $criteria
        ]);
    }

    public function getRank(Request $request)
    {
        return Redirect::route('rank')->with(['period' => $request->period]);
    }

    private function getEmploye($period)
    {
        $employe = Employe::join('data_criterias', 'data_criterias.employe_id', '=', 'employes.id')
            ->where('data_criterias.period', $period)
            ->select([
                'employes.*'
            ])
            ->distinct()
            ->get();

        return $employe;
    }

    private function conventional($period)
    {
        $employe = $this->getEmploye($period);

        foreach ($employe as $e) {
            $points = self::getConventionalPoint($e->id, $period);
            $total = 0;
            foreach ($points as $p) {
                $total += $p->point;
            }
            $e->points = $points;
            $e->total = $total;
        }

        $sorted = $employe->sortByDesc('total');

        return $sorted;

//        Log::debug(json_encode($employe));
    }

    private static function getDataCriteria($employe, $period)
    {
        $data = Data_criteria::join('criterias', 'criterias.id', '=', 'data_criterias.criteria_id')
            ->where('data_criterias.period', $period)
            ->where('data_criterias.employe_id', $employe)
            ->select([
                'data_criterias.*',
                'criterias.name AS criteria'
            ])
            ->orderBy('data_criterias.criteria_id')
            ->get();

        return $data;
    }

    private static function getConventionalPoint($employe, $period)
    {
        $data = self::getDataCriteria($employe, $period);

        foreach ($data as $d) {
            //hardcode
            if ($d->criteria_id == 1) {
                $d->point = round($d->value / 5000000);
            } elseif ($d->criteria_id == 2) {
                $d->point = round($d->value / 10000000);
            } elseif ($d->criteria_id == 4) {
                if ($d->value <= 50) {
                    $d->point = 1;
                } elseif ($d->value >= 51 && $d->value <= 70) {
                    $d->point = 2;
                } elseif ($d->value >= 71 && $d->value < 85) {
                    $d->point = 3;
                } elseif ($d->value >= 85) {
                    $d->point = 5;
                } else {
                    $d->point = 0;
                }
            } else {
                $d->point = $d->value;
            }
        }

        return $data;
    }

    private function saw($period)
    {
        $employe = $this->getEmploye($period);

        foreach ($employe as $e) {
            $alternatives = self::getAlternative($e->id, $period);
            $totalPoint = 0;
            foreach ($alternatives as $alternative) {
                $maxWeight = Alternative::where('criteria_id',$alternative->criteria_id)->max('weight');
                if ($maxWeight == null){
                    $benefit = 0;
                } else {
                    $benefit = $alternative->weight / $maxWeight;
                }
                $point = $benefit * $alternative->criteria_weight;
                $alternative->benefit = $benefit;
                $alternative->point = $point;
                $totalPoint += $point;
            }
            $e->alternative = $alternatives;
            $e->total_point = $totalPoint;
        }

        $sorted = $employe->sortByDesc('total_point');

        return $sorted;
    }

    private function getAlternative($employe, $period)
    {
        $data = Data_criteria::join('criterias', 'criterias.id', '=', 'data_criterias.criteria_id')
            ->join('alternatives', 'data_criterias.criteria_id', '=', 'alternatives.criteria_id')
            ->select([
                'data_criterias.*',
                'criterias.name AS criteria',
                'criterias.weight AS criteria_weight',
                'alternatives.id AS alternative_id',
                'alternatives.description AS alternative',
                'alternatives.weight'
            ])
            ->where('period', $period)
            ->where('employe_id', $employe)
//            ->whereBetween('data_criterias.value',['alternatives.min_value','alternatives.max_value'])
            ->whereRaw('data_criterias.value BETWEEN alternatives.min_value AND alternatives.max_value')
            ->orderBy('data_criterias.criteria_id');

//        DB::enableQueryLog();
//        Log::debug(DB::getQueryLog());

        return $data->get();
    }

    private function ahp($period)
    {
        $employe = $this->getEmploye($period);
        $matrix = RatioCriteriaController::showCriteria();
        $eigen = RatioCriteriaController::eigen($matrix);
//        Log::debug(json_encode($eigen));

        foreach ($employe as $e) {
            $alternatives = self::getAlternative($e->id, $period);
            $totalPoint = 0;
            foreach ($alternatives as $a) {
                //get eigen criteria
                $totEigen = 0;
                $divider = 1;
                foreach ($eigen as $key => $value) {
                    if ($key == $a->criteria) {
                        $totEigen = $value['totalEigen'];
                    } elseif ($key == 'sumEigen') {
                        $divider = $value['totalEigen'];
                    }
                }
                $avgEigen = RatioAlternativeController::getAverage($totEigen, $divider);
                $a->criteria_eigen = $avgEigen;

                //get eigen alternative
                $altMatrix = RatioAlternativeController::showAlternative($a->criteria_id);
                $altEigen = RatioAlternativeController::eigen($altMatrix);
                $totEigenAlt = 0;
                $dividerAlt = 1;
                foreach ($altEigen as $key => $value) {
                    if ($key == $a->alternative) {
                        $totEigenAlt = $value['totalEigen'];
                    } elseif ($key == 'sumEigen') {
                        $dividerAlt = $value['totalEigen'];
                    }
                }
                $avgEigenAlt = RatioAlternativeController::getAverage($totEigenAlt, $dividerAlt);
                $a->alternative_eigen = $avgEigenAlt;
                $point = RatioAlternativeController::getMultiply($a->criteria_eigen, $a->alternative_eigen);
                $a->point = $point;

                $totalPoint = $this->getSum($totalPoint, $point);
            }

            $e->alternative = $alternatives;
            $e->total_point = $totalPoint;
        }

        $sorted = $employe->sortByDesc('total_point');

        return $sorted;
    }

    private function getSum($total, $value)
    {
        try {
            $total += $value;
        } catch (\Exception $e) {
            $total = "N/A";
        }
        return $total;
    }
}
