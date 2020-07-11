<?php

namespace App\Controller;

use App\Entity\Telephone;
use App\Entity\User;
use App\Message\CreateUserMessage;
use App\Message\RemoveUserMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
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
     * @Route("/", methods={"GET"})
     */
    public function homePage(): Response
    {
        $response ='bem vindo ao app de usuarios em php.';

        return new JsonResponse($response);
    }

    /**
     * @Route("/users/{id}", methods={"GET"})
     */
    public function detailAction(int $id): Response
    {
        $user = $this->manager->getRepository(User::class)->find($id);
        if (null === $user) {
            throw $this->createNotFoundException('User with ID #' . $id . ' not found');
        }
        return new JsonResponse($this->userToArray($user));
    }

    /**
     * @Route("/users/{id}", methods={"PUT"})
     */
    public function updateAction(Request $request, int $id): Response
    {
        $requestContent = $request->getContent();
        $json = json_decode($requestContent, true);
        $user = $this->manager->getRepository(User::class)->find($id);
        if (null === $user) {
            throw $this->createNotFoundException('User with ID #' . $id . ' not found');
        }
        $user->setName($json['name']);
        $user->setEmail($json['email']);
        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $violations = array_map(fn(ConstraintViolationInterface $violation) => [
                'property' => $violation->getPropertyPath(),
                'message' => $violation->getMessage()
            ], iterator_to_array($errors));
            return new JsonResponse($violations, Response::HTTP_BAD_REQUEST);
        }
        $this->manager->persist($user);
        $this->manager->flush();
        return new Response('', Response::HTTP_OK);
    }

    private function userToArray(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'telephones' => array_map(fn(Telephone $telephone) => [
                'number' => $telephone->getNumber()
            ], $user->getTelephones()->toArray())
        ];
    }
}