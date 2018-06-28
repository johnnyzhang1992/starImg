<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    //
    use Notifiable;

    protected $table = 'star';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','domain','name','description','avatar','gender','follow_count','status',
        'created_at','updated_at','country','profession','baike','en_name','birthday'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

}
