<?php

use Illuminate\Support\Facades\Route;

/**
 * Contain Auth routes
 *
 * */
Route::group([],function ($router){
    $router->group(['namespace'=>'\Laravel\Passport\Http\Controllers'],function ($router){
        $router->post('login',[
            'as'=>'auth.login',
            'middleware'=>['throttle'],
            'uses'=>'AccessTokenController@issueToken'
        ]);
    });
    $router->post('register',[
        'as'=>'auth.register',
        'uses'=>'App\Http\Controllers\Authcontroller@register',
    ]);
    $router->post('register-verify',[
        'as'=>'auth.register.verify',
        'uses'=>'App\Http\Controllers\Authcontroller@registerVerify',
    ]);
    $router->post('resend-verification-code',[
        'as'=>'auth.register.resnd.verification.code',
        'uses'=>'App\Http\Controllers\Authcontroller@resendVerificationCode',
    ]);
});

/**
 * User's Route
 * */
Route::group(['middleware'=>['auth:api']],function ($router){
    $router->post('change-email',[
        'as'=>'change.email',
        'uses'=>'App\Http\Controllers\UserController@changeEmail',
    ]);
    $router->post('change-email-submit',[
        'as'=>'change.email',
        'uses'=>'App\Http\Controllers\UserController@changeEmailSubmit'
    ]);
    $router->post('/change-password',[
        'as'=>'password.change',
        'uses'=>'App\Http\Controllers\UserController@changePassword'
    ]);
    // User's
    Route::group(['prefix'=>'user'],function ($router){
        $router->match(['post','get'],'/{channel}/follow',[
            'as'=>'user.follow',
            'uses'=>'App\Http\Controllers\UserController@follow'
        ]);
        $router->match(['post','get'],'/{channel}/unfollow',[
            'as'=>'user.unfollow',
            'uses'=>'App\Http\Controllers\UserController@unfollow'
        ]);
        $router->get('/followings',[
            'as'=>'user.followings',
            'uses'=>'App\Http\Controllers\UserController@followings',
        ]);
        $router->get('/followers',[
            'as'=>'user.followers',
            'uses'=>'App\Http\Controllers\UserController@followers',
        ]);
        $router->delete('/me',[
            'as'=>'user.unregister',
            'uses'=>'App\Http\Controllers\UserController@unregister',
        ]);
    });
});

/**
 * channel's Route
 * */
Route::group(['middleware'=>['auth:api'],'prefix'=>'/channel'],function ($router){
    $router->put('/{id?}',[
        'as'=>'channel.update',
        'uses'=>'App\Http\Controllers\ChannelController@update'
    ]);
    $router->post('/',[
        'as'=>'channel.upload.banner',
        'uses'=>'App\Http\Controllers\ChannelController@uploadBanner'
    ]);
    $router->match(['post','put'],'/socials',[
        'as'=>'channel.update.socials',
        'uses'=>'App\Http\Controllers\ChannelController@updatesocials'
    ]);
    $router->get('/statistics',[
        'as'=>'channel.statistics',
        'uses'=>'App\Http\Controllers\ChannelController@statistics'
    ]);

});

/**
 * video's Route
 * */
Route::group(['prefix'=>'/video'],function ($router){
    $router->match(['post','put'], '/{video:slug}/like',[
        'as'=>'change.like',
        'uses'=>'App\Http\Controllers\VideoController@like'
    ]);
    $router->match(['post','put'], '/{video:slug}/unlike',[
        'as'=>'change.unlike',
        'uses'=>'App\Http\Controllers\VideoController@unlike'
    ]);
    $router->get('/',[
        'as'=>'video.list',
        'uses'=>'App\Http\Controllers\VideoController@getList'
    ]);
    $router->get('/{video:slug}/comments',[
        'as'=>'video.comments',
        'uses'=>'App\Http\Controllers\CommentController@videoComments'
    ]);
    Route::group(['middleware'=>['auth:api']],function ($router){
        $router->post('/upload',[
            'as'=>'video.upload',
            'uses'=>'App\Http\Controllers\VideoController@upload'
        ]);
        $router->post('/upload-banner',[
            'as'=>'video.upload.banner',
            'uses'=>'App\Http\Controllers\VideoController@uploadBanner'
        ]);
        $router->post('/',[
            'as'=>'video.create',
            'uses'=>'App\Http\Controllers\VideoController@create'
        ]);
        $router->put('/{video}',[
            'as'=>'video.update',
            'uses'=>'App\Http\Controllers\VideoController@update'
        ]);
        $router->put('/{video:slug}/state',[
            'as'=>'change.state',
            'uses'=>'App\Http\Controllers\VideoController@changeState'
        ]);
        $router->post('/{video:slug}/republish',[
            'as'=>'change.republish',
            'uses'=>'App\Http\Controllers\VideoController@republish'
        ]);
        $router->match(['post','get'],'/liked',[
            'as'=>'change.liked',
            'uses'=>'App\Http\Controllers\VideoController@likedByCurrentUser'
        ]);
        $router->delete('/{video}',[
            'as'=>'change.delete',
            'uses'=>'App\Http\Controllers\VideoController@delete'
        ]);
        $router->match(['post','get'], '/{video}/statistic',[
            'as'=>'change.statistic',
            'uses'=>'App\Http\Controllers\VideoController@statistics'
        ]);
        $router->get( '/favourites',[
            'as'=>'change.favourites',
            'uses'=>'App\Http\Controllers\VideoController@favourites'
        ]);
    });
    $router->get('/{video:slug}',[
        'as'=>'video.show',
        'uses'=>'App\Http\Controllers\VideoController@show'
    ]);
});

/**
 * category's Route
 * */
Route::group(['middleware'=>['auth:api'],'prefix'=>'/category'],function ($router){
    $router->get('/',[
        'as'=>'category.all',
        'uses'=>'App\Http\Controllers\CategoryController@index'
    ]);
    $router->get('/my',[
        'as'=>'category.mine',
        'uses'=>'App\Http\Controllers\CategoryController@my'
    ]);
    $router->post('/',[
        'as'=>'category.create',
        'uses'=>'App\Http\Controllers\CategoryController@create'
    ]);
    $router->post('/upload-banner',[
        'as'=>'category.upload.banner',
        'uses'=>'App\Http\Controllers\CategoryController@uploadBanner'
    ]);
});

/**
 * playlist's Route
 * */
Route::group(['middleware'=>['auth:api'],'prefix'=>'/playlist'],function ($router){
    $router->get('/',[
        'as'=>'playlist.all',
        'uses'=>'App\Http\Controllers\PlaylistController@index'
    ]);
    $router->get('/my',[
        'as'=>'playlist.all',
        'uses'=>'App\Http\Controllers\PlaylistController@my'
    ]);
    $router->get('/{playlist}',[
        'as'=>'playlist.show',
        'uses'=>'App\Http\Controllers\PlaylistController@show'
    ]);
    $router->post('/',[
        'as'=>'playlist.create',
        'uses'=>'App\Http\Controllers\PlaylistController@create'
    ]);
    $router->match(['put','post'],'/{playlist}/sort',[
        'as'=>'playlist.sort',
        'uses'=>'App\Http\Controllers\PlaylistController@sortVideos'
    ]);
    $router->match(['put','post'],'/{playlist}/{video}',[
        'as'=>'playlist.add-video',
        'uses'=>'App\Http\Controllers\PlaylistController@add_video'
    ]);

});

/**
 * tag's Route
 * */
Route::group(['middleware'=>['auth:api'],'prefix'=>'/tag'],function ($router){
    $router->get('/',[
        'as'=>'tag.all',
        'uses'=>'App\Http\Controllers\TagController@index'
    ]);
    $router->post('/',[
        'as'=>'tag.create',
        'uses'=>'App\Http\Controllers\TagController@create'
    ]);


});

/**
 * comment's Route
 * */
Route::group(['middleware'=>['auth:api'],'prefix'=>'/comment'],function ($router){
    $router->get('/',[
        'as'=>'comment.all',
        'uses'=>'App\Http\Controllers\CommentController@index'
    ]);
    $router->post('/',[
        'as'=>'comment.create',
        'uses'=>'App\Http\Controllers\CommentController@create'
    ]);
    $router->match(['post','put'],'/{comment}/state',[
        'as'=>'comment.change.state',
        'uses'=>'App\Http\Controllers\CommentController@changeState'
    ]);
    $router->delete('/{comment}',[
        'as'=>'comment.delete',
        'uses'=>'App\Http\Controllers\CommentController@delete'
    ]);
});
