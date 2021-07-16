<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('/aws/sns', '\Rennokki\LaravelSnsEvents\Http\Controllers\SnsController@handle');

Route::post('check-token', 'AuthController@checkToken');

Route::get('export/group-shop', 'ExportGroupShopController@index')->name('export.group-shop');

Route::group(['middleware' => ['auth:api']], function () {
    Route::resource('users/status', 'UserStatusController', ['parameters' => [
        'status' => 'any_user',
    ]]);

    Route::get('dealer/{id}/specific-dealers', 'GetSpecificDealerController@index');

    Route::make()
        ->namespace('Twilio')
        ->prefix('twilio')
        ->name('twilio.')
        ->group(function () {
            Route::post('/list-available-numbers', 'ListNumberController')->name('list-available-numbers');
            Route::post('/get-specific-logs-by-number/', 'LogController')->name('get-logs-by-number');
        });

    Route::resource('/events', 'EventController');
    Route::post('/event/{id}/sync-to-calendar/', 'CalendarSyncController@store');
    Route::resource('/users/secret-shoppers', 'SecretShopperController');

    Route::get('/states', 'StateController');
    Route::get('/leadsources', 'LeadSourceController');
    Route::get('/cities/{stateId}', 'CityController@show');
    Route::get('/files/voice-mails', 'VoiceMailController');

    Route::resource('/featured-videos', 'FeaturedVideoController');
    Route::resource('/featured-video/related-units', 'FeaturedVideoRelatedUnitController')->except(['create', 'delete', 'update', 'destroy']);
    Route::resource('/featured-video-watched', 'FeaturedVideoMarkAsWatchController')->except(['create', 'delete', 'update', 'destroy']);
    Route::post('/featured-video-played', 'FeaturedVideoMarkAsWatchController@playedVideo');

    Route::post('mark-intro-video-watched', 'IntroVideoController@markAsWatched');
    Route::get('dealer/{id}/users', 'DealerUserController@index');

    Route::get('specific-dealer/{id}/users', 'SpecificDealerUserController@index');

    Route::resource('groups', 'GroupController');
    Route::get('/get-video-of-the-day', 'GetVideoOfTheDayController@index');

    Route::post('mark-user-unit-video-played', 'MarkPlayedController@setUnitVideoPlayed');
    Route::post('mark-module-user-video-played', 'MarkPlayedController@setPlayedModuleVideo');

    Route::make()
        ->prefix('secret-shop-management')
        ->group(function () {
            Route::get('phone-numbers', 'PhoneNumberController@index');
            Route::post('phone-numbers', 'PhoneNumberController@store');
            Route::get('phone-numbers/search', 'PhoneNumberController@searchNumbers');
            Route::patch('phone-numbers/{id}', 'PhoneNumberController@update');
            Route::post('phone-numbers/delete', 'PhoneNumberController@destroy');
            Route::get('fetch-phone-numbers', 'PhoneNumberController@fetchNumbersToManage');

            Route::resource('internet-shops', 'InternetShopController');
            Route::get('internet-shop/{internet_shop}', 'InternetPostController@show');
            Route::post('internet-shop', 'InternetPostController@store');
            Route::patch('internet-shop/{internet_shop}', 'InternetPostController@update');
            Route::get('internet-shops/restore/{id}', 'InternetShopController@restore');
            Route::get('internet-shops/fetch-sms/{id}', 'InternetShopController@fetchSms');

            Route::resource('phone-shops', 'PhoneShopController')->except(['create', 'edit']);
            Route::get('phone-shops/restore/{id}', 'PhoneShopRestoreController@restore');

            Route::apiResource('group-shops', 'GroupShopController');
            Route::patch('group-shops/restore/{id}', 'GroupShopRestoreController@update');

            Route::get('export/group-shop/{id}', 'ExportGroupShopController@show');
        });

    Route::post('/get-users-by-roles', 'GetUserByRolesController@index');

    Route::make()
        ->prefix('notifications')
        ->group(function () {
            Route::get('/', 'NotificationController@index');
            Route::patch('/unread', 'NotificationController@markAsUnread');
            Route::post('/read', 'NotificationController@markAsRead');
        });

    Route::make()
        ->namespace('LMS')
        ->prefix('lms-manager')
        ->group(function () {
            Route::get('fetch/categories', 'CategoryController@fetchCategories');
            Route::get('fetch/modules', 'ModuleController@fetchModules');

            Route::resource('courses', 'CourseController')->except(['create', 'edit', 'show']);
            Route::patch('/courses/restore/{id}', 'CourseRestoreController');
            Route::get('/search/courses', 'CourseSearchController');

            Route::get('categories', 'CategoryController@index');
            Route::post('categories', 'CategoryController@store');
            Route::post('categories/{id}', 'CategoryController@update');
            Route::delete('categories/{id}', 'CategoryController@destroy');
            Route::patch('/categories/restore/{id}', 'CategoryController@restore');

            Route::get('modules', 'ModuleController@index');
            Route::post('modules', 'ModuleController@store');
            Route::post('modules/{id}', 'ModuleController@update');
            Route::delete('modules/{id}', 'ModuleController@destroy');
            Route::patch('/modules/restore/{id}', 'ModuleController@restore');

            Route::get('units', 'UnitController@index');
            Route::post('units', 'UnitController@store');
            Route::post('units/{id}', 'UnitController@update');
            Route::delete('units/{id}', 'UnitController@destroy');
            Route::patch('/units/restore/{id}', 'UnitController@restore');

            Route::resource('tags', 'TagController')->except(['create', 'edit', 'show']);
        });
    Route::make()
        ->namespace('LMS\Builder')
        ->prefix('lms-manager/builder')
        ->group(function () {
            Route::resource('modules', 'ModuleBuilderController')->except(['create', 'edit', 'show']);
            Route::patch('modules/restore/{id}', 'ModuleBuilderController@restore');
            Route::resource('categories', 'CategoryBuildController')->except(['create', 'edit', 'show']);
            Route::patch('categories/restore/{id}', 'CategoryBuildController@restore');
            Route::resource('courses', 'CourseBuildController')->except(['create', 'edit', 'show']);
            Route::patch('courses/restore/{id}', 'CourseBuildController@restore');
            Route::apiResource('quizzes', 'QuizBuilderController');
            Route::apiResource('quiz/duplicate/', 'QuizDuplicateController')->except(['update', 'index', 'show', 'destroy']);
            Route::apiResource('quiz/restore', 'QuizBuilderRestoreController')->except(['store', 'destroy', 'index', 'show']);
            Route::get('questions', 'SearchQuestionController@index');

            Route::get('fetch/categories', 'CategoryBuildController@fetchCategoriesByCourse');
            Route::get('fetch/category_builds', 'CourseBuildController@fetchCategoryBuildsByCourse');
            Route::get('fetch/module_builds', 'CategoryBuildController@fetchModuleBuildsByCategory');
            Route::get('fetch/modules', 'ModuleBuilderController@fetchModulesByCourse');
            Route::get('fetch/units', 'ModuleBuilderController@fetchUnitsByModules');
            Route::get('fetch/courses', 'ModuleBuilderController@fetchCourses');
        });
    Route::post('/vin', 'VehicleIdentificationNumberController');
    Route::resource('users', 'UserController');

    Route::make()
        ->prefix('secret-shop-data-summary')
        ->namespace('SecretShopDataSummary')
        ->group(function () {
            Route::get('/sales-persons', 'SalesPersonController@index');
            Route::get('/specific-dealers', 'SpecificDealerController@index');
            Route::get('/compare-dealers-performance', 'CompareDealerPerformanceController@index');
            Route::get('/get-filtered-data', 'FilterController@index');

            Route::post('/fetchFilters', 'ClientShopsController@fetchFilters');
            Route::post('/fetchData', 'ClientShopsController@fetchData');
            Route::post('/exportData', 'ClientShopsController@exportData');
        });
    Route::get('secret-shops-table', 'SecretShopTableController@index');

    Route::resource('playlist/add/unit', 'PlaylistAddUnitController');
    Route::resource('playlist/delete/unit', 'PlaylistDeleteUnitController');
    Route::resource('playlist/unit', 'PlaylistUnitController');
    Route::resource('playlist/assign', 'PlaylistAssignController');
    Route::resource('playlist', 'PlaylistController');
    Route::patch('playlist/update-order/{id}', 'PlaylistOrderController@update');

    Route::resource('unit/favorite', 'UnitFavoriteController');
    Route::resource('my-profile', 'UserProfileController')->except(['edit', 'destroy', 'create', 'store']);
    Route::resource('profile/picture', 'UserProfilePictureController')->except(['index', 'edit', 'destroy', 'create', 'store']);
    Route::resource('search-analytics', 'SearchAnalyticsController')->except(['edit', 'create']);

    Route::apiResource('scorm/course', 'ScormCourseController');
    Route::apiResource('scorm/registration', 'ScormRegistrationController');
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('register', 'RegisterController@store');
});

Route::get('/ip', function () {
    dd(request()->ip());
});

Route::resource('roles', 'RoleController');
Route::post('account', 'UserController@storeAccount');

Route::resource('dealers/specific', 'SpecificDealerController');
Route::resource('dealers', 'DealerController');
Route::post('dealers/settings/file-uploads', 'DealerSettingsFileUploadController@store');
Route::resource('call', 'CallController');
Route::resource('sms', 'SmsController');

Route::make()
    ->prefix('twilio')
    ->namespace('Twilio')
    ->name('twilio.')
    ->group(function () {
        Route::post('reply/sms', 'OutgoingSMSController@store');
        Route::post('incoming/call', 'IncomingCallController@index')->name('incoming-voice');
        Route::post('incoming/sms', 'IncomingSMSController@index')->name('incoming-sms');
        Route::get('record/incoming/call', 'IncomingCallRecorderController@index')->name('record-call');
    });

Route::make()
    ->prefix('preview-reports')
    ->group(function () {
        Route::get('phone-shop/{id}', 'PreviewPhoneShopReportController@show');
        Route::get('internet-shop/{id}', 'PreviewInternetShopReportController@show');
        Route::get('group-shop/{id}', 'GroupShopReportController@show');
    });

Route::make()
    ->prefix('send-reports')
    ->group(function () {
        Route::post('phone-shop', 'SendPhoneShopReportController@store');
        Route::post('internet-shop', 'SendInternetShopReportController@store');
        Route::post('group-shop', 'SendGroupShopReportController@store');
    });

Route::make()
    ->prefix('client/lms')
    ->namespace('LMS')
    ->group(function () {
        Route::get('/fetchCourseLibrary', 'ClientLmsController@fetchCourseLibrary');
        Route::get('/fetchCourseLibraryHome', 'ClientLmsController@fetchCourseLibraryLazied');
        Route::get('/fetchAssignedUnits', 'ClientLmsController@fetchAssignedUnits');
        Route::get('/fetchPlaylists', 'ClientLmsController@fetchPlaylists');
        Route::get('/fetchInProgressModule', 'ClientLmsController@fetchInProgressModule');
        Route::get('/fetchLatestAssignedModule', 'ClientLmsController@fetchLatestAssignedModule');
        Route::get('/fetchUsersByDealer', 'AssignUnitsController@fetchUsersByDealer');
        Route::resource('/slider/categories', 'CategoryPageSliderController')->except(['create', 'store', 'update', 'destroy']);
        Route::resource('/related-units', 'RelatedUnitController')->except(['create', 'show', 'destroy', 'edit', 'update']);
        Route::post('/assignUnit', 'AssignUnitsController@assignUnit');
        Route::post('/assignModule', 'AssignUnitsController@assignModule');
        Route::get('/unit/{id}', 'UnitClientController@show');

        Route::post('/shareunit', 'ShareController@shareUnit');
        Route::post('/sharemodule', 'ShareController@shareModule');

        Route::apiResource('quiz', 'QuizController');
    });

Route::make()
    ->prefix('client/reports')
    ->namespace('LMS')
    ->group(function () {
        Route::get('/fetchspecificdealers', 'LmsReportController@getSpecificDealers');
        Route::post('/fetchdata', 'LmsReportController@index');
        Route::post('/fetchteam', 'LmsReportController@getTeamProgress');
        Route::post('/fetchoverview', 'LmsReportController@getOverviewReport');
        Route::post('/exportoverview', 'LmsReportController@exportOverviewReport');
        Route::post('/exportData', 'LmsReportController@exportData');
        Route::post('/fetchusers', 'LmsReportController@getUsers');
        Route::post('/fetchovewviewmodules', 'LmsReportController@getOverviewModuleHeaders');
        Route::get('/fetchmodules', 'LmsReportController@getModules');
        Route::get('/fetchoutstandingmodules', 'LmsReportController@getOutstandingModules');
        Route::get('/fetchoutstandingplaylists', 'LmsReportController@getOutstandingPlaylists');

        Route::apiResource('/lms/overview', 'ReportLmsOverviewController');
    });

Route::get('/options/dealer', 'DealerOptionController@index');

Route::make()
    ->prefix('client/lms')
    ->namespace('LMS')
    ->group(function () {
        Route::get('/module/{id}', 'ModuleClientController@show');
    });

Route::make()
    ->prefix('mark-as-completed')
    ->namespace('LMS')
    ->group(function () {
        Route::post('determine-playlist-type', 'DetectPlaylistTypeController@determine');
        Route::post('/', 'MarkAsCompletedController@store');
        Route::post('is-completed', 'MarkAsCompletedController@show');
    });

Route::make()
    ->prefix('notes')
    ->namespace('LMS')
    ->group(function () {
        Route::get('/unit/{unitId}', 'NoteController@index');
        Route::post('/unit', 'NoteController@store');
        Route::patch('/{id}', 'NoteController@update');
    });

// Send reset password mail
Route::post('reset-password', 'AuthController@sendPasswordResetLink');

// handle reset password form process
Route::post('reset/password', 'AuthController@callResetPassword');

Route::get('/search', 'SearchController@index');

Route::name('guest.')
    ->prefix('guest')
    ->group(function () {
        Route::apiResource('phone-shop', 'GuestPhoneShopViewController')
            ->except(['index', 'update']);
        Route::apiResource('group-shop', 'GuestGroupShopViewController')
            ->except(['index', 'update', 'store', 'destroy']);
        Route::apiResource('internet-shop', 'GuestInternetShopViewController')
             ->except(['index', 'update', 'store', 'destroy']);
    });

Route::get('venv/aws-url', function () {
    return response()->json([
        'aws_url' => env('AWS_URL'),
    ]);
});

Route::apiResource('scorm/registration/webhook', 'ScormRegistrationWebhookController')
    ->middleware('scormCloudAuth')
    ->names([
        'store' => 'scorm.webhook.store',
    ]);

Route::make()
    ->prefix('clients/secret-shops')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::apiResource('internet-shops', 'Client\SecretShops\InternetShopController')
            ->except(['store', 'update', 'delete', 'show']);
        Route::apiResource('phone-shops', 'Client\SecretShops\PhoneShopController')
            ->except(['store', 'update', 'delete', 'show']);
    });
