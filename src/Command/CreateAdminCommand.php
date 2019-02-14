<?php

namespace App\Command;

use App\Entity\User;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';
    private $userManager;
    private $encoder;

    public function __construct(UserManager $userManager, UserPasswordEncoderInterface $encoder){

        $this->userManager = $userManager;
        $this->encoder = $encoder;
        parent::__construct();

    }
    protected function configure()
    {
        $this
            ->setDescription('Command for create a new Admin')
            ->addArgument('email', InputArgument::REQUIRED, 'email ')
            ->addArgument('password', InputArgument::REQUIRED, 'paswword ')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $io->note(sprintf('You passed an argument: %s', $email));

        $user = new User();
        $user->setEmail($email);
        $passwordEncoded = $this->encoder->encodePassword($user, $password);
        $user->setPassword($passwordEncoded);
        $user->setRoles(['ROLE_USER','ROLE_ADMIN']);
        $user->setName('LastName_Admin');
        $user->setFirstName('FirstName_Admin');

        $this->userManager->save($user);

        $io->success(sprintf('You have created a User with email: %s',$email));

    }
}
