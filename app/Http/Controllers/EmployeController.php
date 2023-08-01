<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Data_criteria;
use App\Models\Employe;
use App\Models\Position;
use App\Models\Ratio_alternative;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return view
     */
    public function index()
    {
        $data = (object)[
            'employe' => Employe::join('positions', 'employes.position_id', '=', 'positions.id')
                        ->select('employes.*', 'positions.name as position')
                ->orderBy('employes.name')
                ->get(),
            'position' => Position::all(),
        ];

        return view('pages.employe')->with('data', $data);
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
            'name' => 'required|string',
            'position' => 'required',
             'code' => 'required|unique:employes',
            // 'gender' => 'required',
            // 'date' => 'required',
        ]);

        Employe::create([
            'name' => $request->name,
            'position_id' => $request->position,
             'code' => $request->code,
            // 'gender' => $request->gender,
            // 'date_in' => $request->date,
        ]);

        // User::create([
        //     'name' => $request->name,
        //     'password' => Hash::make('password'),
        // ]);

        return redirect()->back()->with('message' , 'Insert Data Agen Success');
    }

//    public function storeCriteria(Request $request)
//    {
//        $request->validate([
//            'name' => 'required|string',
//        ]);
//
//        Criteria::create([
//            'name' => $request->name,
//        ]);
//
//        return redirect()->back()->with('message' , 'Insert Data Karyawan Success');
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employe  $employe
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $karyawan = Employe::find($request->id);
        if($karyawan){
            $karyawan->update([
                'name' => $request->name,
//                'position_id' => $request->position,
                 'code' => $request->code,
                // 'gender' => $request->gender,
                // 'date_in' => $request->date,
                ]);
        }

        return redirect()->back()->with(["message" => $request->name]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employe  $employe
     * @return Redirect
     */
    public function destroy(Employe $employe)
    {
        $existance = Data_criteria::where('employe_id', $employe->id)
                                      ->count();
        if ($existance > 1){
            return redirect()->back()->with(["message" => "Info : Karyawan memiliki relasi perbandingan!"]);
        }else {
            $employe->delete();
            return redirect()->back()->with(["message" => "Delete Data sukses"]);
        }
    }
}
