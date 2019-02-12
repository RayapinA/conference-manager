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
            'conferences' => $conferences
        ]);
    }
    /**
     * @Route("/conference/add", name="Addconferences")
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



            //$videoManager->formateYoutubeUrl($video);
            //$videoManager->save($video);


    }
}
