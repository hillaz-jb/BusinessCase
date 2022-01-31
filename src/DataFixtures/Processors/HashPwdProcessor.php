<?php

namespace App\DataFixtures\Processors;

use App\Entity\User;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class HashPwdProcessor implements ProcessorInterface
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function preProcess(string $id, object $object): void
    {
        if (false === $object instanceof User) {
            return;
        }

        $object->setPassword($this->userPasswordHasher->hashPassword(
            $object,
            $object->getPassword(),
        ));
    }

    public function postProcess(string $id, object $object): void
    {
        //Implement postProcess() method.
    }
}