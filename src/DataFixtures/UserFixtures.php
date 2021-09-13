<?php

namespace App\DataFixtures;

use App\Framework\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordEncoderInterface $passwordEncoder)
    {
    }

    public function load(ObjectManager $manager)
    {
        $users = [
            'user' => 'password',
        ];

        foreach ($users as $username => $password) {
            $user = (new User())
                ->setUsername($username)
                ->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
