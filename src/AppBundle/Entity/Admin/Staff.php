<?php

namespace AppBundle\Entity\Admin;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Common\User;

/**
 * @ORM\Entity
 */
class Staff extends User
{
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotNull() @Assert\Email()
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotNull()
     */
    private $address;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $phone;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     */
    private $note;
    
    public function __toString()
    {
        return $this->name;
    }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }
    
    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }
    
    public function getAddress() { return $this->address; }
    public function setAddress($address) { $this->address = $address; }
    
    public function getPhone() { return $this->phone; }
    public function setPhone($phone) { $this->phone = $phone; }
    
    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }
    
    public function getRoles()
    {
        $defaultRoles = array_merge(parent::getRoles(), array('ROLE_STAFF'));
        $assignedRoles = array_map(function($userRole) { return $userRole->getRole(); }, $this->getUserRoles()->toArray());
        $roles = array_unique(array_merge($defaultRoles, $assignedRoles));
        
        return $roles;
    }
}
