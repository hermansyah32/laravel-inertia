<?php

namespace App\Helper;

use App\Helper\NotificationType;
use App\Http\Response\BodyResponse;
use App\Http\Response\ResponseCode;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;

enum FlashType: string
{
    case TOAST = 'toast';
    case MODAL = 'modal';
    case BANNER = 'banner';
}

class FlashMessenger
{
    public static function send(
        string $title,
        string $message,
        NotificationType $notification = NotificationType::INFO,
        FlashType $type = FlashType::BANNER
    ) {
        Session::flash('flash_message', $message);
        Session::flash('flash_title', $title);
        Session::flash('flash_notification', $notification->value);
        Session::flash('flash_type', $type->value);
    }

    public static function sendFromBody(BodyResponse $bodyResponse, FlashType $type = FlashType::BANNER)
    {
        $title = Lang::get('flash.success');
        $notification = NotificationType::SUCCESS;
        $message = $bodyResponse->getBodyMessage();
        if ($bodyResponse->getResponseCode() !== ResponseCode::OK) {
            $title = Lang::get('flash.error');
            $notification = NotificationType::ERROR;
        }
        Session::flash('flash_message', $message);
        Session::flash('flash_title', $title);
        Session::flash('flash_notification', $notification->value);
        Session::flash('flash_type', $type->value);
    }
}
