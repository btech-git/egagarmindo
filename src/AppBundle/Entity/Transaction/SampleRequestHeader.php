<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Entity\Common\CodeNumberEntity;
use AppBundle\Entity\Admin\Staff;

/**
 * @ORM\Table(name="transaction_sample_request_header")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Transaction\SampleRequestHeaderRepository")
 */
class SampleRequestHeader extends CodeNumberEntity
{
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
     * @ORM\Column(type="date")
     * @Assert\NotNull() @Assert\Date()
     */
    private $deliveryDate;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     */
    private $note;
    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $totalQuantity;
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
     * @ORM\ManyToOne(targetEntity="Quotation", inversedBy="sampleRequestHeaders")
     * @Assert\NotNull()
     */
    private $quotation;
    /**
     * @ORM\OneToMany(targetEntity="SampleRequestDetail", mappedBy="sampleRequestHeader")
     * @Assert\Valid() @Assert\Count(min=1)
     */
    private $sampleRequestDetails;
    /**
     * @ORM\OneToMany(targetEntity="SampleMaterialOutgoingHeader", mappedBy="sampleRequestHeader")
     */
    private $sampleMaterialOutgoingHeaders;
    /**
     * @ORM\OneToMany(targetEntity="PurchaseOrderHeader", mappedBy="sampleRequestHeader")
     */
    private $purchaseOrderHeaders;
    
    public function __construct()
    {
        $this->sampleRequestDetails = new ArrayCollection();
        $this->sampleMaterialOutgoingHeaders = new ArrayCollection();
        $this->purchaseOrderHeaders = new ArrayCollection();
    }
    
    public function getCodeNumberConstant() { return 'SMPR'; }
    
    public function getId() { return $this->id; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate($transactionDate) { $this->transactionDate = $transactionDate; }

    public function getDeliveryDate() { return $this->deliveryDate; }
    public function setDeliveryDate($deliveryDate) { $this->deliveryDate = $deliveryDate; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getTotalQuantity() { return $this->totalQuantity; }
    public function setTotalQuantity($totalQuantity) { $this->totalQuantity = $totalQuantity; }

    public function getStaffFirst() { return $this->staffFirst; }
    public function setStaffFirst(Staff $staffFirst) { $this->staffFirst = $staffFirst; }

    public function getStaffLast() { return $this->staffLast; }
    public function setStaffLast(Staff $staffLast) { $this->staffLast = $staffLast; }

    public function getQuotation() { return $this->quotation; }
    public function setQuotation(Quotation $quotation) { $this->quotation = $quotation; }

    public function getSampleRequestDetails() { return $this->sampleRequestDetails; }
    public function setSampleRequestDetails(Collection $sampleRequestDetails) { $this->sampleRequestDetails = $sampleRequestDetails; }

    public function getSampleMaterialOutgoingHeaders() { return $this->sampleMaterialOutgoingHeaders; }
    public function setSampleMaterialOutgoingHeaders(Collection $sampleMaterialOutgoingHeaders) { $this->sampleMaterialOutgoingHeaders = $sampleMaterialOutgoingHeaders; }

    public function getPurchaseOrderHeaders() { return $this->purchaseOrderHeaders; }
    public function setPurchaseOrderHeaders(Collection $purchaseOrderHeaders) { $this->purchaseOrderHeaders = $purchaseOrderHeaders; }

    public function sync()
    {
        $totalQuantity = 0;
        foreach ($this->sampleRequestDetails as $sampleRequestDetail) {
            $totalQuantity += $sampleRequestDetail->getQuantity();
        }
        $this->totalQuantity = $totalQuantity;
    }
}
