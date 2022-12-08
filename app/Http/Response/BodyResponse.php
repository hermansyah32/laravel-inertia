<?php

namespace App\Http\Response;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

enum ResponseCode: int
{
    case OK = 200;
    case NOT_AUTHENTICATED = 401;
    case NOT_FOUND = 404;
    case SERVER_ERROR = 403;
    case VALIDATION_ERROR = 422;
    case NOT_ACCEPTABLE = 406;
    case TOO_MANY_REQUEST = 429;
}

enum ResponseStatus: string
{
    case OK = "OK";
    case WARNING = "WARNING";
    case BAD = "BAD";
}

class RequestInfo
{
    public $requestInfo = [];
    public $ipInfo = [];
    public $userInfo = [];

    public function __construct($requestInfo, $ipInfo, $userInfo)
    {
        $this->requestInfo = $requestInfo;
        $this->ipInfo = $ipInfo;
        $this->userInfo = $userInfo;
    }

    public function toArray()
    {
        return [
            'request' => $this->requestInfo,
            'ipInfo' => $this->ipInfo,
            'userInfo' => $this->userInfo,
        ];
    }
}

class BodyResponse
{
    // used for header or response status
    private ResponseCode $responseCode = ResponseCode::OK;
    private ResponseStatus $responseStatus = ResponseStatus::OK;
    private array|object $responseData = [];

    // used for body
    private string $bodyMessage = "";
    private array|object|null $bodyData = [];
    private string|null $tokenData = null;

    // in case need exception data
    private $exception;

    // in case have request. contains ip info, and request send
    private RequestInfo|array $request = [];

    // is message exception
    private $knownIssue = false;

    public function getResponseCode(): ResponseCode
    {
        return $this->responseCode;
    }

    public function setResponseCode(ResponseCode $responseCode)
    {
        $this->responseCode = $responseCode;
    }

    public function getResponseStatus(ResponseStatus $responseStatus): ResponseStatus
    {
        return $this->responseStatus = $responseStatus;
    }

    public function setResponseStatus(ResponseStatus $responseStatus)
    {
        $this->responseStatus = $responseStatus;
    }

    public function getResponseData(): array|object
    {
        return $this->responseData;
    }

    public function setResponseData(array|null $data)
    {
        $this->responseData = $data;
    }

    public function getBodyMessage(): string
    {
        return $this->bodyMessage;
    }

    public function setBodyMessage(string $message)
    {
        $this->bodyMessage = $message;
    }

    public function getBodyData(): array|object
    {
        return $this->bodyData;
    }

    public function setBodyData(array|object|null $data)
    {
        $this->bodyData = $data;
    }

    public function getTokenData(): string
    {
        return $this->tokenData;
    }

    public function setTokenData(string $token)
    {
        $this->tokenData = $token;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function setException($exception)
    {
        $this->exception = $exception;
    }

    public function getKnownIssue()
    {
        return $this->knownIssue;
    }

    public function setKnownIssue(bool $knownIssue)
    {
        $this->knownIssue = $knownIssue;
    }

    public function getRequestInfo()
    {
        return is_array($this->request) ? $this->request : $this->request->toArray();
    }

    public function setRequestInfo(Request $request, array $currentUser = [])
    {
        $geoInfo = [];
        try {
            $geoInfo = geoip()->getLocation($request->ip)->toArray();
        } catch (\Throwable $th) {
            // failed to get info, skip info ip location
        }
        if (count($geoInfo) === 0) $geoInfo = ['ip' => $request->ip];

        // remove sensitive data
        $input = $request->all();
        foreach ($input as $key => $value) {
            if (str_contains($key, 'password')) {
                $input[$key] = '';
            }
        }

        $requestInfo = new RequestInfo(
            ['param' => $input, 'url' => $request->url(), 'method' => $request->method()],
            $geoInfo,
            $currentUser
        );

        $this->request = $requestInfo;
    }

    public function setResponseError(
        string $message = "Server error",
        ResponseCode $responseCode = ResponseCode::SERVER_ERROR,
        ResponseStatus $responseStatus = ResponseStatus::BAD,
    ): BodyResponse {
        $this->bodyMessage = $message;
        $this->responseCode = $responseCode;
        $this->responseStatus = $responseStatus;
        return $this;
    }

    public function setResponseAuthFailed(): BodyResponse
    {
        $this->responseCode = ResponseCode::NOT_AUTHENTICATED;
        $this->responseStatus = ResponseStatus::BAD;
        $this->bodyMessage = Lang::get('auth.failed');
        return $this;
    }

    public function setResponseNotFound(string $messageKey = 'Data', string|null $message = ''): BodyResponse
    {
        $this->responseCode = ResponseCode::NOT_FOUND;
        $this->responseStatus = ResponseStatus::BAD;
        $this->bodyMessage =  $message ?: MessageResponse::getMessage($messageKey, 'failedNotFound');
        return $this;
    }

    public function setResponseValidationError(
        array|MessageBag $errors,
        string $messageKey = 'Data',
        string|null $message = null
    ): BodyResponse {
        $this->responseCode = ResponseCode::VALIDATION_ERROR;
        $this->responseStatus = ResponseStatus::BAD;
        $this->bodyMessage = $message ?: $this->bodyMessage = MessageResponse::getMessage($messageKey, 'failedValidation');
        $this->bodyData = $errors;
        return $this;
    }

    public function setPermissionDenied(string|null $message = null): BodyResponse
    {
        $this->responseCode = ResponseCode::SERVER_ERROR;
        $this->responseStatus = ResponseStatus::BAD;
        $this->bodyMessage = $message ?: MessageResponse::getMessage('', 'failedPermission');
        return $this;
    }

    public function getHeaderResponse(): array
    {
        return [
            'code' => $this->responseCode->value,
            'status' => $this->responseStatus->value
        ];
    }

    public function getBodyResponse(): array
    {
        $body = [
            'message' => $this->bodyMessage,
            'data' => $this->bodyData
        ];

        $result = [
            'response' => $this->getHeaderResponse(),
            'body' => $body
        ];

        if (isset($this->tokenData)) {
            $result['token'] = $this->tokenData;
        }
        return $result;
    }

    public function getResponse(): array
    {
        $result = null;
        $dataKey = $this->responseStatus === ResponseStatus::OK ? 'data' : 'error';

        $response = [
            'code' => $this->responseCode->value,
            'status' => $this->responseStatus->value
        ];
        $body = [
            'message' => $this->bodyMessage,
            $dataKey => $this->bodyData
        ];

        $result = [
            'response' => $response,
            'body' => $body
        ];

        if (isset($this->tokenData)) {
            $result['token'] = $this->tokenData;
        }
        return $result;
    }
}
