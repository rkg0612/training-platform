<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class RemoveFileAtS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function handle()
    {
        if (Storage::disk('s3')->exists($this->path)) {
            Storage::disk('s3')->delete($this->path);
        }
    }
}
