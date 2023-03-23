<?php

namespace App\Controller;

use App\Entity\Avantage;
use App\Entity\User;
use App\Entity\UserAvantage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AvantageController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
    #[Route('/avantages', name: 'app_avantage')]
    public function index(): JsonResponse
    {
        $avantages = $this->em->getRepository(Avantage::class)->findAll();

        foreach ($avantages as $avantage){
            $avantageArray[] = array(
                'id' => $avantage->getId(),
                'libelle' => $avantage->getLibelle(),
                'points' => $avantage->getPoints(),
                'categorie' => $avantage->getCategorie(),
            );
        }
        return new JsonResponse($avantageArray);
    }

    #[Route('/avantage/{id}', name: 'app_one_avantage')]
    public function indexOne($id): JsonResponse
    {
        $avantage = $this->em->getRepository(Avantage::class)->find($id);

        $avantageArray = array(
            'id' => $avantage->getId(),
            'libelle' => $avantage->getLibelle(),
            'points' => $avantage->getPoints(),
            'categorie' => $avantage->getCategorie(),
        );

        return new JsonResponse($avantageArray);
    }

    #[Route('/create-avantage', name: 'app_create_avantage',methods: "post")]
    public function create(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $avantage = new Avantage();
        $avantage->setPoints($data["points"]);
        $avantage->setCategorie($data["categorie"]);
        $avantage->setLibelle($data["libelle"]);
        $this->em->persist($avantage);
        $this->em->flush();

        $avantageArray = array(
            'id' => $avantage->getId(),
            'libelle' => $avantage->getLibelle(),
            'points' => $avantage->getPoints(),
            'categorie' => $avantage->getCategorie(),
        );

        return new JsonResponse($avantageArray);
    }

    #[Route('/edit-avantage/{id}', name: 'app_edit_avantage',methods: "put|patch")]
    public function edit($id,Request $request): JsonResponse
    {
        $data = $request->toArray();
        $avantage = $this->em->getRepository(Avantage::class)->find($id);
        $avantage->setPoints($data["points"]);
        $avantage->setCategorie($data["categorie"]);
        $avantage->setLibelle($data["libelle"]);
        $this->em->persist($avantage);
        $this->em->flush();

        $avantageArray = array(
            'id' => $avantage->getId(),
            'libelle' => $avantage->getLibelle(),
            'points' => $avantage->getPoints(),
            'categorie' => $avantage->getCategorie(),
        );

        return new JsonResponse($avantageArray);
    }

    #[Route('/delete-avantage/{id}', name: 'app_del_avantage',methods: "delete")]
    public function delete($id): JsonResponse
    {
        try {
            $avantage = $this->em->getRepository(Avantage::class)->find($id);
            $this->em->remove($avantage);
            $this->em->flush();

            return new JsonResponse([
                'status' => "success",
                'code' => 200
            ]);
        }catch (\Exception $e){
            return $e;
        }
    }

    #[Route('/nb-avantages-non-valide', name: 'app_nb_avantage_pas_valide',methods: "get")]
    public function getNbAvantagePasValide(): JsonResponse
    {
        $userAvantages = $this->em->getRepository(UserAvantage::class)->findBy(['isValide' => false]);

        return new JsonResponse(count($userAvantages));
    }

    #[Route('/avantages-non-valide', name: 'app_avantage_pas_valide',methods: "get")]
    public function getAvantagePasValide(): JsonResponse
    {
        try{
            $userAvantages = $this->em->getRepository(UserAvantage::class)->findBy(['isValide' => false]);
            foreach ($userAvantages as $userAvantage) {
                $user = $userAvantage->getUtilisateur();

                $avantageArray = array(
                    'id' => $userAvantage->getAvantage()->getId(),
                    'libelle' => $userAvantage->getAvantage()->getLibelle(),
                    'points' => $userAvantage->getAvantage()->getPoints(),
                    'categorie' => $userAvantage->getAvantage()->getCategorie(),
                );
                $userArray = array(
                    'id' => $user->getId(),
                    'nom' => $user->getNom(),
                    'prenom' => $user->getPrenom(),
                    'points' => $user->getPoints(),
                );
                $userAvantageArray[] = array(
                    'id' => $userAvantage->getId(),
                    'commentaire' => $userAvantage->getCommentaire(),
                    'points' => $userAvantage->getPoints(),
                    'created' => $userAvantage->getCreated()->format('Y-m-d\\TH:i:s'),
                    'isValide' => $userAvantage->isIsValide(),
                    'avantage' => $avantageArray,
                    'user' => $userArray,
                );
            }
            return new JsonResponse($userAvantageArray);
        }catch (\Exception $e) {
            return new JsonResponse("pas d'avantage à valider");
        }

    }

    #[Route('/avantages-accept/{id}', name: 'app_avantage_valide',methods: "put")]
    public function accept($id){
        try {
            $userAvantage = $this->em->getRepository(UserAvantage::class)->find($id);
            $userAvantage->setIsValide(true);
            $this->em->persist($userAvantage);
            $this->em->flush();

            return new JsonResponse([
                'status' => "success",
                'code' => 200
            ]);
        }catch (\Exception $e){
            return $e;
        }
    }
}
