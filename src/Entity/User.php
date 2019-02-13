<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstName;
    /**
     * @ORM\Column(type="simple_array")
     */
    private $roles = [];

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Conference", inversedBy="users")
     */
    private $conference;


    public function __construct()
    {
        $this->roles = array("ROLE_USER");
        $this->conference = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getSalt()
    {
        return null;
    }
    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials():void
    {

    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Conference[]
     */
    public function getConference(): Collection
    {
        return $this->conference;
    }

    public function addConference(Conference $conference): self
    {
        if (!$this->conference->contains($conference)) {
            $this->conference[] = $conference;
        }

        return $this;
    }

    public function removeConference(Conference $conference): self
    {
        if ($this->conference->contains($conference)) {
            $this->conference->removeElement($conference);
        }

        return $this;
    }

    public function getIdConferenceVoted(): Array
    {
        $arrayIdVoted = array();
        $conferencesVoted = $this->getConference();
        foreach($conferencesVoted as $conferenceVoted){
            array_push($arrayIdVoted, $conferenceVoted->getId());

        }
        return $arrayIdVoted;
    }


}
