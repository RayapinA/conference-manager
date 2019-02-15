<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Manager\ConferenceManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(ConferenceManager $conferenceManager,Request $request)
    {
        $conferencesQuery = $conferenceManager->getAllConferences();

        $paginator = $this->get('knp_paginator');

        $conferences = $paginator->paginate(
            $conferencesQuery,
            $request->query->getInt('page', 1),
            Conference::NB_CONF_PER_PAGE
        );

        return $this->render('home/index.html.twig', [
            'conferences' => $conferences,
            "NbEtoile" => conference::NB_ETOILE

        ]);
    }
}
