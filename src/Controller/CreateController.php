<?php

namespace App\Controller;

use App\Message\CreateUserMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateController extends AbstractController
{
    private MessageBusInterface $bus;
    private ValidatorInterface $validator;
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator, MessageBusInterface $bus)
    {
        $this->manager = $manager;
        $this->validator = $validator;
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