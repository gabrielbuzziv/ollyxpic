<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{

    protected $statusCode = 200;

    /**
     * Get the status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set the status code.
     *
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Return a response error with status code 400.
     *
     * @param $exception
     * @param string $message
     * @return mixed
     */
    public function respondBadRequest($exception, $message = 'Ops, something went wrong, try again.')
    {
        return $this->setStatusCode(400)->respondWithError($exception, $message);
    }

    /**
     * Return a response error with status code 404.
     *
     * @param $exception
     * @param string $message
     * @return mixed
     */
    public function respondNotFound($exception, $message = 'Ops, not found!')
    {
        return $this->setStatusCode(404)->respondWithError($exception, $message);
    }

    /**
     * Return a response error with status code 500.
     *
     * @param $exception
     * @param string $message
     * @return mixed
     */
    public function respondInternalError($exception, $message = 'Ops, something went wrong, contact the administrator.')
    {
        return $this->setStatusCode(500)->respondWithError($exception, $message);
    }

    /**
     * Return a response error with status 401.
     *
     * @param $exception
     * @param string $message
     * @return mixed
     */
    public function respondUnauthorized($exception, $message = 'Ops, you are not authorized.')
    {
        return $this->setStatusCode(401)->respondWithError($exception, $message);
    }

    /**
     * Return a response with error message and status code.
     *
     * @param $exception
     * @param $message
     * @return mixed
     */
    public function respondWithError($exception, $message)
    {
        if (config('app.debug') && $exception != null) {
            return $this->respond([
                'error' => [
                    'message'     => $exception->getMessage(),
                    'status_code' => $exception->getCode(),
                    'trace'       => $exception->getTrace(),
                ],
            ]);
        }

        return $this->respond([
            'error' => [
                'message'     => $message,
                'status_code' => $this->getStatusCode(),
            ],
        ]);
    }

    /**
     * Return a response with created code. 201
     *
     * @param $data
     * @return mixed
     */
    public function respondCreated($data)
    {
        return $this->setStatusCode(201)->respond($data);
    }

    /**
     * Return a response as json with
     * status code and headers.
     *
     * @param array $data
     * @param array $headers
     * @return mixed
     */
    public function respond(array $data, $headers = [])
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * Redirect to somewhere.
     *
     * @param string $route
     * @return string
     */
    public function redirectTo($route = '/')
    {
        if (app('request')->input('redirect_to')) {
            return app('request')->input('redirect_to');
        }

        return $route;
    }

    /**
     * Return a message formatted based in the type.
     *
     * @param $message
     * @param $type
     * @return array
     */
    public function message($message, $type)
    {
        switch ($type) {
            case 'success':
                return $this->messageSuccess($message);
            case 'warning':
                return $this->messageWarning($message);
            case 'error':
                return $this->messageError($message);
        }
    }

    /**
     * Return a formatted success message.
     *
     * @param $message
     * @return array
     */
    public function messageSuccess($message)
    {
        return [
            'title'   => 'Success',
            'message' => $message,
            'type'    => 'success',
        ];
    }

    /**
     * Return a formatted warning message.
     *
     * @param $message
     * @return array
     */
    public function messageWarning($message)
    {
        return [
            'title'   => 'Warning',
            'message' => $message,
            'type'    => 'warning',
        ];
    }

    /**
     * Return a formatted success message.
     *
     * @param $message
     * @return array
     */
    public function messageError($message)
    {
        return [
            'title'   => 'Ops, something went wrong!',
            'message' => $message,
            'type'    => 'error',
        ];
    }

}