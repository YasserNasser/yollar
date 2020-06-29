<?php

namespace App\Models;



use App\Models\Board;
use App\Models\Card;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class Lists extends Model  {


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'lists';
    protected $fillable = [
        'name','board_id'
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
    public function cards()
    {
        return $this->hasMany(Card::class);
    }
    public function comments(){
        return $this ->hasManyThrough(Comment::class,Card::class,'lists_id','card_id','id','id');
    }
}
