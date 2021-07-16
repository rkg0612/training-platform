<?php

namespace App\Services\ScormCloud;

use App\Models\ScormCloud\Course;
use Illuminate\Support\Facades\Log;
use RusticiSoftware\Cloud\V2\Api\CourseApi;
use RusticiSoftware\Cloud\V2\ApiException;

class CourseService extends ScormCloud
{
    /**
     * @var CourseApi
     */
    private CourseApi $courseApi;
    /**
     * @var RegistrationService
     */
    private RegistrationService $registrationService;
    private ?\Illuminate\Contracts\Auth\Authenticatable $user;

    public function __construct(RegistrationService $registrationService)
    {
        parent::__construct();
        $this->courseApi = new CourseApi(null, $this->config, null);
        $this->registrationService = $registrationService;
        $this->user = auth()->user();
    }

    public function index()
    {
        return Course::where('dealer_id', $this->user->dealer_id)
            ->get();
    }

    public function show($id)
    {
        $course = Course::findOrFail($id);
        $registrationId = $this->registrationService->create($course->id, $this->user);

        return $this->registrationService->createLaunchLink($registrationId);
    }
}
