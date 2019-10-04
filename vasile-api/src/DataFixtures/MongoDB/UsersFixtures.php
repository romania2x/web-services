<?php

namespace App\DataFixtures\MongoDB;

use App\Document\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UsersFixtures
 * @package App\DataFixtures\MongoDB
 */
class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->firstName = 'Sorin';
        $user->lastName = 'Badea';
        $user->email = 'sorin.badea91@gmail.com';

        $manager->persist($user);
        $manager->flush();
    }
}
