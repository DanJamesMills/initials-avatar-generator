<?php

Route::group(['prefix' => 'api/v1', 'middleware' => 'web', 'namespace' => 'DanJamesMills\InitialsAvatarGenerator\Http\Controllers\Api'], function () {
    Route::post('avatar', 'AvatarUploaderApiController@store');
    Route::delete('avatar', 'AvatarUploaderApiController@destroy');
});
