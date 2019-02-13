<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Manager\ConferenceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ConferenceController extends AbstractController
{

    /**
     * @Route("/conference", name="conference")
     */
    public function index()
    {
        return $this->render('conference/index.html.twig', [
            'controller_name' => 'ConferenceController',
        ]);
    }

    /**
     * @Route("/conferences", name="conferences")
     */
    public function showAllConference(ConferenceManager $conferenceManager)
    {
        $conferences = $conferenceManager->getAllConferences();

        return $this->render('conference/showAll.html.twig', [
            'conferences' => $conferences,
            'NbEtoile' => conference::NB_ETOILE
        ]);
    }
    /**
     * @Route("/conference/add", name="addConferences")
     */
    public function addConference(Request $request, ConferenceManager $conferenceManager) // LoggerInterface $logger
    {
        // Possibilité d'ajouter uniquement si l'utilisateur est connecté //Fonction a mettre en place // Logger la creation

        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $conference = new Conference();

        $formAddConference = $this->createForm(ConferenceType::class,$conference);
        $formAddConference->handleRequest($request);

        if($formAddConference->isSubmitted() &&  $formAddConference->isValid()){

            $conference->setVote(0);
            $conferenceManager->save($conference);
        }

        return $this->render('conference/addConference.html.twig', [
            'form' => $formAddConference->createView(),
        ]);
    }

    /**
     * @Route("/conference/edit/{id}", name="editVideo")
     */
    public function editConference(Request $request, ConferenceManager $conferenceManager, Conference $conference) //,  LoggerInterface $logger
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $formEditConference = $this->createForm(ConferenceType::class,$conference);
        $formEditConference->handleRequest($request);

        if($formEditConference->isSubmitted() &&  $formEditConference->isValid()){
            $conferenceManager->save($conference);

            //$this->addFlash(
             //   'notice',
               // 'Video Edited'
          //  );

//            $logger->info('Video Edited. idVideo = '.$video->getId().' title = '.$video->getTitle());
        }

        return $this->render('conference/editConference.html.twig', [
            'form' => $formEditConference->createView(),
            "conference" => $conference
        ]);
    }

    /**
     * @Route("/conference/oneVote/{id}", name="oneVote")
     */
    public function oneVote(Conference $conference,Request $request, ConferenceManager $conferenceManager)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $conferenceAlreadyVoted = $user->getIdConferenceVoted();

        if(in_array($conference->getId(),$conferenceAlreadyVoted)){

            return $this->redirectToRoute('profile');

        }

        $nbVote = $conference->getVote();
        $newNbVote = $nbVote + $_GET['nbre']; // Securiser HtmlSpecial Caract
        $conference->setVote($newNbVote);
        $conference->addUser($user);

        $conferenceManager->save($conference);

        return $this->redirectToRoute('profile');
    }
}
