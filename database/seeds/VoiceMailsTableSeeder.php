<?php

use App\Models\VoiceMail;
use Illuminate\Database\Seeder;

class VoiceMailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $voiceMails = [
            (object) [
                'name'  => 'Boy VM 1',
                'value' => 'https://webinarinc.com/wp-content/uploads/2017/10/Standard-telephone-greeting-1.mp3',
            ],
            (object) [
                'name'  => 'Boy VM 2',
                'value' => 'https://webinarinc.com/wp-content/uploads/2017/11/Guy-VM-MP3.mp3',
            ],
            (object) [
                'name'  => 'Boy VM 3',
                'value' => 'https://webinarinc.com/wp-content/uploads/2017/12/ThankYouForCallingMP3.mp3',
            ],
            (object) [
                'name'  => 'Girl VM 1',
                'value' => 'https://webinarinc.com/wp-content/uploads/2017/10/Girl.mp3',
            ],
        ];
        foreach ($voiceMails as $voiceMail) {
            VoiceMail::create([
                'name'  => $voiceMail->name,
                'value' => $voiceMail->value,
            ]);
        }
    }
}
