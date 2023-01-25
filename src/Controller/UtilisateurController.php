<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\User;
use App\Entity\UserAvantage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    #[Route('/utilisateur/{id}', name: 'app_utilisateur_one')]
    public function index($id): JsonResponse
    {
        $user = $this->em->getRepository(User::class)->find($id);

        $userArray = array(
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'role' => $user->getRoles(),
        );

        return new JsonResponse($userArray);
    }

    #[Route('/username/{username}', name: 'app_utilisateur_one_username')]
    public function indexUsername($username): JsonResponse
    {
        $user = $this->em->getRepository(User::class)->findByUsername($username);

        $userArray = array(
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'role' => $user->getRoles(),
        );

        return new JsonResponse($userArray);
    }
}
