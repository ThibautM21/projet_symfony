<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 20; $i++) {
            $article = new Article();
            $article->setTitle('Title ' . $i)
                ->setContent('Consectetur ex cupidatat velit adipisicing et est tempor labore qui consectetur cupidatat anim nulla. Incididunt fugiat dolor minim incididunt laboris anim exercitation et quis ut reprehenderit. In ipsum cillum deserunt do amet exercitation anim. Consequat consectetur id ex officia laboris excepteur nostrud cupidatat. Excepteur aute in culpa voluptate quis exercitation excepteur in. Reprehenderit nostrud dolor laborum nulla officia irure veniam consequat magna et laborum commodo duis tempor. Nulla aliquip aute ipsum minim cillum. Consectetur ex cupidatat velit adipisicing et est tempor labore qui consectetur cupidatat anim nulla. Incididunt fugiat dolor minim incididunt laboris anim exercitation et quis ut reprehenderit. In ipsum cillum deserunt do amet exercitation anim. Consequat consectetur id ex officia laboris excepteur nostrud cupidatat. Excepteur aute in culpa voluptate quis exercitation excepteur in. Reprehenderit nostrud dolor laborum nulla officia irure veniam consequat magna et laborum commodo duis tempor. Nulla aliquip aute ipsum minim cillum.');
            $manager->persist($article);
            $manager->flush();
        }
    }
}
