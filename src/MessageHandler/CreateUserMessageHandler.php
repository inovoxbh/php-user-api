<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Message\CreateUserMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateUserMessageHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $manager;
    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $manager, ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    public function __invoke(CreateUserMessage $message): int
    {
        $name = $message->getName();
        $email = $message->getEmail();
        $telephones = $message->getTelephones();

        /* instancia classe de usuário */
        $user = new User($name, $email);
        foreach ($telephones as $telephone) {
            $user->addTelephone($telephone['number']);
        }

        /* persistência */
        $this->manager->persist($user);
        $this->manager->flush();


        /* retorno */
        return $user->getId();

//        return new Response('sucesso!!!', Response::HTTP_CREATED, [
//            'Location' => '/users/' . $user->getId()
//        ]);

    }
}
