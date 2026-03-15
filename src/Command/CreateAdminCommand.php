<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:create-admin', description: 'Create the default admin user')]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $existing = $this->em->getRepository(User::class)->findOneBy(['email' => 'admin@norsu.edu.ph']);
        if ($existing) {
            $output->writeln('Admin user already exists.');
            return Command::SUCCESS;
        }

        $user = new User();
        $user->setFirstName('Admin');
        $user->setLastName('User');
        $user->setEmail('admin@norsu.edu.ph');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->hasher->hashPassword($user, 'admin123'));
        $user->setAccountStatus('active');

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('Admin created: admin@norsu.edu.ph / admin123');

        return Command::SUCCESS;
    }
}
