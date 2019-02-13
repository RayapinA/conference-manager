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

        // Paginate the results of the query
        $conferences = $paginator->paginate(
            $conferencesQuery,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('home/index.html.twig', [
            'conferences' => $conferences,
            "NbEtoile" => conference::NB_ETOILE

        ]);
    }
}
