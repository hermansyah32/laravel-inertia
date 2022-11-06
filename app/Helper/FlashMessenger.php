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
        NotificationType $type = NotificationType::INFO,
        FlashType $flashType = FlashType::BANNER
    ) {
        Session::flash('flash_message', $message);
        Session::flash('flash_title', $title);
        Session::flash('flash_type', $type->value);
        Session::flash('flash_notification', $flashType->value);
    }

    public static function sendFromBody(BodyResponse $bodyResponse, FlashType $flashType = FlashType::BANNER)
    {
        $title = Lang::get('flash.success');
        $type = NotificationType::SUCCESS;
        $message = $bodyResponse->getBodyMessage();
        if ($bodyResponse->getResponseCode() !== ResponseCode::OK) {
            $title = Lang::get('flash.success');
            $type = NotificationType::ERROR;
        }
        Session::flash('flash_message', $message);
        Session::flash('flash_title', $title);
        Session::flash('flash_type', $type->value);
        Session::flash('flash_notification', $flashType->value);
    }
}
