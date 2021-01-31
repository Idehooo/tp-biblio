<?php

namespace App\DataFixtures;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
         $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail("admin@gmail.com");

        $encoded = $this->passwordEncoder->encodePassword($user, "password");
        $user->setPassword($encoded);

        $user->setRoles(["ROLE_USER", "ROLE_ADMIN"]);

        $manager->persist($user);

        $user = new User();
        $user->setEmail("user@gmail.com");

        $encoded = $this->passwordEncoder->encodePassword($user, "password");
        $user->setPassword($encoded);

        $user->setRoles(["ROLE_USER"]);

        $manager->persist($user);

        $manager->flush();
    }
}
