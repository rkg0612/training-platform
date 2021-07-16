<?php

namespace App\Services;

use App\Models\PhoneNumberSMSLog;

class SmsService
{
    public function destroy($id)
    {
        try {
            $sms = PhoneNumberSMSLog::findOrFail($id);
            $sms->delete();

            return response('success');
        } catch (\Exception $e) {
            return response($e->getMessage());
        }
    }
}
