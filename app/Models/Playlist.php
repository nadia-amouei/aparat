<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends Model
{
    use HasFactory,SoftDeletes;

    //region model config
    protected $table = 'playlists';
    protected $fillable = ['user_id','title'];
    //endregion

    //region videos
    public function videos()
    {
        return $this->belongsToMany(Video::class,'playlist_videos')
            ->orderBy('playlist_videos.id');
    }
    //endregion

    //region user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    //endregion

    public function toArray()
    {
        $data = parent::toArray();
        $data['size'] = $this->videos()->count();
        return $data;
    }
}
