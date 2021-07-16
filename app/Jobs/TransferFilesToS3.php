<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\File;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TransferFilesToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $fileName;
    private $path;

    /**
     * Create a new job instance.
     *
     * @param $path
     * @param $fileName
     */
    public function __construct($path, $fileName)
    {
        $this->path = $path;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function handle()
    {
        if (Storage::disk('local')->exists($this->path)) {
            /**
             * https://laravel.com/docs/6.x/filesystem#file-urls
             * Remember, if you are using the local driver, all files
             * that should be publicly accessible should be placed in the
             * storage/app/public directory.
             */
            $filePath = Storage::disk('local')->path($this->path);

            $path = Str::beforeLast($this->path, '/');

            Storage::disk('s3')->putFileAs($path, new File($filePath), $this->fileName, 'public');

            Storage::disk('s3')->setVisibility($path, 'public');
        }
    }
}
