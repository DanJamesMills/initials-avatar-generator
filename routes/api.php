<?php

Route::group(['prefix' => 'api/v1', 'middleware' => 'web', 'namespace' => 'DanJamesMills\InitialsAvatarGenerator\Http\Controllers\Api'], function () {
    Route::post('avatar', 'AvatarUploaderAPIController@store');
    Route::delete('avatar', 'AvatarUploaderAPIController@destroy');
});
