<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Master\Material;

/**
 * @ORM\Table(name="transaction_sample_request_detail")
 * @ORM\Entity
 */
class SampleRequestDetail
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
     * @ORM\ManyToOne(targetEntity="SampleRequestHeader", inversedBy="sampleRequestDetails")
     * @Assert\NotNull()
     */
    private $sampleRequestHeader;
    /**
     * @ORM\ManyToOne(targetEntity="EstimationDetail", inversedBy="sampleRequestDetails")
     * @Assert\NotNull()
     */
    private $estimationDetail;
    /**
     * @ORM\OneToMany(targetEntity="PurchaseOrderDetail", mappedBy="sampleRequestDetail")
     */
    private $purchaseOrderDetails;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\Material")
     * @Assert\NotNull()
     */
    private $material;
    
    public function __construct()
    {
        $this->purchaseOrderDetails = new ArrayCollection();
    }
    
    public function getId() { return $this->id; }

    public function getQuantity() { return $this->quantity; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }

    public function getSampleRequestHeader() { return $this->sampleRequestHeader; }
    public function setSampleRequestHeader(SampleRequestHeader $sampleRequestHeader = null) { $this->sampleRequestHeader = $sampleRequestHeader; }

    public function getEstimationDetail() { return $this->estimationDetail; }
    public function setEstimationDetail(EstimationDetail $estimationDetail = null) { $this->estimationDetail = $estimationDetail; }

    public function getPurchaseOrderDetails() { return $this->purchaseOrderDetails; }
    public function setPurchaseOrderDetails(Collection $purchaseOrderDetails) { $this->purchaseOrderDetails = $purchaseOrderDetails; }

    public function getMaterial() { return $this->material; }
    public function setMaterial(Material $material = null) { $this->material = $material; }
    
    public function sync()
    {
    }
}
