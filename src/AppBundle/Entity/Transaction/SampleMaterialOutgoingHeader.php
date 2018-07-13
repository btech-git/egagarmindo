<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Entity\Common\CodeNumberEntity;
use AppBundle\Entity\Admin\Staff;

/**
 * @ORM\Table(name="transaction_sample_material_outgoing_header")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Transaction\SampleMaterialOutgoingHeaderRepository")
 */
class SampleMaterialOutgoingHeader extends CodeNumberEntity
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
    private $outgoingDate;
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
     * @ORM\ManyToOne(targetEntity="SampleRequestHeader", inversedBy="sampleMaterialOutgoingHeaders")
     * @Assert\NotNull()
     */
    private $sampleRequestHeader;
    /**
     * @ORM\OneToMany(targetEntity="SampleMaterialOutgoingDetail", mappedBy="sampleMaterialOutgoingHeader")
     * @Assert\Valid() @Assert\Count(min=1)
     */
    private $sampleMaterialOutgoingDetails;
    
    public function __construct()
    {
        $this->sampleMaterialOutgoingDetails = new ArrayCollection();
    }
    
    public function getCodeNumberConstant() { return 'SMPO'; }
    
    public function getId() { return $this->id; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate($transactionDate) { $this->transactionDate = $transactionDate; }

    public function getOutgoingDate() { return $this->outgoingDate; }
    public function setOutgoingDate($outgoingDate) { $this->outgoingDate = $outgoingDate; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getTotalQuantity() { return $this->totalQuantity; }
    public function setTotalQuantity($totalQuantity) { $this->totalQuantity = $totalQuantity; }

    public function getStaffFirst() { return $this->staffFirst; }
    public function setStaffFirst(Staff $staffFirst) { $this->staffFirst = $staffFirst; }

    public function getStaffLast() { return $this->staffLast; }
    public function setStaffLast(Staff $staffLast) { $this->staffLast = $staffLast; }

    public function getSampleRequestHeader() { return $this->sampleRequestHeader; }
    public function setSampleRequestHeader(SampleRequestHeader $sampleRequestHeader) { $this->sampleRequestHeader = $sampleRequestHeader; }

    public function getSampleMaterialOutgoingDetails() { return $this->sampleMaterialOutgoingDetails; }
    public function setSampleMaterialOutgoingDetails(Collection $sampleMaterialOutgoingDetails) { $this->sampleMaterialOutgoingDetails = $sampleMaterialOutgoingDetails; }

    public function sync()
    {
        $totalQuantity = 0;
        foreach ($this->sampleMaterialOutgoingDetails as $sampleMaterialOutgoingDetail) {
//            $sampleMaterialOutgoingDetail->sync();
            $totalQuantity = $totalQuantity + $sampleMaterialOutgoingDetail->getQuantity();
        }
        $this->totalQuantity = $totalQuantity;
    }
}
