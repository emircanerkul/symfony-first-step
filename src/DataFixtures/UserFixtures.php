<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $user->setEmail("admin@symfony.dev");
        $user->setPassword('$argon2id$v=19$m=65536,t=4,p=1$MmxBQlM1WjJJSUxNWEpISg$po/JPHPWNF8CRY34cuRVtoEwQ1Fmg+wTxBIMZyayMsY');
        $user->setRoles(["ROLE_ADMIN"]);

        $manager->persist($user);
        $manager->flush();
    }
}
