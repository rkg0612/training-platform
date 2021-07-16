<?php

namespace App\Services;

use App\Call;

class CallService
{
    public function destroy($id)
    {
        try {
            Call::destroy($id);
        } catch (\Exception $e) {
        }

        return response('success');
    }
}
