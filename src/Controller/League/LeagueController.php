<?php

namespace Guess\Controller\League;

use Guess\Domain\League\LeagueRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class LeagueController extends AbstractController
{
    public function list(LeagueRepositoryInterface $leagueRepository): JsonResponse
    {
//        $leagues = $leagueRepository->findAll();
//
//        $return = [];
//        foreach ($leagues as $league) {
//            $return[] = $league->toArray();
//        }
//
//        dd($return);
        return new JsonResponse($leagueRepository->all());
    }
}
