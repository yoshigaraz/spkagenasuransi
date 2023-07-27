<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\Payout;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'value' => 'required|numeric',
        ]);

        Bonus::create([
            'name' => $request->name,
            'value' => $request->value,
        ]);
        return redirect()->back()->with('message', 'Input Data Sukses');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function show(Bonus $bonus)
    {
        //
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'value' => 'required|numeric',
        ]);

        $bonus = Bonus::find($request->id);
            $bonus->name = $request->name;
            $bonus->value = $request->value;
            $bonus->save();

        return redirect()->back()->with('message', 'Input Data Sukses');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bonus = Bonus::where('id', $id)->first();
        if(Payout::where('bonus_id' , $id)->count() > 0){
            return redirect()->back()->with(["message" => "Info : Bonus Sedang digunakan!"]);
        }else {
            $bonus->delete();
            return redirect()->back()->with(["message" => "Delete Data sukses"]);
        }
    }
}
