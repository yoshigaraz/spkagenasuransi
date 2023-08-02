<?php

namespace App\Http\Controllers;

use App\Models\Data_criteria;
use App\Models\Employe;
use App\Models\Ratio_alternative;
use App\Models\Ratio_criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class RankController extends Controller
{
    public function index()
    {
        $period = Session::get('period');
        $conventional = self::conventional($period);
//        sort($conventional);
        Log::debug(json_encode($conventional));

        return view('pages.rank', [
            'period' => $period,
            'conventional' => $conventional
        ]);
    }

    public function getRank(Request $request)
    {
        return Redirect::route('rank')->with(['period' => $request->period]);
    }

    private function conventional($period)
    {
        $employe = Employe::join('data_criterias', 'data_criterias.employe_id', '=', 'employes.id')
            ->where('data_criterias.period', $period)
            ->select([
                'employes.*'
            ])
            ->distinct()
            ->get();

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

    private static function getConventionalPoint($employe, $period)
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

        foreach ($data as $d) {
            //hardcode
            if ($d->criteria_id == 1) {
                $d->point = round($d->value / 5000000);
            } elseif ($d->criteria_id == 2) {
                $d->point = round($d->value / 10000000);
            } else {
                $d->point = $d->value;
            }
        }

        return $data;
    }
}
