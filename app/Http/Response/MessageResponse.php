<?php

namespace App\Http\Response;

use Exception;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class MessageResponse
{
    /** @var array */
    protected static $message = [];

    /**
     * Get message response
     * @param string $dataName Set data name replacement. Set empty to be default "Data".
     * @param string $messageKey Message key to get specific message
     * @return array|string return array if message key empty string. Return string if message key exists if not throw error
     * @throws Exception
     */
    public static function getMessage(string $dataName  = '', string $messageKey = '')
    {
        if (!$dataName) $dataName = 'Data';
        self::$message['successIndex'] = Lang::get('data.get', ['Data' => Str::plural($dataName)]);
        self::$message['successIndexTrashed'] = Lang::get('data.get', ['Data' => Str::plural($dataName)]);
        self::$message['successGetFull'] = Lang::get('data.get', ['Data' => $dataName]);
        self::$message['successGet'] = Lang::get('data.get', ['Data' => $dataName]);
        self::$message['successGetTrashed'] = Lang::get('data.get', ['Data' => $dataName]);
        self::$message['successCreated'] = Lang::get('data.created', ['Data' => $dataName]);
        self::$message['successUpdated'] = Lang::get('data.updated', ['Data' => $dataName]);
        self::$message['successRestored'] = Lang::get('data.restored', ['Data' => $dataName]);
        self::$message['successDeleted'] = Lang::get('data.deleted', ['Data' => $dataName]);
        self::$message['successPermanentDeleted'] = Lang::get('data.force_deleted', ['Data' => $dataName]);
        self::$message['successReset'] = Lang::get('data.reset', ['Data' => $dataName]);

        self::$message['failedValidation'] = Lang::get('data.validation', ['Data' => $dataName]);
        self::$message['failedNotFound'] = Lang::get('data.not_found', ['data' => Str::lower($dataName)]);
        self::$message['failedUnauthorized'] = Lang::get('data.unauthorized');
        self::$message['failedPermission'] = Lang::get('data.permission_denied');
        self::$message['failedError'] = Lang::get('data.error');

        if (!$messageKey) return self::$message;

        if ($messageKey && key_exists($messageKey, self::$message)) {
            return self::$message[$messageKey];
        }
        throw new Exception("Message key of \"" . $messageKey . "\" is not found");
    }
}
