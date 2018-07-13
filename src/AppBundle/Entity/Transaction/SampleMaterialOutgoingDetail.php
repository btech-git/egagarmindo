<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Master\Material;

/**
 * @ORM\Table(name="transaction_sample_material_outgoing_detail")
 * @ORM\Entity
 */
class SampleMaterialOutgoingDetail
{
    /**
     * @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue
     */
    private $id;
    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $quantity;
    /**
     * @ORM\ManyToOne(targetEntity="SampleMaterialOutgoingHeader", inversedBy="sampleMaterialOutgoingDetails")
     * @Assert\NotNull()
     */
    private $sampleMaterialOutgoingHeader;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\Material")
     * @Assert\NotNull()
     */
    private $material;
    
    public function __construct()
    {
    }
    
    public function getId() { return $this->id; }

    public function getQuantity() { return $this->quantity; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }

    public function getSampleMaterialOutgoingHeader() { return $this->sampleMaterialOutgoingHeader; }
    public function setSampleMaterialOutgoingHeader(SampleMaterialOutgoingHeader $sampleMaterialOutgoingHeader = null) { $this->sampleMaterialOutgoingHeader = $sampleMaterialOutgoingHeader; }

    public function getMaterial() { return $this->material; }
    public function setMaterial(Material $material = null) { $this->material = $material; }
    
    public function sync()
    {
    }
}
