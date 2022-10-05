<?php

namespace App\Http\Response;

use Illuminate\Contracts\Support\MessageBag;
use Illuminate\Support\Facades\Lang;

enum ResponseCode: int
{
    case OK = 200;
    case NOT_AUTHENTICATED = 401;
    case NOT_FOUND = 404;
    case SERVER_ERROR = 403;
    case PRECONDITION_FAILED = 428;
    case NOT_ACCEPTABLE = 406;
}

enum ResponseStatus: string
{
    case OK = "OK";
    case WARNING = "WARNING";
    case BAD = "BAD";
}

class BodyResponse
{
    // used for header or response status
    private ResponseCode $responseCode = ResponseCode::OK;
    private ResponseStatus $responseStatus = ResponseStatus::OK;
    private array|object $responseData = [];

    // used for body
    private string $bodyMessage = "";
    private array|object $bodyData = [];
    private string|null $tokenData = null;

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

    public function setBodyData(array|object $data)
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

    public function setResponseError(
        string $message = "Server error",
        ResponseCode $responseCode = ResponseCode::SERVER_ERROR,
        ResponseStatus $responseStatus = ResponseStatus::BAD,
    ) {
        $this->bodyMessage = $message;
        $this->responseCode = $responseCode;
        $this->responseStatus = $responseStatus;
    }

    public function setResponseAuthFailed()
    {
        $this->responseCode = ResponseCode::NOT_AUTHENTICATED;
        $this->responseStatus = ResponseStatus::BAD;
        $this->bodyMessage = Lang::get('auth.failed');
    }

    public function setResponseNotFound(string $message = '')
    {
        $this->responseCode = ResponseCode::NOT_FOUND;
        $this->responseStatus = ResponseStatus::BAD;
        if (!empty($message)) $this->bodyMessage = $message;
        else $this->bodyMessage = Lang::get('data.not_found');
    }

    public function setResponseValidationError(array|MessageBag $errors)
    {
        $this->responseCode = ResponseCode::PRECONDITION_FAILED;
        $this->responseStatus = ResponseStatus::BAD;
        $this->bodyMessage = Lang::get('data.validation');
        $this->bodyData = $errors;
    }

    public function setPermissionDenied(string $message = null)
    {
        $this->responseCode = ResponseCode::NOT_ACCEPTABLE;
        $this->responseStatus = ResponseStatus::BAD;
        $this->bodyMessage = $message ?? Lang::get('errors.permission_denied');
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

        $response = [
            'code' => $this->responseCode->value,
            'status' => $this->responseStatus->value
        ];
        $body = [
            'message' => $this->bodyMessage,
            'data' => $this->bodyData
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
