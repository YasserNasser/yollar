<?php

namespace App\Models;



use App\Models\Lists;
use App\Models\Comment;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Card extends Model  {


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'cards';
    protected $fillable = [
        'name','lists_id','description','priority','user_id'
    ];

    public function list()
    {
        return $this->belongsTo(Lists::class,'lists_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class,'card_id');
    }

}
