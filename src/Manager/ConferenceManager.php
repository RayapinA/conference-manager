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

    public function getAllConferences()
    {
        return $this->conferenceRepository->findAll();
    }

    public function getConferenceNoVotedByUser($userCurrent)
    {
        $arrayConferenceNoVoted = array();
        $conferences = $this->getAllConferences();

        foreach ($conferences as $conference) {

            $users = $conference->getUsers();

            if(count($users) == 0){
                array_push($arrayConferenceNoVoted, $conference);
            }
            else{

                foreach ($users as $user) {
                    if ($user->getId() != $userCurrent->getId()) {

                        array_push($arrayConferenceNoVoted, $conference);
                    }
                }
            }

        }
        return $arrayConferenceNoVoted;

    }

    public function getConferenceVotedByUser($userCurrent)
    {
        $arrayConferenceVoted = array();
        $conferences = $this->getAllConferences();
        foreach( $conferences as $conference){

            $users = $conference->getUsers();
            foreach($users as $user){
                if($user->getId() == $userCurrent->getId()){
                    array_push($arrayConferenceVoted,$conference);
                }
            }
        }
        return $arrayConferenceVoted;
    }

    public function save(Conference $conference)
    {
        $this->conferenceDoctrine->persist($conference);
        $this->conferenceDoctrine->flush();
    }

}