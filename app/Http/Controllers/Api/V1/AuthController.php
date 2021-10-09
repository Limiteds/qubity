<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Clicks;
use App\Models\Referrals;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

      /**
     * @OA\info(
     *      description="This is a sample Marble server.",
     *      title="Qubity API",
     *      version="1.0")
     */

    /**
     * @OA\SecurityScheme(
     *   securityScheme="bearerAuth",
     *   type="http",
     *   in="header",
     *   name="bearerAuth",
     *   scheme="bearer",
     *   bearerFormat="JWT",
     * )
     */

    /**
     * @OA\Tag(
     *     name="auth",
     *     description="Авторизация",
     * )
     */
    public function __construct()
    {
        $this->middleware('auth.api:api', ['except' => ['login', 'registration']]);
    }

      /**
     * @OA\Post(
     *     path="/api/v1.0/auth/login",
     *     tags={"auth"},
     *     summary="Авторизация",
     *     description="Для входа нужно передать два поля: email и password.",
     *     operationId="login",
     *     @OA\Response(response="200", description="ok", @OA\JsonContent(ref="#/components/schemas/LoginResponse")),
     *     @OA\Response(response="400", description="Отправленны неверные данные"),
     *     @OA\Response(response="422", description="Ошибка валидации"),
     *     @OA\Response(response="500", description="Неизвестная ошибка сервера"),
     *     @OA\RequestBody(
     *         description="Модель авторизации",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     )
     * )
     */
    public function login(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6',
        ]);

        $credentials = '';
        
        if ($request->email) {
            $credentials = request(['email', 'password']);
        } else {
            return response()->json(['error' => 'Почтовый ящик обязателен для заполнения'], 422);
        }
       
        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json(['error' => 'Неверный пользователь или пароль'], 422);
        }

        return $this->respondWithToken($token);
    }

     /**
     * @OA\Post(
     *     path="/api/v1.0/auth/registration",
     *     tags={"auth"},
     *     summary="Регистрация",
     *     description="Регистрация пользователя",
     *     operationId="registration",
     *     @OA\Response(response="200", description="ok", @OA\JsonContent(ref="#/components/schemas/RegistrationResponse")),
     *     @OA\Response(response="400", description="Отправленны неверные данные"),
     *     @OA\Response(response="422", description="Ошибка валидации"),
     *     @OA\Response(response="500", description="Неизвестная ошибка сервера"),
     *     @OA\RequestBody(
     *         description="Модель регистрации",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegistrationRequest")
     *     )
     * )
     */
    public function registration(Request $request)
    {   

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6',
        ]);

        if (trim($request->password) !== trim($request->password_confirmation)) {
            return response()->json(['error' => 'Пароли не совпадают'], 422);
        }
            
        $user = Users::where('email', trim($request->email))->first();
        if ($user) return response()->json(['error' => 'Пользователь с таким почтовым ящиком уже зарегестрирован'], 422);

        $ref_id = trim($request->email) . time();
        $hash_ref = Hash::make($ref_id);
        $user = Users::create([
            'email' => $request->email, 
            'password' => Hash::make(trim($request->password)),
            'refID' => $hash_ref
            ]);

        $click_id = $this->getHeader($request, 'click_id');
        if (!$click_id) {
            $click_id = $this->getCookie($request, 'click_id');
        }

        if ($click_id) {
            $refer_user = Users::find($click_id);
        }

        if (isset($refer_user)) {
            $user->clicks()->create(['ref_id' => $hash_ref]);
            $user->referrals()->create(['parent_user_id' => $click_id]);
        }

        $credentials = request(['email', 'password']);
        $token = auth('api')->attempt($credentials);
    
        return $this->respondWithToken($token);
    }

    //Non public
    protected function respondWithToken($token)
    {
         return response()->json([
             'access_token' => $token,
             'token_type' => 'bearer',
             'expires_in' => auth('api')->factory()->getTTL() * 60
         ]);
    }

    private function getHeader($request, $header) {
        return $request->header($header);
    }

    private function getCookie($request, $cookie) {
        return $request->cookie($cookie);
    }
}
