<?php

namespace AppBundle\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\Common\CodeNumberEntity;
use AppBundle\Entity\Admin\Staff;

/**
 * @ORM\Table(name="transaction_quotation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Transaction\QuotationRepository")
 */
class Quotation extends CodeNumberEntity
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
     * @ORM\Column(type="decimal", precision=10, scale=4)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $quantity;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $estimatedSellingPercentage1;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $estimatedSellingPrice1;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $estimatedSellingPercentage2;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $estimatedSellingPrice2;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $estimatedSellingPercentage3;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $estimatedSellingPrice3;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $estimatedSellingPercentage4;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $estimatedSellingPrice4;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $estimatedSellingPercentage5;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $estimatedSellingPrice5;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $sellingUnitPercentage;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $sellingUnitPrice;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $transportationPercentage;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $transportationNominal;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $samplingPercentage;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $samplingNominal;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $overheadPercentage;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $overheadNominal;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $totalOperationalCost;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $totalCost;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $grandTotalCost;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $totalSellingPrice;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $totalMaterialCost;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $totalProfit;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $salesmanCommissionPercentage;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $salesmanCommissionAmount;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     */
    private $note;
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
     * @ORM\ManyToOne(targetEntity="EstimationHeader", inversedBy="quotations")
     * @Assert\NotNull()
     */
    private $estimationHeader;
    /**
     * @ORM\OneToMany(targetEntity="SampleRequestHeader", mappedBy="quotation")
     */
    private $sampleRequestHeaders;
    
    public function __construct()
    {
        $this->sampleRequestHeaders = new ArrayCollection();
    }
    
    public function __toString()
    {
        return 'quotation';
    }
    
    public function getCodeNumberConstant() { return 'QUO'; }
    
    public function getId() { return $this->id; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate(\DateTime $transactionDate = null) { $this->transactionDate = $transactionDate; }
    
    public function getQuantity() { return $this->quantity; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }

    public function getEstimatedSellingPercentage1() { return $this->estimatedSellingPercentage1; }
    public function setEstimatedSellingPercentage1($estimatedSellingPercentage1) { $this->estimatedSellingPercentage1 = $estimatedSellingPercentage1; }

    public function getEstimatedSellingPrice1() { return $this->estimatedSellingPrice1; }
    public function setEstimatedSellingPrice1($estimatedSellingPrice1) { $this->estimatedSellingPrice1 = $estimatedSellingPrice1; }

    public function getEstimatedSellingPercentage2() { return $this->estimatedSellingPercentage2; }
    public function setEstimatedSellingPercentage2($estimatedSellingPercentage2) { $this->estimatedSellingPercentage2 = $estimatedSellingPercentage2; }

    public function getEstimatedSellingPrice2() { return $this->estimatedSellingPrice2; }
    public function setEstimatedSellingPrice2($estimatedSellingPrice2) { $this->estimatedSellingPrice2 = $estimatedSellingPrice2; }

    public function getEstimatedSellingPercentage3() { return $this->estimatedSellingPercentage3; }
    public function setEstimatedSellingPercentage3($estimatedSellingPercentage3) { $this->estimatedSellingPercentage3 = $estimatedSellingPercentage3; }

    public function getEstimatedSellingPrice3() { return $this->estimatedSellingPrice3; }
    public function setEstimatedSellingPrice3($estimatedSellingPrice3) { $this->estimatedSellingPrice3 = $estimatedSellingPrice3; }

    public function getEstimatedSellingPercentage4() { return $this->estimatedSellingPercentage4; }
    public function setEstimatedSellingPercentage4($estimatedSellingPercentage4) { $this->estimatedSellingPercentage4 = $estimatedSellingPercentage4; }

    public function getEstimatedSellingPrice4() { return $this->estimatedSellingPrice4; }
    public function setEstimatedSellingPrice4($estimatedSellingPrice4) { $this->estimatedSellingPrice4 = $estimatedSellingPrice4; }

    public function getEstimatedSellingPercentage5() { return $this->estimatedSellingPercentage5; }
    public function setEstimatedSellingPercentage5($estimatedSellingPercentage5) { $this->estimatedSellingPercentage5 = $estimatedSellingPercentage5; }

    public function getEstimatedSellingPrice5() { return $this->estimatedSellingPrice5; }
    public function setEstimatedSellingPrice5($estimatedSellingPrice5) { $this->estimatedSellingPrice5 = $estimatedSellingPrice5; }

    public function getSellingUnitPercentage() { return $this->sellingUnitPercentage; }
    public function setSellingUnitPercentage($sellingUnitPercentage) { $this->sellingUnitPercentage = $sellingUnitPercentage; }

    public function getSellingUnitPrice() { return $this->sellingUnitPrice; }
    public function setSellingUnitPrice($sellingUnitPrice) { $this->sellingUnitPrice = $sellingUnitPrice; }

    public function getTransportationPercentage() { return $this->transportationPercentage; }
    public function setTransportationPercentage($transportationPercentage) { $this->transportationPercentage = $transportationPercentage; }

    public function getTransportationNominal() { return $this->transportationNominal; }
    public function setTransportationNominal($transportationNominal) { $this->transportationNominal = $transportationNominal; }

    public function getSamplingPercentage() { return $this->samplingPercentage; }
    public function setSamplingPercentage($samplingPercentage) { $this->samplingPercentage = $samplingPercentage; }

    public function getSamplingNominal() { return $this->samplingNominal; }
    public function setSamplingNominal($samplingNominal) { $this->samplingNominal = $samplingNominal; }

    public function getOverheadPercentage() { return $this->overheadPercentage; }
    public function setOverheadPercentage($overheadPercentage) { $this->overheadPercentage = $overheadPercentage; }

    public function getOverheadNominal() { return $this->overheadNominal; }
    public function setOverheadNominal($overheadNominal) { $this->overheadNominal = $overheadNominal; }

    public function getTotalOperationalCost() { return $this->totalOperationalCost; }
    public function setTotalOperationalCost($totalOperationalCost) { $this->totalOperationalCost = $totalOperationalCost; }

    public function getTotalCost() { return $this->totalCost; }
    public function setTotalCost($totalCost) { $this->totalCost = $totalCost; }

    public function getGrandTotalCost() { return $this->grandTotalCost; }
    public function setGrandTotalCost($grandTotalCost) { $this->grandTotalCost = $grandTotalCost; }

    public function getTotalSellingPrice() { return $this->totalSellingPrice; }
    public function setTotalSellingPrice($totalSellingPrice) { $this->totalSellingPrice = $totalSellingPrice; }

    public function getTotalMaterialCost() { return $this->totalMaterialCost; }
    public function setTotalMaterialCost($totalMaterialCost) { $this->totalMaterialCost = $totalMaterialCost; }

    public function getTotalProfit() { return $this->totalProfit; }
    public function setTotalProfit($totalProfit) { $this->totalProfit = $totalProfit; }

    public function getSalesmanCommissionPercentage() { return $this->salesmanCommissionPercentage; }
    public function setSalesmanCommissionPercentage($salesmanCommissionPercentage) { $this->salesmanCommissionPercentage = $salesmanCommissionPercentage; }

    public function getSalesmanCommissionAmount() { return $this->salesmanCommissionAmount; }
    public function setSalesmanCommissionAmount($salesmanCommissionAmount) { $this->salesmanCommissionAmount = $salesmanCommissionAmount; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getStaffFirst() { return $this->staffFirst; }
    public function setStaffFirst(Staff $staffFirst = null) { $this->staffFirst = $staffFirst; }

    public function getStaffLast() { return $this->staffLast; }
    public function setStaffLast(Staff $staffLast = null) { $this->staffLast = $staffLast; }

    public function getEstimationHeader() { return $this->estimationHeader; }
    public function setEstimationHeader(EstimationHeader $estimationHeader = null) { $this->estimationHeader = $estimationHeader; }
    
    public function getSampleRequestHeaders() { return $this->sampleRequestHeaders; }
    public function setSampleRequestHeaders(Collection $sampleRequestHeaders) { $this->sampleRequestHeaders = $sampleRequestHeaders; }

    public function sync()
    {
        $this->totalMaterialCost = $this->estimationHeader->getTotalMaterialCost();
        $this->transportationNominal = $this->transportationPercentage * $this->totalMaterialCost / 100;
        $this->samplingNominal = $this->samplingPercentage * $this->totalMaterialCost / 100;
        $this->overheadNominal = $this->overheadPercentage * $this->totalMaterialCost / 100;
        $this->totalOperationalCost = $this->transportationNominal + $this->samplingNominal + $this->overheadNominal;
        $this->totalCost = $this->totalMaterialCost + $this->totalOperationalCost;
        $this->estimatedSellingPrice1 = ($this->estimatedSellingPercentage1 <= 0.00) ? 0.00 : $this->totalCost / $this->estimatedSellingPercentage1;
        $this->estimatedSellingPrice2 = ($this->estimatedSellingPercentage2 <= 0.00) ? 0.00 : $this->totalCost / $this->estimatedSellingPercentage2;
        $this->estimatedSellingPrice3 = ($this->estimatedSellingPercentage3 <= 0.00) ? 0.00 : $this->totalCost / $this->estimatedSellingPercentage3;
        $this->estimatedSellingPrice4 = ($this->estimatedSellingPercentage4 <= 0.00) ? 0.00 : $this->totalCost / $this->estimatedSellingPercentage4;
        $this->estimatedSellingPrice5 = ($this->estimatedSellingPercentage5 <= 0.00) ? 0.00 : $this->totalCost / $this->estimatedSellingPercentage5;
        $this->sellingUnitPercentage = ($this->sellingUnitPrice <= 0.00) ? 0.00 : $this->totalCost / $this->sellingUnitPrice;
        $this->totalSellingPrice = $this->quantity * $this->sellingUnitPrice;
        $this->grandTotalCost = $this->totalCost * $this->quantity;
        $this->totalProfit = $this->totalSellingPrice - $this->grandTotalCost;
        $this->salesmanCommissionPercentage = ($this->sellingUnitPercentage < 0.90) ? 1 : 1.5;
        $this->salesmanCommissionAmount = $this->salesmanCommissionPercentage * $this->totalProfit / 100;
    }
}
