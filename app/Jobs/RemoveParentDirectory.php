<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class RemoveParentDirectory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $disk;
    private $path;

    public function __construct($path, $disk)
    {
        $this->path = $path;
        $this->disk = $disk;
    }

    public function handle()
    {
        $currentFilesCount = Storage::disk($this->disk)
            ->allFiles($this->path);

        if (count($currentFilesCount) === 0) {
            Storage::disk($this->disk)
                ->deleteDirectory($this->path);
        }
    }
}
