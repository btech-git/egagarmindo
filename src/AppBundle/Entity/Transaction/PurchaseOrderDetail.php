<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Master\Material;

/**
 * @ORM\Table(name="transaction_purchase_order_detail")
 * @ORM\Entity
 */
class PurchaseOrderDetail
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
     */
    private $discount;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $total;
    /**
     * @ORM\ManyToOne(targetEntity="PurchaseOrderHeader", inversedBy="purchaseOrderDetails")
     * @Assert\NotNull()
     */
    private $purchaseOrderHeader;
    /**
     * @ORM\ManyToOne(targetEntity="SampleRequestDetail", inversedBy="purchaseOrderDetails")
     */
    private $sampleRequestDetail;
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

    public function getUnitPrice() { return $this->unitPrice; }
    public function setUnitPrice($unitPrice) { $this->unitPrice = $unitPrice; }

    public function getDiscount() { return $this->discount; }
    public function setDiscount($discount) { $this->discount = $discount; }

    public function getTotal() { return $this->total; }
    public function setTotal($total) { $this->total = $total; }

    public function getPurchaseOrderHeader() { return $this->purchaseOrderHeader; }
    public function setPurchaseOrderHeader(PurchaseOrderHeader $purchaseOrderHeader) { $this->purchaseOrderHeader = $purchaseOrderHeader; }

    public function getSampleRequestDetail() { return $this->sampleRequestDetail; }
    public function setSampleRequestDetail(SampleRequestDetail $sampleRequestDetail) { $this->sampleRequestDetail = $sampleRequestDetail; }

    public function getMaterial() { return $this->material; }
    public function setMaterial(Material $material) { $this->material = $material; }

    public function sync()
    {
        $this->total = $this->quantity * $this->unitPrice - $this->discount;
    }
}
