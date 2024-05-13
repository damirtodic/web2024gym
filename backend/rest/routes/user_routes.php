<?php
require_once __DIR__ . '/../services/UserService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::set('user_service', new UserService());
/**
 * @OA\Post(
 *      path="/register",
 *      tags={"register"},
 *      summary="Register",
 *      description="Endpoint to register a new user.",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  required={"name", "dob", "email", "password"},
 *                  @OA\Property(
 *                      property="name",
 *                      type="string",
 *                      description="User's name"
 *                  ),
 *                  @OA\Property(
 *                      property="dob",
 *                      type="string",
 *                      format="date",
 *                      description="User's date of birth (YYYY-MM-DD)"
 *                  ),
 *                  @OA\Property(
 *                      property="email",
 *                      type="string",
 *                      format="email",
 *                      description="User's email address"
 *                  ),
 *                  @OA\Property(
 *                      property="password",
 *                      type="string",
 *                      description="User's password"
 *                  ),
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="Successful registration"
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Bad request"
 *      ),
 *      @OA\Response(
 *           response=500,
 *           description="Internal server error"
 *      ),
 * )
 */
Flight::route('POST /register', function(){
    $payload = Flight::request()->data;

    if (empty($payload['name']) || empty($payload['dob']) || empty($payload['password']) || empty($payload['email'])) {
        Flight::json(['error' => 'One or more required fields are missing'], 400);
        return;
    }
    if (!filter_var($payload['email'], FILTER_VALIDATE_EMAIL)) {
        Flight::json(['error' => 'Invalid email format'], 400);
        return;
    }

    $user_service = Flight::get('user_service');
    $new_user = $user_service->add_user($payload);

    if ($new_user !== false) {
        Flight::json(['message'=>'Successfully registered user', 'data'=>$new_user]);
    } else {
        Flight::json(['error' => 'Failed to register user'], 500);
    }
});

/**
 * @OA\Post(
 *      path="/login",
 *      tags={"login"},
 *      summary="Login",
 *      description="Login endpoint to authenticate users.",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\MediaType(
 *              mediaType="application/x-www-form-urlencoded",
 *              @OA\Schema(
 *                  required={"email", "password"},
 *                  @OA\Property(
 *                      property="email",
 *                      type="string",
 *                      description="User's email address"
 *                  ),
 *                  @OA\Property(
 *                      property="password",
 *                      type="string",
 *                      description="User's password"
 *                  ),
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="Successful login"
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Bad request"
 *      ),
 *      @OA\Response(
 *           response=401,
 *           description="Unauthorized"
 *      ),
 * )
 */
Flight::route('POST /login', function(){
    $payload = Flight::request()->data;

    if (empty($payload['email']) || empty($payload['password'])) {
        Flight::json(['error' => 'Email or password is missing'], 400);
        return;
    }

    $user_service = Flight::get('user_service');
    $login_result = $user_service->login_user($payload['email'], $payload['password']);

    if ($login_result !== false) {
        Flight::json(['message'=>'Successfully logged in', 'data'=>$login_result]);
    } else {
        Flight::json(['error' => 'Invalid email or password'], 401);
    }
});


 /**
     * @OA\Post(
     *      path="/logout",
     *      tags={"auth"},
     *      summary="Logout from the system",
     *      security={
     *          {"ApiKey": {}}   
     *      },
     *      @OA\Response(
     *           response=200,
     *           description="Success response or exception if unable to verify jwt token"
     *      ),
     * )
     */
    Flight::route('POST /logout', function() {
        try {
            $token = Flight::request()->getHeader("Authentication");
            if(!$token)
                Flight::halt(401, "Missing authentication header");

            $decoded_token = JWT::decode($token, new Key(JWT_SECRET, 'HS256'));

            Flight::json([
                'jwt_decoded' => $decoded_token,
                'user' => $decoded_token->user
            ]);
        } catch (\Exception $e) {
            Flight::halt(401, $e->getMessage());
        }
    });


 /**
     * @OA\Get(
     *      path="/employees",
     *      tags={"employees"},
     *      summary="Get all employees",
     *      @OA\Response(
     *           response=200,
     *           description="Get all employees"
     *      ),
     * )
     */
Flight::route('GET /employees', function(){
    $employees = Flight::get('user_service')->fetch_employees();
    $team = [];

    foreach ($employees as $employee) {
        $social_links = [
            'facebook' => $employee['facebook'],
            'twitter' => $employee['twitter'],
            'youtube' => $employee['youtube'],
            'instagram' => $employee['instagram'],
            'email' => $employee['email']
        ];

        $employee_data = [
            'name' => $employee['name'],
            'position' => $employee['position'],
            'image' => $employee['image'],
            'social' => $social_links
        ];

        $team[] = $employee_data;
    }

    header('Content-Type: application/json');
    echo json_encode(['team' => $team], JSON_PRETTY_PRINT);
});
/**
 * @OA\Delete(
 *      path="/user/{id}",
 *      tags={"user"},
 *      summary="Delete User",
 *      description="Delete a user by ID.",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="User ID",
 *          required=true,
 *          @OA\Schema(
 *              type="integer",
 *              format="int64"
 *          )
 *      ),
 *      @OA\Response(
 *           response=200,
 *           description="User deleted successfully"
 *      ),
 *      @OA\Response(
 *           response=400,
 *           description="Bad request"
 *      ),
 *      @OA\Response(
 *           response=500,
 *           description="Internal server error"
 *      ),
 * )
 */
Flight::route('DELETE /user/@id', function($id){
    $user_service = Flight::get('user_service');
    $delete_result = $user_service->delete_user($id);

    if ($delete_result) {
        Flight::json(['message'=>'User deleted successfully']);
    } else {
        Flight::json(['error' => 'Failed to delete user'], 500);
    }
});
?>
