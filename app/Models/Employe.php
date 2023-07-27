<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'position_id',
        'address',
        'gender',
        'date_in'
    ];

    public static function getIdfromName($name)
    {
        
        $data = Employe::where('name', '=', $name)->first();
        return $data->id;
    }
}
