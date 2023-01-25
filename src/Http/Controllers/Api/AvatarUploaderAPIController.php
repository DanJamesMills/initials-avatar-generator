<?php

namespace DanJamesMills\InitialsAvatarGenerator\Http\Controllers\Api;

use Illuminate\Http\Request;
use DanJamesMills\InitialsAvatarGenerator\AvatarUploader;
use DanJamesMills\LaravelResponse\Http\Controllers\BaseController;
use Response;

class AvatarUploaderAPIController extends BaseController
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
        try {
            $avatarUploader = new AvatarUploader();

            $record = $this->getModelClass($request->model, $request->id);

            $avatarFileName = $avatarUploader->handle($request->file);

            $record->forceFill([$record->getAvatarField() => $avatarFileName])->save();
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

        return $this->sendResponse(['file' => $record->{$record->getAvatarField()}], 'Avatar has been uploaded and saved. successfully');
    }

    /**
     * Remove the avatar and set back to default.
     * DELETE /avatar.
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Request $request)
    {
        $record = $this->getModelClass($request->model, $request->id);
        $record->generateAvatarAndSet();
        $record->save();

        return $this->sendResponse(['file' => $record->{$record->getAvatarField()}], 'Avatar has been uploaded and saved. successfully');
    }

    private function getModelClass($model, $id = null)
    {
        if (config()->has('initials-avatar-generator.models.' . $model)) {
            return (config('initials-avatar-generator.models.' . $model))::findOrFail($id);
        }

        throw new \Exception('No model exists in config.');
    }
}
