<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Manager\ConferenceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ConferenceManager $conferenceManager)
    {
        $conferences = $conferenceManager->getAllConferences();

        return $this->render('home/index.html.twig', [
            'conferences' => $conferences,
            "NbEtoile" => conference::NB_ETOILE

        ]);
    }
}
