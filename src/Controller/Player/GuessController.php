<?php

namespace Guess\Controller\Player;

use Exception;
use Guess\Application\Handler\Player\MakeAGuessHandler;
use Guess\Domain\Player\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GuessController extends AbstractController
{
    public function __construct(private MakeAGuessHandler $guessHandler) {}

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        $guessArray = json_decode($request->getContent(), true);

        /** @var Player $player */
        $player = $this->getUser();

        $this->guessHandler->handle(
            [
                'gameId' => $guessArray['gameId'],
                'guess' => $guessArray['guess'],
                'username' => $player->getUsername()
            ]
        );

        return new JsonResponse('Guess has been made');
    }
}
