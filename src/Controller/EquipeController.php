<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\User;
use App\Entity\UserAvantage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class EquipeController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    #[Route('/equipes', name: 'app_equipe')]
    public function index():JsonResponse
    {
        $equipes = $this->em->getRepository(Equipe::class)->findAll();

        foreach ($equipes as $equipe) {
            $equipeArray[] = array(
                'id' => $equipe->getId(),
                'nom' => $equipe->getNom(),
                'isSenior' => $equipe->isIsSenior(),
                'cotisationBase' => $equipe->getCotisationBase()
            );
        }
        return new JsonResponse($equipeArray);
    }

    #[Route('/equipe/{id}', name: 'app_equipe_one')]
    public function indexOne($id): JsonResponse
    {
        $equipe = $this->em->getRepository(Equipe::class)->find($id);
        $joueurs = $equipe->getUsers();

        foreach ($joueurs as $joueur){
            $points = 0;
            $userAvantages = $this->em->getRepository(UserAvantage::class)->findBy(["utilisateur" => $joueur->getId(),"isValide" => true]);
            foreach ($userAvantages as $userAvantage){
                $points = $userAvantage->getPoints();
            }
            $arrayJoueur[] = array(
                'id' => $joueur->getId(),
                'nom' => $joueur->getNom(),
                'prenom' => $joueur->getPrenom(),
                'username' => $joueur->getUsername(),
                'point' => $points
            );
        }

        $equipeArray = array(
            'id' => $equipe->getId(),
            'nom' => $equipe->getNom(),
            'isSenior' => $equipe->isIsSenior(),
            'cotisationBase' => $equipe->getCotisationBase(),
            'users' => $arrayJoueur
        );

        return new JsonResponse($equipeArray);
    }

    #[Route('/create-equipe', name: 'app_equipe_create', methods: "post")]
    public function create(Request $request): JsonResponse
    {
        $data = $request->toArray();

        $equipe = new Equipe();
        $equipe->setNom($data['nom']);
        $equipe->setCotisationBase($data['cotisationBase']);
        $equipe->setIsSenior($data['isSenior']);
        $this->em->persist($equipe);
        $this->em->flush();

        $equipeArray = array(
            'id' => $equipe->getId(),
            'nom' => $equipe->getNom(),
            'cotisation_base' => $equipe->getCotisationBase(),
            'is_senior' => $equipe->isIsSenior(),
        );

        return new JsonResponse($equipeArray);
    }

    #[Route('/edit-equipe/{id}', name: 'app_equipe_edit', methods: "put|patch")]
    public function edit(Request $request,$id): JsonResponse
    {
        $equipe = $this->em->getRepository(Equipe::class)->find($id);

        $data = $request->toArray();

        $equipe->setNom($data['nom']);
        $equipe->setCotisationBase($data['cotisationBase']);
        $equipe->setIsSenior($data['isSenior']);
        $this->em->persist($equipe);
        $this->em->flush();

        $equipeArray = array(
            'id' => $equipe->getId(),
            'nom' => $equipe->getNom(),
            'cotisation_base' => $equipe->getCotisationBase(),
            'is_senior' => $equipe->isIsSenior(),
        );

        return new JsonResponse($equipeArray);
    }

    #[Route('/delete-equipe/{id}', name: 'app_equipe_delete', methods: "delete")]
    public function delete($id): JsonResponse
    {
        try {
            $equipe = $this->em->getRepository(Equipe::class)->find($id);
            $this->em->remove($equipe);
            $this->em->flush();
            return new JsonResponse([
                'status' => 'success',
                'code' => '200'
            ]);
        }catch (\Exception $e){
            return $e;
        }
    }
}
