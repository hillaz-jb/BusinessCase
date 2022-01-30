<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:hashPwd',
    description: 'Use after fixtures to hash user\'s password'
)]
class HashPwdCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $userPasswordHasher;

    /**
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    protected function configure(): void
    {
        $this->setName('app:hashPwd');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Command starting...');

        $allUser = $this->userRepository->findAll();

        $progressBar = new ProgressBar($output, count($allUser));
        $progressBar->start();

        foreach ($allUser as $user) {
            $user->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                $user->getPassword(),
            ));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $progressBar->advance();
        }

        $progressBar->finish();
        $output->writeln("\r\nCommand finished !");
        return Command::SUCCESS;
    }
}