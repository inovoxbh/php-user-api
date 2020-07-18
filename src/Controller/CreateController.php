<?php

namespace App\Controller;

use App\Message\CreateUserMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class CreateController extends AbstractController
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }


    /**
     * @Route("/users", methods={"POST"})
     */
    public function createAction(Request $request): Response
    {
        /* conteúdo da requisição */
        $requestContent = $request->getContent();

        /* transforma requisição em json */
        $json = json_decode($requestContent, true);

        $envelope = $this->bus->dispatch(new CreateUserMessage($json['name'],$json['email'],$json['telephones']));

        $handledStamp = $envelope->last(HandledStamp::class);
        $createdId = $handledStamp->getResult();

        return new Response('', Response::HTTP_CREATED, [
            'Location' => '/users/' . $createdId
        ]);
    }
}