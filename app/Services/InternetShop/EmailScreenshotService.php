<?php

namespace App\Services\InternetShop;

use App\Helpers\WithFileUpload;
use App\Jobs\StoreImageOnS3;
use App\Models\InternetShop;
use App\Models\InternetShopEmailScreenshot;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmailScreenshotService
{
    use WithFileUpload;

    public function store($id, $emailScreenshots)
    {
        try {
            $emailScreenshots->each(function ($emailScreenshot, $index) use ($id) {
                $filename = Str::random(40);
                $table = new InternetShopEmailScreenshot;
                $table->internet_shop_id = $id;
                $table->value = $filename.'.'.$emailScreenshot['file']->guessExtension();
                $table->fall_back = $filename.'.'.$emailScreenshot['file']->guessExtension();
                $table->order_by = $emailScreenshot['order'];
                $table->save();

                // Queue job to save on S3
                dispatch(new StoreImageOnS3([
                    'id' => $id,
                    'name' => $filename,
                    'extension' => $emailScreenshot['file']->guessExtension(),
                    /**
                     * Laravel note:
                     * Binary data, such as raw image contents, should be passed through the base64_encode function before being passed to a queued job.
                     * Otherwise, the job may not properly serialize to JSON when being placed on the queue.
                     */
                    'file' => base64_encode(file_get_contents($emailScreenshot['file'])),
                    'folder' => 'email',
                ]));
            });
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
            abort(404);
        }
    }

    public function destroy()
    {
    }

    public function update($id, $screenshots)
    {
        $screenshots->each(function ($emailScreenshot) use ($id) {
            if (isset($emailScreenshot['id'])) {
                $table = InternetShopEmailScreenshot::find($emailScreenshot['id']);
                $table->order_by = $emailScreenshot['order'];
                $table->internet_shop_id = $id;
                $table->save();
            } else {
                $filename = Str::random(40);
                $table = new InternetShopEmailScreenshot;
                $table->internet_shop_id = $id;
                $table->value = $filename.'.'.$emailScreenshot['file']->guessExtension();
                $table->fall_back = $filename.'.'.$emailScreenshot['file']->guessExtension();
                $table->order_by = $emailScreenshot['order'];
                $table->save();

                // Queue job to save on S3
                dispatch(new StoreImageOnS3([
                    'id' => $id,
                    'name' => $filename,
                    'extension' => $emailScreenshot['file']->guessExtension(),
                    'file' => base64_encode(file_get_contents($emailScreenshot['file'])),
                    'folder' => 'email',
                ]));
            }
        });
    }

    public function show()
    {
    }
}
