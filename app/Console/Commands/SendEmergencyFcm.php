<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmergencyAlert;
use App\Services\FcmService;
use App\Models\User;

class SendEmergencyFcm extends Command
{
    protected $signature = 'emergency:send {id}';
    protected $description = 'Send FCM push notification for an emergency alert asynchronously';

    public function handle()
    {
        $id = $this->argument('id');
        $alert = EmergencyAlert::find($id);

        if (!$alert) {
            $this->error("Emergency alert #{$id} not found.");
            return 1;
        }

        $title = $alert->title;
        $content = $alert->content;
        $extraData = [
            'alert_id' => (string) $alert->id,
            'type' => 'emergency',
            'sound' => 'emergency',
            'priority' => 'high'
        ];

        // 1. Broadcast to 'notice_all' topic
        FcmService::sendToTopic('notice_all', $title, $content, $extraData);

        // 2. Also send directly to all registered FCM device tokens in DB for instant delivery
        $usersWithTokens = User::whereNotNull('fcm_token')->where('fcm_token', '!=', '')->get();
        foreach ($usersWithTokens as $u) {
            FcmService::sendToToken($u->fcm_token, $title, $content, $extraData);
        }

        $this->info("Emergency alert #{$id} notification dispatched successfully.");
        return 0;
    }
}
