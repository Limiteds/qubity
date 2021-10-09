<?php

namespace App\Http\Requests;

/** @OA\Schema(
 *     description="Модель регистрации",
 *     title="Модель регистрации"
 * )
 */
class RegistrationRequest
{
    /**
     * @OA\Property(
     *     format="string",
     *     description="E-mail",
     *     title="E-mail",
     *     example="example@mail.com",
     * )
     *
     * @var string
     */
    public $email;
       /**
     * @OA\Property(
     *     format="string",
     *     description="Имя",
     *     title="Имя",
     *     example="Иван"
     * )
     *
     * @var string
     */
    public $name;
    /**
     * @OA\Property(
     *     format="string",
     *     description="Пароль",
     *     title="Пароль",
     *     example="qwerty123"
     * )
     *
     * @var string
     */
    public $password;
    /**
     * @OA\Property(
     *     format="string",
     *     description="Повтор пароля",
     *     title="Повтор пароля",
     *     example="qwerty123",
     * )
     *
     * @var string
     */
    public $password_confirmation;
}
