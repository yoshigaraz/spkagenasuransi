<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\Data_criteria;
use App\Models\Employe;
use App\Models\Ratio_alternative;
use App\Models\Ratio_criteria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Symfony\Component\VarDumper\Cloner\Data;
use Illuminate\Database\Eloquent\Collection;

class RankController extends Controller
{
    public function index()
    {
        $period = Session::get('period');
        Log::debug("period : " . $period);
        $criteria = Criteria::orderBy('id')->get();
//        $conventional = $this->conventional($period);
        $saw = $this->saw($period);
        $ahp = $this->ahp($period);

        $period_year = substr($period,0,4);
        $period_month = substr($period,5,2);

//        Log::debug('period : ' . $period);
//        Log::debug(substr($period,5,2));
//        Log::debug(json_encode($saw));

        return view('pages.rank', [
            'period' => $period,
            'period_year' => $period_year,
            'period_month' => $period_month,
//            'conventional' => $conventional,
            'saw' => $saw,
            'ahp' => $ahp,
            'criteria' => $criteria
        ]);
    }

    public function getRank(Request $request)
    {
        $period =  $request->year.'-'.$request->month;
        return Redirect::route('rank')->with(['period' => $period]);
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

    /**
     * ini fungsi untuk get data kriterianya pegawai yang di join dengan table ref kriteria
     * dan table kriteria
     * @param $employe
     * @param $period
     * @return data
     */
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

    /**
     * $data variable untuk get data criteria dengan id pegawai dan periode nya
     * setelah datanya ada, maka data akan di looping satu persatu.
     * jika kriteria id nya itu 1 maka value pegawainya akan di bagi 5000000
     * jika kriteria id nya itu 2 maka value pegawainya akan di bagi 10000000
     *
     * jika kriteria id nya itu 4 maka akan di cek lagi :
     *  a. jika nilai pegawai dibawah 50 maka akan di berikan point 1
     *  b. jika nilai pegawai diantara 51 sampai 70 maka akan diberikan point 2
     *  c. jika nilai pegawai diantara 71 sampai 85 maka akan diberikan point 3
     *  d. jika nilai pegawai diatas 85 maka akan diberikan point 5
     *  e. jika nilai nya 0 maka akan diberikan point 0
     *
     * jika kriteria id nya itu selain 1,2, dan 4 maka diberikan point sesuai nilainya
     *
     * @param $employe
     * @param $period
     *
     * @return data
     */
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

    /**
     * ini  perhitungan saw
     * $employe itu ambil data pegawai dari fungsi getEmploye diatas
     * kemudian di looping satu persatu
     * lalu pada $alternatives akan di panggil fungsi getAlternative yg ada dibawah fungsi ini
     * lalu data pegawai yg telah di join dengan referensi alternatif akan di looping
     * lalu akan dimulai perhitungan metode SAW dengan mempertimbangkan nilai max weight
     *
     * @param $period
     * @return hasil yg telah di urutkan total point nya dari tertinggi ke rendah
     */
    private function saw($period)
    {
        $employe = $this->getEmploye($period);

        foreach ($employe as $e) {
            $alternatives = self::getAlternative($e->id, $period);
            $totalPoint = 0;
            foreach ($alternatives as $alternative) {
                // ambil nilai tertinggi weight
                $maxWeight = Alternative::where('criteria_id', $alternative->criteria_id)->max('weight');
                // jika nilai weight nda ada, maka benefitnya 0. kalau ada nilai tertingginya maka
                // dibagi nilai weight nya dengan nilai maxWeightnya untuk mendapatkan benefitnya
                if ($maxWeight == null) {
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
//        Log::debug(json_encode($sorted));

        return $sorted;
    }

    /**
     * @param $employe
     * @param $period
     * @return data join dengan referensi alternatives
     */
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
//        Log::debug(json_encode($sorted));

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

    public function printConventional($period)
    {
        $data = [
            'period' => $period,
            'criteria' => Criteria::orderBy('id')->get(),
            'conventional' => $this->conventional($period)
        ];
        $pdf = Pdf::loadView('pdf.conventional', $data);
        return $pdf->stream();
    }

    public function printSaw($period)
    {
        $criteria = Criteria::orderBy('id')->get();
        foreach ($criteria as $crit) {
            $alt = Alternative::where('criteria_id', $crit->id)->orderBy('id')->get();
            $crit->alternative = $alt;
        }

        $data = [
            'period' => $period,
            'criteria' => $criteria,
            'saw' => $this->saw($period)
        ];
        $pdf = Pdf::loadView('pdf.saw', $data);
        return $pdf->stream();
    }

    public function printAhp($period)
    {
        $matrixCriteria = RatioCriteriaController::showCriteria();
        $eigenCriteria = RatioCriteriaController::eigen($matrixCriteria);
        $sumCol = 0;
        if ($matrixCriteria)
            $sumCol = $matrixCriteria['sumCol'];

        $lamdaCriteria = RatioCriteriaController::lamda($sumCol, $eigenCriteria);

        $criteria = Criteria::orderBy('id')->get();
        foreach ($criteria as $c) {
            $c->alternative = Alternative::where('criteria_id', $c->id)->orderBy('id')->get();

            $dataMatrix = RatioAlternativeController::showAlternative($c->id);
            $c->matrix = $dataMatrix;
            $eigen = RatioAlternativeController::eigen($dataMatrix);
            $c->eigen = $eigen;
            $sumCol = 0;
            if ($dataMatrix)
                $sumCol = $dataMatrix['sumCol'];

            $lamda = RatioAlternativeController::lamda($sumCol, $eigen);
            $c->lamda = $lamda;
        }

        $data = [
            'period' => $period,
            'criteria' => Criteria::orderBy('id')->get(),
            'ahp' => $this->ahp($period),
            'matrixCriteria' => $matrixCriteria,
            'eigenCriteria' => $eigenCriteria,
            'lamdaCriteria' => $lamdaCriteria,
            'alternative' => $criteria
        ];
        $pdf = Pdf::loadView('pdf.ahp', $data);
        return $pdf->stream();
    }
}
