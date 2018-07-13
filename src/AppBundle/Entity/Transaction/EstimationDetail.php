<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\Master\Material;
use AppBundle\Entity\Master\Supplier;

/**
 * @ORM\Table(name="transaction_estimation_detail")
 * @ORM\Entity
 */
class EstimationDetail
{
    /**
     * @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue
     */
    private $id;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=4)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $quantity;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $unitPrice;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $total;
    /**
     * @ORM\ManyToOne(targetEntity="EstimationHeader", inversedBy="estimationDetails")
     * @Assert\NotNull()
     */
    private $estimationHeader;
    /**
     * @ORM\OneToMany(targetEntity="SampleRequestDetail", mappedBy="estimationDetail")
     */
    private $sampleRequestDetails;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\Material")
     * @Assert\NotNull()
     */
    private $material;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\Supplier")
     * @Assert\NotNull()
     */
    private $supplier;
    
    public function __construct()
    {
        $this->sampleRequestDetails = new ArrayCollection();
    }
    
    public function getId() { return $this->id; }

    public function getQuantity() { return $this->quantity; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }

    public function getUnitPrice() { return $this->unitPrice; }
    public function setUnitPrice($unitPrice) { $this->unitPrice = $unitPrice; }

    public function getTotal() { return $this->total; }
    public function setTotal($total) { $this->total = $total; }

    public function getEstimationHeader() { return $this->estimationHeader; }
    public function setEstimationHeader(EstimationHeader $estimationHeader = null) { $this->estimationHeader = $estimationHeader; }

    public function getSampleRequestDetails() { return $this->sampleRequestDetails; }
    public function setSampleRequestDetails(Collection $sampleRequestDetails) { $this->sampleRequestDetails = $sampleRequestDetails; }

    public function getMaterial() { return $this->material; }
    public function setMaterial(Material $material = null) { $this->material = $material; }
    
    public function getSupplier() { return $this->supplier; }
    public function setSupplier(Supplier $supplier = null) { $this->supplier = $supplier; }
    
    public function sync()
    {
        $this->total = $this->quantity * $this->unitPrice;
    }
}
