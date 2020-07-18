<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\UpdateUserMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class UpdateController extends AbstractController
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @Route("/users/{id}", methods={"PUT"})
     */
    public function updateAction(Request $request, int $id): Response
    {
        /* conteúdo da requisição */
        $requestContent = $request->getContent();

        /* transforma requisição em json */
        $json = json_decode($requestContent, true);

        /* bus de atualização */
        $this->bus->dispatch(new UpdateUserMessage($id,$json['name'],$json['email']));

        /* retorno */
        return new Response('', Response::HTTP_OK);
    }
}