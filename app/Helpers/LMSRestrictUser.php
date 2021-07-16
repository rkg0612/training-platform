<?php

namespace App\Helpers;

use App\Models\Module;
use App\Models\Role;
use App\Models\Unit;
use App\Models\User;

class LMSRestrictUser
{
    private $user;
    private $contentId;
    private $contentType;

    public function __construct($user, $contentId, $contentType)
    {
        $this->user = User::with(['roles'])->find($user);
        $this->contentId = $contentId;
        $this->contentType = $contentType;
    }

    public static function make($dealerId, $contentId, $contentType)
    {
        return new self($dealerId, $contentId, $contentType);
    }

    public function getDealersList(): array
    {
        switch ($this->contentType) {
            case 'unit':
                return Unit::find($this->contentId)
                    ->module->category->course->dealers->pluck('id')->toArray();
            case 'module':
                return Module::find($this->contentId)
                    ->category->course->dealers->pluck('id')->toArray();
            default:
                return [];
        }
    }

    public function isContentNotAccessible(): bool
    {
        if ($this->user->roles->contains('name', Role::SUPER_ADMINISTRATOR)) {
            return false;
        }

        $dealersList = $this->getDealersList();

        if (in_array($this->user->dealer_id, $dealersList)) {
            return false;
        }

        return true;
    }
}
