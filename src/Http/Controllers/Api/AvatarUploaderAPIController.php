<?php

namespace DanJamesMills\InitialsAvatarGenerator\Api;

use Illuminate\Http\Request;
use DanJamesMills\InitialsAvatarGenerator\AvatarUploader;
use DanJamesMills\SettingsUi\Http\Controllers\AppBaseController;
use Response;

class AvatarUploaderAPIController extends AppBaseController
{
    /**
     * Store a newly created avatar in storage.
     * POST /avatar
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $avatarUploader = new AvatarUploader();

        $avatarUploader->handle($request->file);

        // $request->user()
        //     ->fill(['avatar' => $newFileName])
        //     ->save();

        return $this->sendResponse(['file' => $request->user()->avatar], 'Avatar has been uploaded and saved. successfully');
    }
}
