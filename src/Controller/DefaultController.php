<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index(Request $request): Response {
        $nome = $request->query->get('nome');
        $sobrenome = $request->query->get('sobrenome');
        /* para testar: http://localhost:8080/?nome=lucas&sobrenome=alcantara */

//        return $this->render('base.html.twig',[
//            'nome' => $nome,
//            'sobrenome' => $sobrenome
//        ]);

        return new JsonResponse([
            'name' => $request->query->get('nome')
        ]);
    }
}