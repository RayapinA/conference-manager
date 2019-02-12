<?php

namespace App\Controller;

use App\Manager\ConferenceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
}
