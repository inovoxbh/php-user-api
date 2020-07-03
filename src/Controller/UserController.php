<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    /**
     * @Route("/users", methods={"GET"})
     */
    public function listAction(): Response
    {
        return new JsonResponse([
            ['id' => 1, 'name' => 'Tales'],
            ['id' => 2, 'name' => 'Augusto'],
            ['id' => 3, 'name' => 'Santos'],
        ]);
    }
    /**
     * @Route("/users/{id}", methods={"GET"})
     */
    public function detailAction(): Response
    {
        return new JsonResponse(['id' => 1, 'name' => 'Tales']);
    }
}