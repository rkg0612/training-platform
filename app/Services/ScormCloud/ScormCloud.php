<?php

namespace App\Services\ScormCloud;

use Illuminate\Support\Facades\Log;
use RusticiSoftware\Cloud\V2\Api\RegistrationApi;
use RusticiSoftware\Cloud\V2\ApiException;
use RusticiSoftware\Cloud\V2\Configuration;
use RusticiSoftware\Cloud\V2\Model\RegistrationListSchema;

class ScormCloud
{
    /**
     * @var Configuration
     */
    protected Configuration $config;

    public function __construct()
    {
        $config = new Configuration();
        $config->setUsername(env('SCORM_CLOUD_APP_ID'));
        $config->setPassword(env('SCORM_CLOUD_SECRET_KEY'));

        $this->config = $config;
    }
}
