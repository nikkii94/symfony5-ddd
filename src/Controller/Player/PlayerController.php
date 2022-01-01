<?php

namespace Guess\Controller\Player;

use Exception;
use Guess\Application\Player\CreatePlayerHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PlayerController extends AbstractController
{
    private CreatePlayerHandler $createPlayerHandler;

    public function __construct(CreatePlayerHandler $createPlayerHandler)
    {
        $this->createPlayerHandler = $createPlayerHandler;
    }

    public function index(Request $request): JsonResponse
    {
        $playerArray = json_decode($request->getContent(), true);

        try {
            $this->createPlayerHandler->handle(
                [
                    'username' => $playerArray['username'],
                    'email' => $playerArray['email'],
                    'password' => $playerArray['password'],
                    'avatar' => 2
                ]
            );
        } catch (Exception $exception) {
            return new JsonResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse('User created', Response::HTTP_OK);
    }

    public function list(): JsonResponse
    {
        return new JsonResponse();
    }
}
