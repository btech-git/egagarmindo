<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="transaction_estimation_image")
 * @ORM\Entity
 */
class EstimationImage
{
    /**
     * @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $fileExtension;
    /**
     * @ORM\ManyToOne(targetEntity="EstimationHeader", inversedBy="estimationImages")
     * @Assert\NotNull()
     */
    private $estimationHeader;
    
    public function __construct()
    {
    }
    
    public function getId() { return $this->id; }

    public function getFileExtension() { return $this->fileExtension; }
    public function setFileExtension($fileExtension) { $this->fileExtension = $fileExtension; }

    public function getEstimationHeader() { return $this->estimationHeader; }
    public function setEstimationHeader(EstimationHeader $estimationHeader) { $this->estimationHeader = $estimationHeader; }
}
