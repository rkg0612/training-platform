<?php

namespace App\Services\LMS\MarkAsCompleted;

class MarkAsCompletedService
{
    public $lookup;

    public function __construct()
    {
        $this->lookup = [
            'assigned' => new AssignedPlaylistUnitMarkAsCompletedService,
            'playlist' => new AssignedPlaylistUnitMarkAsCompletedService,
            'shared' => new UserUnitMarkAsCompletedService,
            'owner' => new UserUnitMarkAsCompletedService,
            'default' => new UserUnitMarkAsCompletedService,
            'single' => new UserUnitMarkAsCompletedService,
        ];
    }

    /**
     * param contains type, playlist id, unit id.
     * @param $request
     * @return mixed
     */
    public function markAsCompleted($request)
    {
        if (! isset($request['type']) || $request['type'] === null) {
            $request['type'] = 'default';
        }

        $type = is_array($request['type']) ? $request['type'][0] : $request['type'];

        return $this->lookup[$type]->markAsCompleted($request);
    }

    public function isCompleted($request)
    {
        if (! isset($request['type']) || $request['type'] === null) {
            $request['type'] = 'default';
        }

        return $this->lookup[$request['type']]->isCompleted($request);
    }
}
