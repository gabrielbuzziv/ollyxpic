<?php

namespace App\Http\Controllers;


use App\Http\Requests\AuthRequest;
use App\User;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => 'isAuthorized']);
    }

    /**
     * Authenticate the user using the email and password,
     * if user is granted an access token will be retrived.
     *
     * @param AuthRequest $request
     * @return mixed
     */
    public function authenticate(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            $user = User::where('email', $credentials['email'])->first();

            if (! $user) {
                Log::warning('Tentativa de conexão com usuário inexistente', $credentials);

                return $this->respondNotFound(null, 'E-mail ou senha incorretos, tente novamente.');
            }

            if (! $token = JWTAuth::attempt($credentials)) {
                Log::warning('Não foi possível autenticar o usuário', $credentials);

                return $this->respondNotFound(null, 'E-mail ou senha incorretos, tente novamente.');
            }
        } catch (JWTException $e) {
            Log::critical('Não foi possível criar o token.', $credentials);

            return $this->respondInternalError($e, 'Não foi possível autenticar o usuário devido a problemas internos, tente novamente mais tarde.');
        }

        return $this->respond(['token' => $token]);
    }

    /**
     * Check if have an authenticated user and retrieve it.
     *
     * @return mixed
     */
    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        $token = JWTAuth::refresh(JWTAuth::getToken());

        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user', 'token'));
    }

    /**
     * Refresh the access token.
     *
     * @return mixed
     */
    public function refreshToken()
    {
        $token = JWTAuth::getToken();

        if (! $token) {
            Log::warning('Não encontrou o token em tentativa de atualizar o token.');

            return $this->respondBadRequest(null, 'Você foi desconectado por inatividade.');
        }

        try {
            $token = JWTAuth::refresh($token);
            Log::info("Token atualizado: ({$token})");
        } catch (TokenInvalidException $e) {
            Log::warning('Não encontrou o token em tentativa de atualizar o token.');

            return $this->respondNotFound('Sessão expirada, conecte novamente.');
        }

        if (request('redirect')) {
            return redirect(sprintf('%s?token=%s', request('redirect'), $token));
        }

        return $this->respond(['token' => $token]);
    }
}
