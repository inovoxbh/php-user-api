<?php
namespace App\Controller;
use App\Entity\Telephone;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class UserController extends AbstractController
{
    private EntityManagerInterface $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    /**
     * @Route("/users", methods={"GET"})
     */
    public function listAction(): Response
    {
        $users = $this->manager->getRepository(User::class)->findAll();
        $data = [];
        foreach ($users as $user) {
            $data[] = $this->userToArray($user);
        }
        return new JsonResponse($data);
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
     * @Route("/users", methods={"POST"})
     */
    public function createAction(Request $request): Response
    {
        $requestContent = $request->getContent();
        $json = json_decode($requestContent, true);
        $user = new User($json['name'], $json['email']);
        foreach ($json['telephones'] as $telephone) {
            $user->addTelephone($telephone['number']);
        }
        $this->manager->persist($user);
        $this->manager->flush();
        return new Response('', Response::HTTP_CREATED);
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