<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    //region config
    use HasFactory,SoftDeletes;
    protected $table = "comments";
    protected $fillable = [	'user_id' ,	'video_id',	'parent_id' , 'body' , 'state'];

    const STATE_PENDING = 'pending';
    const STATE_ACCEPTED = 'accepted';
    const STATE_READ = 'read';
    const STATES = [
        self::STATE_PENDING,
        self::STATE_ACCEPTED,
        self::STATE_READ
    ];

    //endregion config

    //region relation
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function parent()
    {
        return $this->belongsTo(Comment::class , 'parent_id');
    }
    public function childeren()
    {
        return $this->hasMany(Comment::class ,'parent_id');
    }
    //endregion relation

    //region static method
    protected static function channelComments($userId)
    {
        return Comment::join('videos','comments.video_id','=' , 'videos.id')
            ->selectRaw('comments.*')
            ->where('videos.user_id', '=' , $userId );
    }
    //endregion static method

    //region overwrite
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($comment){
            $comment->childeren()->delete();
        });
    }
    //endregion overwrite
}
