<?php

namespace App\Models;



use App\Models\Board;
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

}
