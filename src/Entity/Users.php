<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $firstname;
    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $secondname;
    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $surname;
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }
    
    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;
        
        return $this;
    }
    
    public function getSecondname(): ?string
    {
        return $this->secondname;
    }
    
    public function setSecondname(?string $secondname): self
    {
        $this->secondname = $secondname;
        
        return $this;
    }
    
    public function getSurname(): ?string
    {
        return $this->surname;
    }
    
    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;
        
        return $this;
    }
    
    public function toArray()
    : array
    {
        return [
            "id"         => $this->getId(),
            "firstname"  => $this->getFirstname(),
            "secondname" => $this->getSecondname(),
            "surname"    => $this->getSurname(),
        ];
    }
    
}
