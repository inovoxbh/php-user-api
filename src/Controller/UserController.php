<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function listAction(): Response
    {
        return new JsonResponse([
            ['id' => 1, 'name' => 'Bruno'],
            ['id' => 2, 'name' => 'Goncalves'],
            ['id' => 3, 'name' => 'de Alcantara'],
        ]);
    }
}