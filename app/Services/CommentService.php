<?php


namespace App\Services;

use App\Http\Requests\comment\changeCommentStateRequest;
use App\Http\Requests\comment\createCommentsRequest;
use App\Http\Requests\comment\deleteCommentRequest;
use App\Http\Requests\comment\getVideoCommentsRequest;
use App\Http\Requests\comment\ListCommentsRequest;
use App\Models\Comment;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentService  extends BaseService
{
    public static function getComments(ListCommentsRequest $request)
    {
        $comments = Comment::channelComments($request->user()->id);
        if ($request->has('state')){
            $comments = $comments->where('comments.state',$request->state);
        }
        return $comments->get();
    }

    public static function create(createCommentsRequest $request)
    {

        $user_id = auth()->id();
        $video = Video::find($request->video_id);
        $comment = $request->user()->comments()->create([
            'video_id'=>$request->video_id,
            'parent_id'=>$request->parent_id,
            'body'=>$request->body,
            'state'=> $video->user_id == $user_id ?
                Comment::STATE_ACCEPTED :
                Comment::STATE_PENDING
        ]);
        return $comment;
    }

    public static function changeState(changeCommentStateRequest $request)
    {
        $comment = $request->comment;
        $comment->state = $request->state;
        $comment->save();
        return response(['message'=>'وضعیت با موفقیت صورت گرفت'],200);
    }

    public static function delete(deleteCommentRequest $request)
    {
        try{
            DB::beginTransaction();
            $request->comment->delete();
            DB::commit();
            return response(['message'=>'حذف دیدگاه با موفقعیت انجام شد'],200);
        }catch (\Exception $e){
            DB::rollBack();
            Log::error($e);
            return response(['message'=>'حذف امکان پذیر نشد، مجددا تلاش کنید'],500);
        }
    }

    public static function videoCommentsService(getVideoCommentsRequest $request)
    {
        return $request->video->comments;
    }

}
