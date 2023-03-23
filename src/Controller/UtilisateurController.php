<?php

namespace App\Controller;

use App\Entity\Avantage;
use App\Entity\Equipe;
use App\Entity\User;
use App\Entity\UserAvantage;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Request;
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

    #[Route('/utilisateurs', name: 'app_utilisateurs')]
    public function indexAll(): JsonResponse
    {
        $users = $this->em->getRepository(User::class)->findAll();
        foreach ($users as $user){
            $userArray[] = array(
                'id' => $user->getId(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'role' => $user->getRoles(),
            );
        }

        return new JsonResponse($userArray);
    }

    #[Route('/utilisateurs/avantages/{idUser}', name: 'app_utilisateurs_avantages')]
    public function indexWithAvantages($idUser): JsonResponse
    {
        $user = $this->em->getRepository(User::class)->find($idUser);
        $usersAvantages = $this->em->getRepository(UserAvantage::class)->findBy(["utilisateur" => $user]);
        if($usersAvantages == null){
            return new JsonResponse([
                "status" => "pas d'avantages",
                "code" => 200
            ]);
        }else {
            foreach ($usersAvantages as $userAvantage) {
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
        }
    }

    #[Route('/utilisateur/{id}', name: 'app_utilisateur_one')]
    public function indexOne($id): JsonResponse
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

    #[Route('/utilisateur/{idUser}/avantages/{idAvantage}', name: 'app_utilisateur_avantages')]
    public function addUserAvantage($idUser,$idAvantage,\Symfony\Component\HttpFoundation\Request $request):JsonResponse
    {
        $data = $request->toArray();
        $user = $this->em->getRepository(User::class)->find($idUser);
        $avantage = $this->em->getRepository(Avantage::class)->find($idAvantage);

        $userAvantage = new UserAvantage();
        $userAvantage->setUtilisateur($user);
        $userAvantage->setAvantage($avantage);
        if (isset($data['commentaire'])){
            $userAvantage->setCommentaire($data['commentaire']);
        }else{
            $userAvantage->setCommentaire("Pas de commentaire");
        }
        $userAvantage->setPoints($data['points']);
        $userAvantage->setIsValide(true);
        $userAvantage->setCreated(new \DateTime($data['created']));
        $this->em->persist($userAvantage);
        $this->em->flush();

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
        $userAvantageArray = array(
            'id' => $userAvantage->getId(),
            'commentaire' => $userAvantage->getCommentaire(),
            'points' => $userAvantage->getPoints(),
            'created' => $userAvantage->getCreated()->format('Y-m-d\\TH:i:s'),
            'isValide' => $userAvantage->isIsValide(),
            'avantage' => $avantageArray,
            'user' => $userArray,
        );

        return new JsonResponse($userAvantageArray);
    }

    #[Route('/delete-userAvantage/{idUserAvantage}', name: 'app_delete_utilisateur_avantages',methods: "delete")]
    public function deleteUserAvantage($idUserAvantage):JsonResponse
    {
        //delete userAvantage in a try catch where the catch return the error
        $userAvantage = $this->em->getRepository(UserAvantage::class)->find($idUserAvantage);
        try {
            $this->em->remove($userAvantage);
            $this->em->flush();
            return new JsonResponse([
                'status' => 'success',
                'code' => 200,
                ]);
        }catch (\Exception $e){
            return new JsonResponse($e->getMessage());
        }
    }

    #[Route('/get-user-role/{idUser}', name: 'app_get_user_role')]
    public function getUserRole($idUser):JsonResponse
    {
        $user = $this->em->getRepository(User::class)->find($idUser);
        $role = array(
            'role' => $user->getRoles(),
        );

        return new JsonResponse($role);
    }

    #[Route('/get-user-equipe/{idUser}', name: 'app_get_user_equipe')]
    public function getUserEquipe($idUser):JsonResponse
    {
        $user = $this->em->getRepository(User::class)->find($idUser);
        $equipe = $user->getEquipe();
        $equipeArray = array(
            'id' => $equipe->getId(),
            'nom' => $equipe->getNom(),
            'cotisationBase' => $equipe->getCotisationBase(),
            'isSenior' => $equipe->isIsSenior(),
        );

        return new JsonResponse($equipeArray);
    }

    #[Route('/utilisateurs/avantages/valide/{idUser}', name: 'app_utilisateurs_avantages_valide')]
    public function indexWithAvantagesValide($idUser): JsonResponse
    {
        $user = $this->em->getRepository(User::class)->find($idUser);
        $usersAvantages = $this->em->getRepository(UserAvantage::class)->findBy(["utilisateur" => $user,"isValide" => true]);
        if($usersAvantages == null){
            return new JsonResponse([
                "status" => "pas d'avantages",
                "code" => 200
            ]);
        }else {
            foreach ($usersAvantages as $userAvantage) {
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
        }
    }
}
