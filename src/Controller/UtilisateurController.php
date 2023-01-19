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

        $userAvantages = $this->em->getRepository(UserAvantage::class)->findBy(["utilisateur" => $user->getId()]);
        $equipe = $this->em->getRepository(Equipe::class)->find($user->getEquipe()->getId());

        $equipeArray = array(
            'id' => $equipe->getId(),
            'nom' => $equipe->getNom(),
        );

        $userAvantageArray = array();
        foreach ($userAvantages as $userAvantage){
            $userAvantageArray[] = array(
                'id' => $userAvantage->getId(),
                'commentaire' => $userAvantage->getCommentaire(),
                'isValide' => $userAvantage->isIsValide(),
                'create' => $userAvantage->getCreated()->format('d-m-Y H:i'),
                'points' => $userAvantage->getPoints(),
            );
        }

        $userArray = array(
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'role' => $user->getRoles(),
            'avantage' => $userAvantageArray,
            'equipe' => $equipeArray
        );

        return new JsonResponse($userArray);
    }
}
