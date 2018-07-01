<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'created_at','updated_at','country','profession','baike','en_name','birthday','status','wb_id',
        'ins_id','wb_domain','ins_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public static function create($star){
        $star_id = DB::table('star')->insertGetId($star);
        return $star_id;
    }
}
