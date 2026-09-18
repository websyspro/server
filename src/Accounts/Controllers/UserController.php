<?php

namespace Websyspro\Server\Accounts\Controllers;

use Websyspro\Server\Accounts\Models\UserModel;
use Websyspro\Server\Accounts\Services\UserService;
use Websyspro\WorkerServer\Decorators\Body;
use Websyspro\WorkerServer\Decorators\Controller;
use Websyspro\WorkerServer\Decorators\Delete;
use Websyspro\WorkerServer\Decorators\Get;
use Websyspro\WorkerServer\Decorators\Patch;
use Websyspro\WorkerServer\Decorators\Post;
use Websyspro\WorkerServer\Decorators\Put;
use Websyspro\WorkerServer\Request;
use Websyspro\WorkerServer\Response;

#[Controller("users")]
class UserController
{
  public function __construct(
    private UserService $userService
  ){}

  #[Get("/")]
  public function index(
    #[Body("email")] string $email
  ): mixed {
    return $this->userService->findAll($email);
  }

  #[Get("/test")]
  public function indexTest(
  ): mixed {
    sleep(20);
    return [ "Put Test" ];
  }    

  #[Get("/:id")]
  public function show(
  ): Response {
    $user = $this->userService->findById();
    if( $user === null ){
      return Response::json([ 'error' => 'not found' ], 404 );
    }

    return Response::json($user);
  }

  #[Post("/")]
  public function create(
  ): mixed {
    return $this->userService->findById();
  }

  #[Put("/:id")]
  public function update(
    Request $request, 
    #[Body] UserModel $user
  ): Response {
    return Response::json([
      'updated' => $request->params['id'], 
      'data' => ['name' => $user->name]
    ]);
  }

  #[Patch("/:id")]
  public function patch(
    Request $request,
    #[Body] UserModel $user
  ): Response {
    return Response::json([
      'patched' => $request->params['id'], 
      'data' => ['name' => $user->name]
    ]);
  }

  #[Delete("/:id")]
  public function destroy(
    Request $request
  ): Response {
    return Response::json([
      'deleted' => $request->params['id']
    ]);
  }
}
