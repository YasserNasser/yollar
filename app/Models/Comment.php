<?php

namespace App\Models;



use App\Models\Card;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model  {


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'comments';
    protected $fillable = [
        'description','card_id','user_id'
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
