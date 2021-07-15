<?php

Route::group(['prefix' => 'api/v1', 'middleware' => 'web', 'namespace' => 'DanJamesMills\InitialsAvatarGenerator\Api'], function () {
    Route::post('avatar', 'AvatarUploaderAPIController@store');
});
