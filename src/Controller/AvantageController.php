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
    #[Route('/avantage-user/{idUser}', name: 'app_user_avantage',methods: "get")]
    public function userAvantage($idUser): JsonResponse
    {
        $user = $this->em->getRepository(User::class)->find($idUser);
        $userAvantages = $this->em->getRepository(UserAvantage::class)->findBy(["utilisateur" => $user->getId(),"isValide" => true]);
        foreach($userAvantages as $userAvantage){
            $userAvantageArray[] = array(
                'id' => $userAvantage->getId(),
                'commentaire' => $userAvantage->getCommentaire(),
                'points' => $userAvantage->getPoints(),
                'created' => $userAvantage->getCreated()->format('d/m/Y'),
                'isValide' => $userAvantage->isIsValide(),
                'idAvantage' => $userAvantage->getAvantage()->getId()
            );
        }

        return new JsonResponse($userAvantageArray);
    }


}
