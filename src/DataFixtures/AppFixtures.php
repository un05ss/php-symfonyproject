<?php
namespace App\DataFixtures;

use App\Entity\Professeur;
use App\Entity\Groupe;
use App\Entity\Etudiant;
use App\Entity\Seance;
use App\Entity\Module;
use App\Entity\Lecon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Professeur
        $prof = new Professeur();
        $prof->setUsername("admin");
        $prof->setPassword("0000");
        $manager->persist($prof);

        // Groupes + étudiants + séances
        $groupes = ["G6","G4","G10"];
        $noms = ["Yassine","Fatima","Omar","Hajar","Mohamed","Imane","Rachid","Nadia","Soufiane","Salma",
                 "Khalid","Samira","Hamza","Amina","Youssef","Meryem","Abdelaziz","Sanae","Karim","Houda",
                 "Reda","Latifa","Zakaria","Ikram","Anas","Sara","Mustapha","Laila","Ayoub","Zineb"];
        $i=0;
        foreach($groupes as $g){
            $grp = new Groupe();
            $grp->setNom($g);
            $manager->persist($grp);

            for($j=0;$j<10;$j++){
                $et = new Etudiant();
                $et->setNom($noms[$i++]);
                $et->setGroupe($g);
                $manager->persist($et);
            }

            $s1 = new Seance();
            $s1->setTitre("Rattrapage ".$g." - Math");
            $s1->setGroupe($g);
            $manager->persist($s1);

            $s2 = new Seance();
            $s2->setTitre("Rattrapage ".$g." - Physique");
            $s2->setGroupe($g);
            $manager->persist($s2);
        }

        // Modules + leçons
        $modules = [
            "Programmation C++" => ["Intro","Classes","Héritage","Polymorphisme","Projet final"],
            "Base de données" => ["SQL Intro","Jointures","Index","Transactions","Optimisation"],
            "Web Symfony" => ["Installation","Controller","Twig","Doctrine","Formulaires"]
        ];
        foreach($modules as $nom=>$lecons){
            $m = new Module();
            $m->setNom($nom);
            $manager->persist($m);
            foreach($lecons as $l){
                $lec = new Lecon();
                $lec->setTitre($l);
                $lec->setModule($nom);
                $manager->persist($lec);
            }
        }

        $manager->flush();
    }
}