<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class FakerFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $user = new User();
        $user->setEmail('toto@toto.fr');
        $user->setPassword($this->encoder->encodePassword(
            $user,
            'toto123'
        ));
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);

        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($this->encoder->encodePassword(
                $user,
                'tata123' //$faker->password
            ));
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 1; $i <= 40; $i++) {
            $article = new Article($users[array_rand($users)]);
            $article->setTitle($faker->sentence($nbWords = 3, $variableNbWords = false));
            $article->setContent($faker->randomHtml(2, 3));
            $manager->persist($article);
        }

        $manager->flush();
    }
}
