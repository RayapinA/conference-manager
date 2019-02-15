<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\User;
use App\Form\LoginUserType;
use App\Form\RegisterUserType;
use App\Manager\ConferenceManager;
use App\Manager\SecurityManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/security", name="security")
     */
    public function index()
    {
        $this->redirectToRoute('home');
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, LoggerInterface $logger, SecurityManager $securityManager )
    {

        $user = new User();
        $form = $this->createForm(RegisterUserType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() &&  $form->isValid()){

            $password = $passwordEncoder->encodePassword($user,$user->getPassword());
            $user->setPassword($password);

            $securityManager->save($user);

            $this->addFlash('success', 'Sign up to vote right now');
            $logger->info(' New User registered  !!! ');

            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils,LoggerInterface $logger)
    {

        $user = new User();

        $form = $this->createForm(LoginUserType::class,$user);

        if(!empty($authenticationUtils->getLastAuthenticationError())){
            $this->addFlash(
                'notice',
               'Erreur de connexion'
            );
        }
        //TODO: Enregistrer l'identité de la connexion
        $logger->info(' New Connection !!! ');

        return $this->render('security/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(ConferenceManager $conferenceManager)
    {
        
        $conferenceVoted = array();
        $conferenceNoVoted = array();

        $conferences = $conferenceManager->getAllConferences();

        $user = $this->getUser();
        $conferenceAlreadyVoted = $user->getIdConferenceVoted();

        foreach($conferences as $conference){

            if(in_array($conference->getId(),$conferenceAlreadyVoted)){
                array_push($conferenceVoted,$conference);
            }else{
                array_push($conferenceNoVoted,$conference);
            }
        }

        return $this->render('security/profile.html.twig',[
            "conferencevoted" => $conferenceVoted,
            "conferenceNoVoted" => $conferenceNoVoted,
            "NbEtoile" => conference::NB_ETOILE
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(LoggerInterface $logger)
    {
        //TODO: enregistrer l'identite de la connexion
        $logger->info(' Deconnection!!! ');
        return $this->redirectToRoute('home');
    }




}
