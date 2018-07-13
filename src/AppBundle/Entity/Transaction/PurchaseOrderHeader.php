<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Entity\Common\CodeNumberEntity;
use AppBundle\Entity\Master\Supplier;
use AppBundle\Entity\Admin\Staff;

/**
 * @ORM\Table(name="transaction_purchase_order_header")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Transaction\PurchaseOrderHeaderRepository")
 */
class PurchaseOrderHeader extends CodeNumberEntity
{
    const TRANSACTION_TYPE_MATERIAL = 'material';
    const TRANSACTION_TYPE_SAMPLE = 'sample';
    
    /**
     * @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue
     */
    private $id;
    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull() @Assert\Date()
     */
    private $transactionDate;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     */
    private $note;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $transactionType;
    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotNull()
     */
    private $paymentTerm;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull()
     */
    private $deliveryPlace;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotNull()
     */
    private $deliveryTime;
    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $totalQuantity;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $subTotal;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     */
    private $discount;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     */
    private $taxNominal;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $grandTotal;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $isTax;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Admin\Staff")
     * @Assert\NotNull()
     */
    private $staffFirst;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Admin\Staff")
     * @Assert\NotNull()
     */
    private $staffLast;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Master\Supplier")
     * @Assert\NotNull()
     */
    private $supplier;
    /**
     * @ORM\ManyToOne(targetEntity="SampleRequestHeader", inversedBy="purchaseOrderHeaders")
     * @Assert\NotNull()
     */
    private $sampleRequestHeader;
    /**
     * @ORM\OneToMany(targetEntity="PurchaseOrderDetail", mappedBy="purchaseOrderHeader")
     * @Assert\Valid() @Assert\Count(min=1)
     */
    private $purchaseOrderDetails;
    
    public function __construct()
    {
        $this->purchaseOrderDetails = new ArrayCollection();
    }
    
    public function getCodeNumberConstant() { return 'PO'; }
    
    public function getId() { return $this->id; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate($transactionDate) { $this->transactionDate = $transactionDate; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getTransactionType() { return $this->transactionType; }
    public function setTransactionType($transactionType) { $this->transactionType = $transactionType; }

    public function getPaymentTerm() { return $this->paymentTerm; }
    public function setPaymentTerm($paymentTerm) { $this->paymentTerm = $paymentTerm; }

    public function getDeliveryPlace() { return $this->deliveryPlace; }
    public function setDeliveryPlace($deliveryPlace) { $this->deliveryPlace = $deliveryPlace; }

    public function getDeliveryTime() { return $this->deliveryTime; }
    public function setDeliveryTime($deliveryTime) { $this->deliveryTime = $deliveryTime; }

    public function getTotalQuantity() { return $this->totalQuantity; }
    public function setTotalQuantity($totalQuantity) { $this->totalQuantity = $totalQuantity; }

    public function getSubTotal() { return $this->subTotal; }
    public function setSubTotal($subTotal) { $this->subTotal = $subTotal; }

    public function getDiscount() { return $this->discount; }
    public function setDiscount($discount) { $this->discount = $discount; }

    public function getTaxNominal() { return $this->taxNominal; }
    public function setTaxNominal($taxNominal) { $this->taxNominal = $taxNominal; }

    public function getGrandTotal() { return $this->grandTotal; }
    public function setGrandTotal($grandTotal) { $this->grandTotal = $grandTotal; }

    public function getIsTax() { return $this->isTax; }
    public function setIsTax($isTax) { $this->isTax = $isTax; }

    public function getStaffFirst() { return $this->staffFirst; }
    public function setStaffFirst(Staff $staffFirst) { $this->staffFirst = $staffFirst; }

    public function getStaffLast() { return $this->staffLast; }
    public function setStaffLast(Staff $staffLast) { $this->staffLast = $staffLast; }

    public function getSupplier() { return $this->supplier; }
    public function setSupplier(Supplier $supplier) { $this->supplier = $supplier; }

    public function getSampleRequestHeader() { return $this->sampleRequestHeader; }
    public function setSampleRequestHeader(SampleRequestHeader $sampleRequestHeader) { $this->sampleRequestHeader = $sampleRequestHeader; }

    public function getPurchaseOrderDetails() { return $this->purchaseOrderDetails; }
    public function setPurchaseOrderDetails(Collection $purchaseOrderDetails) { $this->purchaseOrderDetails = $purchaseOrderDetails; }

    public function sync()
    {
        $totalQuantity = 0;
        $subTotal = 0.00;
        foreach ($this->purchaseOrderDetails as $purchaseOrderDetail) {
            $purchaseOrderDetail->sync();
            $subTotal += $purchaseOrderDetail->getTotal();
            $totalQuantity += $purchaseOrderDetail->getQuantity();
        }
        $this->totalQuantity = $totalQuantity;
        $this->subTotal = $subTotal;
        $this->taxNominal = ($this->isTax ? $subTotal * 10 / 100 : 0.00);
        $this->grandTotal = $this->subTotal + $this->taxNominal - $this->discount;
    }
}
