<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Form\SearchConferenceType;
use App\Manager\ConferenceManager;
use App\Manager\UserManager;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

//Controlleur deprecié mais j'ai besoin du service knp_paginator
class ConferenceController extends Controller
{

    /**
     * @Route("/conference", name="conference")
     */
    public function index()
    {
        return $this->redirectToRoute('home');

    }

    /**
     * @Route("/conferences", name="conferences")
     */
    public function showAllConference(ConferenceManager $conferenceManager, Request $request)
    {
        $conferencesQuery = $conferenceManager->getAllConferences();

        $paginator = $this->get('knp_paginator');

        $conferences = $paginator->paginate(
            $conferencesQuery,
            $request->query->getInt('page', 1),
            Conference::NB_CONF_PER_PAGE

        );


        return $this->render('conference/showAll.html.twig', [
            'conferences' => $conferences,
            "NbEtoile" => conference::NB_ETOILE,
        ]);
    }
    /**
     * @Route("/conference/add", name="addConferences")
     */
    public function addConference(Request $request, ConferenceManager $conferenceManager, \Swift_Mailer $mailer, UserManager $userManager, LoggerInterface $logger)
    {
        // Possibilité d'ajouter uniquement si l'utilisateur est connecté //Fonction a mettre en place // Logger la creation

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $conference = new Conference();

        $formAddConference = $this->createForm(ConferenceType::class,$conference);
        $formAddConference->handleRequest($request);

        if($formAddConference->isSubmitted() &&  $formAddConference->isValid()){

            $conference->setVote(0);
            $conferenceManager->save($conference);
            //TODO: enregistrer les donnes de cette ajout de conference
            $logger->info(' Conference Added!!! ');

            //ENVOI DES MAILS
            // J'ai utilisé Mailinator
            $users = $userManager->getAllUser();
            foreach($users as $user){

                $message = (new \Swift_Message('New Conference '))
                    ->setFrom('anoinegwada@gmail.com')
                    ->setTo(trim($user->getEmail()))
                    ->setBody("New conference",
                        'text/html'
                    );

                $mailer->send($message);
                $logger->info(' Emails send !! ');
            }

        }

        return $this->render('conference/addConference.html.twig', [
            'form' => $formAddConference->createView(),
        ]);
    }

    /**
     * @Route("/conference/edit/{id}", name="editConference")
     */
    public function editConference(Request $request, ConferenceManager $conferenceManager, Conference $conference, AuthorizationCheckerInterface $authChecker,LoggerInterface $logger) //,  LoggerInterface $logger
    {
        //Seul l'admin peut modifier une conference ( pour la date & le lieu )
        if (false === $authChecker->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $formEditConference = $this->createForm(ConferenceType::class,$conference);
        $formEditConference->handleRequest($request);

        if($formEditConference->isSubmitted() &&  $formEditConference->isValid()){
            $conferenceManager->save($conference);

            $this->addFlash(
                'notice',
                'Conference Edited'
           );
            //TODO: enregister les informations de cette conference
           $logger->info('Conference Edited');
        }

        return $this->render('conference/editConference.html.twig', [
            'form' => $formEditConference->createView(),
            "conference" => $conference
        ]);
    }

    /**
     * @Route("/conference/oneVote/{id}", name="oneVote")
     */
    public function oneVote(Conference $conference, Request $request, ConferenceManager $conferenceManager,LoggerInterface $logger)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $conferenceAlreadyVoted = $user->getIdConferenceVoted();

        //Si un utilisateur a deja vote pour la conference en cours on le redirige vers son profil
        if(in_array($conference->getId(),$conferenceAlreadyVoted)){

            return $this->redirectToRoute('profile');

        }

        $nbVote = $conference->getVote();
        $newNbVote = $nbVote + $_GET['nbre']; // Securiser HtmlSpecial Caract
        $conference->setVote($newNbVote);
        $conference->addUser($user);

        $conferenceManager->save($conference);
        $this->addFlash('success', 'Thanks for your vote ! and See you at the conference maybe');
        //TODO:Enregistrer les infos de vote
        $logger->info('One conference has got voted');

        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/conference/conferencesVoted", name="conferencesVoted")
     */
    public function pageVoted(Request $request, ConferenceManager $conferenceManager)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $conferencesQuery = $conferenceManager->getConferenceVotedByUser($this->getUser());

        $paginator = $this->get('knp_paginator');

        $conferences = $paginator->paginate(
            $conferencesQuery,
            $request->query->getInt('page', 1),
            Conference::NB_CONF_PER_PAGE
        );

        return $this->render('conference/showAll.html.twig',[
            "conferences" => $conferences
        ]);
    }

    /**
     * @Route("/conference/conferencesNoVoted", name="conferencesNoVoted")
     */
    public function pageNoVoted(ConferenceManager $conferenceManager, Request $request)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $conferencesQuery = $conferenceManager->getConferenceNoVotedByUser($this->getUser());

        $paginator = $this->get('knp_paginator');

        $conferences = $paginator->paginate(
            $conferencesQuery,
            $request->query->getInt('page', 1),
            Conference::NB_CONF_PER_PAGE
        );

        return $this->render('conference/showAll.html.twig',[
            "conferences" => $conferences,
            "NbEtoile" => conference::NB_ETOILE
        ]);

    }

    /**
     * @Route("/conference/search", name="searchConference")
     */
    public function searchConference(Request $request)
    {

        $conference = new Conference;

        $formSearchConference = $this->createForm(SearchConferenceType::class,$conference);

        return $this->render('conference/searchConference.html.twig', [
            'form' => $formSearchConference->createView(),
            "conference" => $conference
        ]);
    }


    /**
     * @Route("/conference/resultSearch", name="resultSearchConference")
     */
    public function resultSearchConference(Request $request, ConferenceManager $conferenceManager)
    {
        if($request->isXmlHttpRequest()){

            $nameSearched = $request->request->get('nameSearched');

            if($nameSearched == ""){
                //securité supplémentaire
                return $this->json(array());
            }
            $conferencesFind = $conferenceManager->getSearchResult($nameSearched);

            return $this->json($conferencesFind);
        }
    }

}
