<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JWTAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            //Access token from the request
            $token = JWTAuth::parseToken();
            //Try authenticating user
            $user = $token->authenticate();
        } catch (TokenExpiredException $e) {
            //Thrown if token has expired
            return $this->unauthorized('Срок действия вашего токена истек. Пожалуйста, войдите снова.');
        } catch (TokenInvalidException $e) {
            //Thrown if token invalid
            return $this->unauthorized('Ваш токен недействителен. Пожалуйста, войдите снова.');
        } catch (JWTException $e) {
            //Thrown if token was not found in the request.
            return $this->unauthorized('Пожалуйста, прикрепите токен на ваш запрос.');
        }

        $user = auth('api')->user();

        $iat = auth('api')->parseToken()->payload()->get('iat');

        if ($user->last_login != null) {
            $last_login = \Carbon\Carbon::parse($user->last_login)->timestamp;

            if ($iat < $last_login) {
                return $this->unauthorized('Ваш токен не действителен. Повторите авторизацию.');
            }
        }

        return $next($request);
    }

    private function unauthorized($message = null) {
        return response()->json([
            'error' => $message ? $message : 'Вы не авторизованы для доступа к этому ресурсу',
        ], 401);
    }
}
