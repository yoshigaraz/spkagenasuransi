<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name','code','weight','attribute'
    ];

    public static function getIdfromName($name){
        $data = Criteria::where('name', '=', $name)->first();
        return $data->id;
    }
}
