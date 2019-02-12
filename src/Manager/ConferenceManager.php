<?php


namespace App\Manager;


use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConferenceManager extends AbstractController
{
    private $conferenceRepository;

    public function __construct(ConferenceRepository $conferenceRepository)
    {
        $this->conferenceRepository = $conferenceRepository;
    }

    public function getAllConferences(){
        return $this->conferenceRepository->findAll();
    }

}