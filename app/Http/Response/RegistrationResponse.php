<?php

namespace App\Http\Response;
/** @OA\Schema(
 *     description="Модель ответа регистрации",
 *     title="Модель ответа регистрации"
 * )
 */
class RegistrationResponse {
    /**
     * @OA\Property(
     *     format="string",
     *     description="Токен доступа",
     *     title="Токен доступа",
     *     example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sb2NhbGhvc3Q6OD",
     * )
     *
     * @var string
     */
    public $access_token;
    /**
     * @OA\Property(
     *     format="string",
     *     description="Тип токена",
     *     title="Тип токена",
     *     example="bearer"
     * )
     *
     * @var string
     */
    public $token_type;
    /**
     * @OA\Property(
     *     format="string",
     *     description="Время жизни токена",
     *     title="Время жизни токена",
     *     example="1586930699"
     * )
     *
     * @var string
     */
    public $expires_in;
}
