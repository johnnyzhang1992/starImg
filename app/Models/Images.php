<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use DB;

class Images extends Model
{
    use Notifiable;

    protected $table = 'star_img';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'star_id', 'origin','type','attitudes_count','comments_count','reposts_count','is_long_text',
        'text','mid','code','is_video','video_url','display_url','oic_detail','take_at_timestamp','status',
        'created_at','updated_at', 'origin_url','source'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public static function getStarImageByStarId ($id) {
        return self::where('star_id', $id)
            ->where('status', 'active')
            ->paginate(10);
    }
}
