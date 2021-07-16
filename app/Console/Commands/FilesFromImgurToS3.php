<?php

namespace App\Console\Commands;

use App\Http\Controllers\FileTransferFromImgurToS3Service;
use Illuminate\Console\Command;

class FilesFromImgurToS3 extends Command
{
    protected $signature = 'transfer:img-s3 {type*}';

    protected $description = 'Migrate uploaded files from imgur to s3';

    public function handle()
    {
        return FileTransferFromImgurToS3Service::transfer($this->argument('type'));
    }
}
