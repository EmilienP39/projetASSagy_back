<?php

namespace App\DataFixtures;

use App\Entity\Avantage;
use App\Entity\Equipe;
use App\Entity\User;
use App\Entity\UserAvantage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $avantage = new Avantage();
        $avantage->setLibelle("nettoyer les maillots");
        $avantage->setPoints(30);
        $avantage->setCategorie(0); //0 les deux  1 sénior  2 jeune
        $manager->persist($avantage);

        $avantage2 = new Avantage();
        $avantage2->setLibelle("dégradation du matériel");
        $avantage2->setPoints(-25);
        $avantage2->setCategorie(0); //0 les deux  1 sénior  2 jeune
        $manager->persist($avantage2);

        $user = new User();
        $user->setNom("RATINIER");
        $user->setPrenom("Toufick");
        $user->setUsername("tratinier");
        $user->setPassword('$2y$10$aeRNEFl/oY5UMLWpMxTbv.w7eftHNIOZdQt1tzuf1JA2QromgfF0q');
        $user->setRoles(["Joueur"]);
        $user->setPoints(0);
        $manager->persist($user);

        $user2 = new User();
        $user2->setNom("BOUZOULOUF");
        $user2->setPrenom("Abdel");
        $user2->setUsername("admin");
        $user2->setPassword('$2y$10$aeRNEFl/oY5UMLWpMxTbv.w7eftHNIOZdQt1tzuf1JA2QromgfF0q');
        $user2->setRoles(["Admin"]);
        $user2->setPoints(0);
        $manager->persist($user2);

        $equipe = new Equipe();
        $equipe->setNom("U15");
        $equipe->setIsSenior(false);
        $equipe->setCotisationBase(70);
        $manager->persist($equipe);
        $user->setEquipe($equipe);

        $userAvantage1 = new UserAvantage();
        $userAvantage1->setPoints($avantage->getPoints());
        $userAvantage1->setCreated(new \DateTime('now'));
        $userAvantage1->setCommentaire("");
        $userAvantage1->setIsValide(true);
        $userAvantage1->setUtilisateur($user);
        $userAvantage1->setAvantage($avantage);
        $manager->persist($userAvantage1);

        $manager->flush();
    }
}
