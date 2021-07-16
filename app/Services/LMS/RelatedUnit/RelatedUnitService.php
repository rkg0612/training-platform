<?php

namespace App\Services\LMS\RelatedUnit;

class RelatedUnitService
{
    public $lookup = [];

    public function __construct()
    {
        $this->lookup = [
            'single' => new SingleUnitRelatedUnitsService,
            'playlist' => new PlaylistRelatedUnitsService,
        ];
    }

    public function index($request)
    {
        return $this->lookup[$request['type']]->show($request);
    }

    public function show($request)
    {
        return $this->lookup[$request['type']]->getPage($request);
    }
}
