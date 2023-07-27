<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\Employe;
use App\Models\Payout;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($whereYear = null, $whereMonth = null)
    {
        $rank = RankController::show();
        $payout = Payout::whereYear('period',  Carbon::now()->format('Y'))
            ->whereMonth('period', Carbon::now()->format('m'))
            ->leftjoin('bonuses' , 'bonuses.id', 'bonus_id' )
            ->join('employes' , 'employes.id', 'employe_id' )
            ->leftjoin('positions', 'positions.id', 'employes.position_id')
            ->select('payouts.*', 'employes.name as name', 'bonuses.name as bonus_name', 'bonuses.value as bonus_value', 'positions.name as position')
            ->get()->toArray();

        $bonus = Bonus::all()->toArray();
        $employe = Employe::all()->toArray();
        $data = (object)[
            'bonus' => $bonus,
            'payout' => $payout,
            'employe' => $employe,
            'rank' => $rank,
            'date' => null
        ];
        // dd($data);
        return view('pages.payout')->with('data', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $date = $request->date;
        $month = date("m", strtotime($date));
        $Year = date("Y", strtotime($date));

        $rank = RankController::show();
        $payout = Payout::whereYear('period',  $Year)
            ->whereMonth('period', $month)
            ->leftjoin('bonuses' , 'bonuses.id', 'bonus_id' )
            ->join('employes' , 'employes.id', 'employe_id' )
            ->leftjoin('positions', 'positions.id', 'employes.position_id')
            ->select('payouts.*', 'employes.name as name', 'bonuses.name as bonus_name', 'bonuses.value as bonus_value', 'positions.name as position')
            ->get()->toArray();

        $bonus = Bonus::all()->toArray();
        $employe = Employe::all()->toArray();
        $data = (object)[
            'bonus' => $bonus,
            'payout' => $payout,
            'employe' => $employe,
            'rank' => $rank,
            'date' => $date
        ];
        // dd($data);
        return view('pages.payout')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'period'=> 'required',
            'employe_id' => 'required',
            'value' => 'required',
        ]);

        Payout::create([
            'period' => $request->period,
            'employe_id' => $request->employe_id,
            'bonus_id' => $request->bonus_id,
            'value' => $request->value,
        ]);
        return redirect()->back()->with(["message" => "Tambah Data Berhasil"]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payout  $payout
     * @return Redirect Back
     */
    public function update(Request $request)
    {
        $data = Payout::where('id', $request->id)->first();
        if($data->count() < 1){
            return redirect()->back()->with(["message" => "Data Bermasalah"]);
        }

        $data->value = $request->value;
        $data->bonus_id = $request->bonus;
        $data->update();


        return redirect()->back()->with(["message" => "Update Data Berhasil"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payout  $payout
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $payout = Payout::where('id', $id)->first();
        $payout->delete();
        return redirect()->back()->with(["message" => "Delete Data sukses"]);

    }
}
