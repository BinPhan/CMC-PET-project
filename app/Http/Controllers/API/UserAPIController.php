<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Response;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */

/**
 * @OA\Schema(
 *  schema="User",
 *  title="User schema",
 * 	@OA\Property(
 * 		property="name",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="email",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="password",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="role",
 * 		type="integer"
 * 	),
 * )
 */
class UserAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     *
     *
     * @OA\Get(
     *     summary="Get list user",
     *     operationId="List user",
     *     security={{"sanctum":{}}},
     *     tags={"User"},
     *     path="/api/users",
     *     description="Get list of users",
     *     @OA\Parameter(
     *          name="seach params",
     *          description="Search params",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              ref="#/components/schemas/User"
     *          )
     *      ),
     *     @OA\Response(
     *      response="200",
     *      description="User list",
     *      @OA\JsonContent(
     *              ref="#/components/schemas/User"
     *      )
     *     )
     * )
     */
    public function index(Request $request)
    {

        $users = $this->userRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($users->toArray(), 'Users retrieved successfully');
    }

    /**
     * Store a newly created User in storage.
     * POST /users
     *
     * @OA\Post(
     *  tags={"User"},
     *  operationId="Create User",
     *  path="/api/users",
     *  security={{"sanctum":{}}},
     *
     *  @OA\RequestBody(
     *      @OA\JsonContent(
     *              ref="#/components/schemas/User"
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success"
     *  ),
     *  @OA\Response(
     *      response=500,
     *      description="Internal error"
     *  )
     * )
     *
     * @param CreateUserAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUserAPIRequest $request)
    {
        $input = $request->all();

        $input['password'] = \bcrypt($input['password']);

        $user = $this->userRepository->create($input);

        return $this->sendResponse($user->toArray(), 'User saved successfully');
    }

    /**
     * Display the specified User.
     * GET|HEAD /users/{id}
     *
     * @OA\Get(
     *
     *  tags={"User"},
     *  operationId="Show User",
     *  path="/api/users/{id}",
     *  security={{"sanctum":{}}},
     *
     *  @OA\Parameter(
     *      name="id",
     *      in="path",
     *      @OA\Schema(
     *              type="integer"
     *      )
     *  ),
     *  @OA\RequestBody(
     *
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success"
     *  ),
     *  @OA\Response(
     *      response=500,
     *      description="Internal error"
     *  )
     * )
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        return $this->sendResponse($user->toArray(), 'User retrieved successfully');
    }

    /**
     * Update the specified User in storage.
     * PUT/PATCH /users/{id}
     *
     * @param int $id
     * @param UpdateUserAPIRequest $request
     *
     * @return Response
     *
     * @OA\Put(
     *  path="/api/users/{id}",
     *  tags={"User"},
     *  operationId="Edit user",
     *  security={{"sanctum":{}}},
     *  @OA\Parameter(
     *      name="id",
     *      in="path"
     *  ),
     *
     *  @OA\RequestBody(
     *      @OA\JsonContent(
     *              ref="#/components/schemas/User"
     *      )
     *  ),
     *  @OA\Response(
     *      response=200,
     *      description="Success"
     *  ),
     *  @OA\Response(
     *      response=500,
     *      description="Internal error"
     *  )
     * )
     */
    public function update($id, UpdateUserAPIRequest $request)
    {
        $input = $request->all();

        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $input['password'] = \bcrypt($input['password']);

        $user = $this->userRepository->update($input, $id);

        return $this->sendResponse($user->toArray(), 'User updated successfully');
    }

    /**
     * Remove the specified User from storage.
     * DELETE /users/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     *
     *      @OA\Delete(
     *      path="/api/users/{id}",
     *      operationId="delete user",
     *      tags={"User"},
     *      summary="Delete existing user",
     *      security={{"sanctum":{}}},
     *
     *      description="Deletes an user and returns no content",
     *      @OA\Parameter(
     *          name="id",
     *          description="User id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     *
     */
    public function destroy($id)
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user->delete();

        return $this->sendSuccess('User deleted successfully');
    }

    /**
     * Login user.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @OA\Post(
     *
     *  path="/api/login",
     *  summary="Login",
     *  tags={"User"},
     *  operationId="login",
     *  description="Login",
     *
     *  @OA\RequestBody(
     *      request="true",
     *      required=true,
     *      description="User credentials",
     *      @OA\JsonContent(
     *          required={"email", "password"},
     *          @OA\Property(property="email", type="string", format="email", example="bin@gmail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="123"),
     *      )
     *  ),
     *  @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again"),
     *      )
     *    ),
     *  @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example=""),
     *      )
     *    )
     * )
     *
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'status_code' => 500,
                    'message' => 'Unauthorized'
                ]);
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Error in Login');
            }

            $tokenResult = $user->createToken('authToken', config('api-permission')[$user->role])->plainTextToken;

            return response()->json([
                'status_code' => 200,
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $error) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Error in Login',
                'error' => $error,
            ]);
        }
    }
}
