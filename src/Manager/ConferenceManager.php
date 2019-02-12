<?php


namespace App\Manager;


use App\Entity\Conference;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class ConferenceManager extends AbstractController
{
    private $conferenceRepository;
    private $conferenceDoctrine;

    public function __construct(ConferenceRepository $conferenceRepository, EntityManagerInterface $em)
    {
        $this->conferenceRepository = $conferenceRepository;
        $this->conferenceDoctrine = $em;
    }

    public function getAllConferences(){
        return $this->conferenceRepository->findAll();
    }

    public function save(Conference $conference){
        $this->conferenceDoctrine->persist($conference);
        $this->conferenceDoctrine->flush();
    }

}