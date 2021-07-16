<?php

namespace App\Services\InternetShop;

use App\Helpers\WithFileUpload;
use App\Jobs\StoreImageOnS3;
use App\Models\InternetShopChatScreenshot;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Str;
use Storage;

class ChatScreenshotService
{
    use WithFileUpload;

    public function store($id, $chatScreenshots)
    {
        try {
            $chatScreenshots->each(function ($chatScreenshot, $index) use ($id) {
                $filename = Str::random(40);
                $table = new InternetShopChatScreenshot;
                $table->internet_shop_id = $id;
                $table->value = $filename.'.'.$chatScreenshot->getClientOriginalExtension();
                $table->fall_back = $filename.'.'.$chatScreenshot->getClientOriginalExtension();
                $table->order_by = $index;
                $table->save();

                // Queue job to save on S3
                dispatch(new StoreImageOnS3([
                    'id' => $id,
                    'name' => $filename,
                    'extension' => $chatScreenshot->getClientOriginalExtension(),
                    'file' => base64_encode(file_get_contents($chatScreenshot)),
                    'folder' => 'chat',
                ]));
            });
        } catch (\Exception $e) {
            abort(404);
            log($e);
        }
    }

    public function destroy()
    {
    }

    public function update($id, $screenshots, $order)
    {
        $screenshots->each(function ($chatScreenshot) use ($id) {
            $filename = Str::random(40);
            $table = new InternetShopChatScreenshot;
            $table->internet_shop_id = $id;
            $table->value = $filename.'.'.$chatScreenshot->getClientOriginalExtension();
            $table->fall_back = $filename.'.'.$chatScreenshot->getClientOriginalExtension();
            $latest = InternetShopChatScreenshot::where('internet_shop_id', $id)->latest('order_by')->pluck('order_by')->first();
            $table->order_by = $latest + 1;
            $table->save();

            dispatch(new StoreImageOnS3([
                'id' => $id,
                'name' => $filename,
                'extension' => $chatScreenshot->getClientOriginalExtension(),
                'file' => base64_encode(file_get_contents($chatScreenshot)),
                'folder' => 'chat',
            ]));
        });

//        collect($order)->each(function ($order, $index) use ($id) {
//            if (isset($order['baseName'])) {
//                $table = InternetShopChatScreenshot::where('internet_shop_id', $id)->whereValue($order['baseName'])->first();
//                $table->order_by = $index;
//                $table->save();
//            }
//        });
//
//        $storedScreenshots = InternetShopChatScreenshot::where('internet_shop_id', $id)->get();
//
//        $order = collect($order)->map(function ($order) {
//            return $order['baseName'];
//        })->toArray();
//
//        $storedScreenshots->filter(function ($value, $key) use ($order) {
//            if (! in_array($value->value, $order)) {
//                Storage::delete('/chat-screenshots/'.$value->value);
//                Storage::delete('/chat-screenshots/'.$value->fall_back);
//
//                return InternetShopChatScreenshot::find($value->id)->delete();
//            }
//        });
    }

    public function show()
    {
    }
}
