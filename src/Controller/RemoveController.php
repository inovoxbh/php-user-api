<?php

namespace App\Controller;

use App\Message\RemoveUserMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class RemoveController extends AbstractController
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @Route("/users/{id}", methods={"DELETE"})
     */
    public function removeAction(int $id): Response
    {
        $this->bus->dispatch(new RemoveUserMessage($id));
        return new Response('Usuario ID #'. $id . ' removido com sucesso.', Response::HTTP_OK);
    }
}