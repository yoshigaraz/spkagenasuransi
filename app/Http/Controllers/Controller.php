<?php

namespace App\Http\Controllers;

use App\Models\Data_criteria;
use App\Models\Employe;
use App\Models\Payout;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        $karyawan = Employe::all();
        $user     = User::all();
        $data = (object)[
            'user' => $user,
            'karyawan' => $karyawan,
        ];

        return view('pages.UserList')->with('data' , $data);
    }

    public function dashboard()
    {
        $user = Auth::user();
        if ($user->is_admin) {
            return view('dashboard');
        }
        $karyawan = Employe::where('name', $user->name)->first();
        $data_criteria = Data_criteria::where('employe_id', $karyawan->id)
                                        ->leftJoin('criterias', 'criterias.id', '=', 'data_criterias.criteria_id')
                                        ->select('data_criterias.*', 'criterias.name')
                                        ->get();
        $payout = Payout::where('employe_id', $karyawan->id)
            ->leftjoin('bonuses', 'bonuses.id', 'bonus_id')
            ->join('employes', 'employes.id', 'employe_id')
            ->leftjoin('positions', 'positions.id', 'employes.position_id')
            ->select('payouts.*', 'employes.name as name', 'bonuses.name as bonus_name', 'bonuses.value as bonus_value')
            ->get();
            // dd($payout);
        try {
            $rank = RankController::show();
        } catch (\Throwable $th) {
//            $rank = null;
            $rank = [];
        }
        $data = (object)[
            'user' => $user,
            'karyawan' => $karyawan,
            'target' => $data_criteria,
            'rank' => $rank,
            'payout' => $payout,
        ];

        return view('dashboard')->with('data' , $data);
    }


    public function save(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        User::updateOrCreate(
            ['name' => $request->name],
            [
                'password' => Hash::make($request->password),
                'is_admin' => $request->role == 'true' ? 1 : 0,
            ]
            );

        return redirect()->back()->with('message', 'Record Data Success');

    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->back()->with('message', 'Delete User Success');
    }

    public function print($date)
    {
        $altAlternative = RatioAlternativeController::showAlternative();
        $rank = RankController::show();

        $employe = Payout::whereYear('period',  date("Y", strtotime($date)))
        ->whereMonth('period', date("m", strtotime($date)))
        ->leftjoin('bonuses', 'bonuses.id', 'bonus_id')
        ->join('employes', 'employes.id', 'employe_id')
        ->leftjoin('positions', 'positions.id', 'employes.position_id')
        ->select('payouts.*', 'employes.*', 'bonuses.name as bonus_name', 'bonuses.value as bonus_value', 'positions.name as position')
        ->get();

        $data=(object)[
            'date' => date("m-Y", strtotime($date)),
            'employe' => $employe,
            'eigen' => $altAlternative
        ];
        // dd($data);
        return view('layouts.print')->with('data', $data);
    }

}
