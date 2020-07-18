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
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager, MessageBusInterface $bus)
    {
        $this->manager = $manager;
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

        /* verifica se usuário existe */
        $user = $this->manager->getRepository(User::class)->find($id);
        if (null === $user) {
            throw $this->createNotFoundException('User with ID #' . $id . ' not found for a update.');
        }

        /* bus de atualização */
        $this->bus->dispatch(new UpdateUserMessage($id,$json['name'],$json['email']));

        /* retorno */
        return new Response('', Response::HTTP_OK);
    }
}