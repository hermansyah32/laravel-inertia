<?php

namespace App\Helper;

class UserNotification
{
    public static function create(NotificationType $type, $title, $message, $data = [])
    {
        return [
            'title' => $title,
            'type' => $type->value,
            'message' => $message,
            'data' => $data
        ];
    }
}
