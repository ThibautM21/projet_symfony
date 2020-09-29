<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class FakerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 40; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence($nbWords = 3, $variableNbWords = false));
            $article->setContent($faker->paragraph);
            $manager->persist($article);
            $manager->flush();
        }
    }
}
