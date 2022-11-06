<?php

namespace App\Helper;

enum NotificationType: string
{
    case INFO = 'info';
    case SUCCESS = 'success';
    case WARNING = 'warning';
    case ERROR = 'error';
}
