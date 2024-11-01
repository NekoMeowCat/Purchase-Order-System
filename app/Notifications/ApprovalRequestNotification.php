<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApprovalRequestNotification extends Notification
{
    use Queueable;

    protected $record;

    public function __construct($record)
    {
        $this->record = $record;
    }

    public function via($notifiable)
    {
        return ['database']; // Choose preferred channels: 'mail', 'database', etc.
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'You have a new purchase order awaiting your approval.',
            'record_id' => $this->record->id,
            'record_type' => get_class($this->record),
        ];
    }
}
