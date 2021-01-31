<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CollectionOuvrage;
use App\Entity\Ouvrage;
use App\Entity\Chapitre;
use App\Entity\Section;
use Faker;

class BiblioFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create();


        $collections = [];
        $books = [];
        $chapters = [];
        $sections = [];
        $titre = ['Harry Potter', 'The Hobbit', 'Hercule Courgette', 'La Famille Adams'];
        for($i = 0; $i <= 3; $i++) {
            $collections[$i] = new CollectionOuvrage();
            $collections[$i]->setTitre($titre[$i]);

            for ($j = 0; $j <= $faker->numberBetween($min = 1, $max = 8); $j++) {
                $books[$j] = new Ouvrage();
                $books[$j]->setTitre($faker->sentence($nbWords = 3, $variableNbWords = true));
                $books[$j]->setAuteur($faker->firstName() . ' ' . $faker->lastName());
                $books[$j]->setCollectionOuvrage($collections[$i]);
                $manager->persist($books[$j]);

                for($k = 0; $k <= $faker->numberBetween($min = 5, $max = 12); $k++) {
                    $chapters[$k] = new Chapitre();
                    $chapters[$k]->setTitre($faker->sentence($nbWords = 2, $variableNbWords = true));
                    $chapters[$k]->setOuvrage($books[$j]);
                    $manager->persist($chapters[$k]);

                    for($l = 0; $l <= $faker->numberBetween($min = 3, $max = 12); $l++) {
                        $sections[$l] = new Section();
                        $sections[$l]->setTexte($faker->sentence($nbWords = $faker->numberBetween($min = 70, $max = 150), $variableNbWords = true));
                        $sections[$l]->setChapitre($chapters[$k]);
                        $manager->persist($sections[$l]);
                    }
                }
            }

            $manager->persist($collections[$i]);
        }
        
        

        $manager->flush();
    }
}
