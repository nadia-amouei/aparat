<?php


namespace App\Services;


use App\Http\Requests\channel\ChannelStatisticRequest;
use App\Http\Requests\channel\UpdateChannelRequest;
use App\Http\Requests\channel\UpdateSocialsRequest;
use App\Http\Requests\channel\UploadBannerForChannelRequest;
use App\Models\Channel;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChannelService  extends BaseService
{

    public static function UpdateChannelService(UpdateChannelRequest $request)
    {
        try {
            //TODO check if its admin to update others user channel
            if ( $channelId = $request->route('id')){
                $channel = Channel::findOrFail($channelId);
                $user = $channel->user;
            }else{
                $user = auth()->user();
                $channel = $user->channel;
            }

            DB::beginTransaction();
            $channel->name = $request->name ;
            $channel->info = $request->info ;
            $channel->save();

            $user->website = $request->website;
            $user->save();
            DB::commit();
            return response(['message'=>'تغییرات کانال انجام شد.'],200);
        }catch (\Exception $e){
           DB::rollBack();
           Log::error($e);
           return response(['message'=>'خطایی رخ داده است!'],500);
        }
    }

    public static function UploadBannerForChannelService(UploadBannerForChannelRequest $request)
    {
        try {
            $banner = $request->file('banner');
            $fileName = md5(auth()->id()) . '-'. Str::random(10);
            $name = $banner->move(public_path('channel-banner'),$fileName);

            $channel = auth()->user()->channel;
            if ($channel->banner){
                unlink(public_path($channel->banner));//this line remove old picture
            }
            $channel->banner = 'channel-banner/' . $fileName;
            $channel->save();

            return response([
                'banner'=> url('channel-banner/' . $fileName)
            ],200);
        }catch (\Exception $e){
            return response(['message'=>'خطایی رخ داده است!'],500);
        }
    }

    public static function UpdateSocials(UpdateSocialsRequest $request)
    {
        try {
            $socials = [
                'facebook' => $request->input('facebook'),
                'twitter' => $request->input('twitter'),
                'instagram' => $request->input('instagram'),
                'telegram'=> $request->input('telegram'),
            ];
            auth()->user()->channel->update(['social'=>$socials]);

            return response(['message'=>'با موفقعیت ثبت شد'],200);
        }catch (\Exception $e){
            Log::error($e);
            return response(['message'=>'خطایی رخ داد'],500);
        }
    }

    public static function ShowStatistic(ChannelStatisticRequest $request)
    {
        $fromDate = now()->subDays($request->get('last_n_days' ,7 ))->toDateString();
        $data = [
            'views'=>[],
            'total_views'=>0,
            'total_followers'=> $request->user()->followers()->count(),
            'total_videos'=> $request->user()->channelVideos()->count(),
            'total_comments'=> Video::channelComments($request->user()->id)
                ->selectRaw('comments.*')
                ->count(),//TODO get unaccepted comment
        ];
        Video::views($request->user()->id)
            ->whereRaw("date(video_views.created_at) >= '{$fromDate}' ")
            ->selectRaw('date(video_views.created_at) as date , count(*) as views')
            ->groupBy(DB::raw('date(video_views.created_at)'))
            ->each(function ($item) use (&$data){
                $data['total_views'] += $item->views;
                $data['views'][$item->date] = $item->views;
            });
        return $data;
    }


}
