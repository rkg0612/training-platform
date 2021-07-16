<?php

namespace App\Services\ScormCloud;

use App\Models\ScormCloud\Course;
use App\Models\ScormCloud\Registration;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RusticiSoftware\Cloud\V2\Api\RegistrationApi;
use RusticiSoftware\Cloud\V2\ApiException;
use RusticiSoftware\Cloud\V2\Model\CreateRegistrationSchema;
use RusticiSoftware\Cloud\V2\Model\LaunchLinkRequestSchema;
use RusticiSoftware\Cloud\V2\Model\LaunchLinkSchema;
use RusticiSoftware\Cloud\V2\Model\LearnerSchema;
use RusticiSoftware\Cloud\V2\Model\PostBackSchema;
use RusticiSoftware\Cloud\V2\Model\RegistrationListSchema;

class RegistrationService extends ScormCloud
{
    /**
     * @var RegistrationApi
     */
    private RegistrationApi $registrationApi;

    public function __construct()
    {
        parent::__construct();
        $this->registrationApi = new RegistrationApi(null, $this->config, null);
    }

    public function getRegistrations($courseId = null, $learnerId = null): RegistrationListSchema
    {
        try {
            return $this->registrationApi->getRegistrations($courseId, $learnerId, null, null, null, 'true', 'true', 'true');
        } catch (ApiException $e) {
            Log::error($e);
        }
    }

    public function create($courseId, User $user): string
    {
        $record = Registration::where('course_id', $courseId)
            ->where('user_id', $user->id)
            ->first();

        if (! empty($record)) {
            return $record->uuid;
        }

        $uuid = (string) Str::uuid();
        $schema = new CreateRegistrationSchema();
        $schema->setCourseId($courseId);
        $schema->setRegistrationId($uuid);
        $schema->setLearner($this->createLearnerSchema($user));
        $schema->setPostBack($this->createPostBackSchema());

        try {
            $this->registrationApi->createRegistration($schema);
        } catch (ApiException $e) {
            Log::error($e);
            abort(500);
        }

        $record = new Registration;
        $record->course_id = $courseId;
        $record->user_id = $user->id;
        $record->uuid = $uuid;
        $record->save();

        return $uuid;
    }

    private function createLearnerSchema(User $user): LearnerSchema
    {
        $schema = new LearnerSchema();
        $schema->setId($user->id);
        $schema->setEmail($user->email);

        return $schema;
    }

    public function createLaunchLink($registrationId): string
    {
        $schema = new LaunchLinkRequestSchema();
        $schema->setRedirectOnExitUrl(env('APP_URL'));
        $schema->setTracking(true);

        try {
            $response = $this->registrationApi->buildRegistrationLaunchLink($registrationId, $schema);
        } catch (ApiException $e) {
            Log::error($e);
        }

        return $response->getLaunchLink();
    }

    public function registrationExists($courseId, $userId)
    {
        return Registration::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->exists();
    }

    public function show($id)
    {
        $user = auth()->user();
        $course = Course::findOrFail($id);
        $registrationId = $this->create($course->id, $user);

        return $this->createLaunchLink($registrationId);
    }

    private function createPostBackSchema(): PostBackSchema
    {
        $schema = new PostBackSchema();
        $schema->setUrl(env('APP_URL').'api/scorm/registration/webhook');

        return $schema;
    }
}
