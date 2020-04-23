<?php

namespace App\Models;



use App\User;
use App\Models\Lists;
use Illuminate\Database\Eloquent\Model;

class Board extends Model  {


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','user_id'
    ];

     public function user()
     {
         return $this->belongsTo(User::class);
     }
    public function lists()
    {
        return $this->hasMany(Lists::class);
    }

}
