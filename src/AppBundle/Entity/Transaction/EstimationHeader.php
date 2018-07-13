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
 * @ORM\Table(name="transaction_estimation_header")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Transaction\EstimationHeaderRepository")
 */
class EstimationHeader extends CodeNumberEntity
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
     * @ORM\Column(type="text")
     * @Assert\NotNull()
     */
    private $note;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $projectName;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $salesmanName;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $modelName;
    /**
     * @ORM\Column(type="smallint")
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $totalQuantity;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThan(0)
     */
    private $totalMaterialCost;
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
     * @ORM\OneToMany(targetEntity="Quotation", mappedBy="estimationHeader")
     */
    private $quotations;
    /**
     * @ORM\OneToMany(targetEntity="EstimationDetail", mappedBy="estimationHeader")
     * @Assert\Valid() @Assert\Count(min=1)
     */
    private $estimationDetails;
    
    public function __construct()
    {
        $this->estimationDetails = new ArrayCollection();
        $this->quotations = new ArrayCollection();
    }
    
    public function getCodeNumberConstant() { return 'EST'; }
    
    public function getId() { return $this->id; }
    
    public function getTransactionDate() { return $this->transactionDate; }
    public function setTransactionDate($transactionDate) { $this->transactionDate = $transactionDate; }

    public function getNote() { return $this->note; }
    public function setNote($note) { $this->note = $note; }

    public function getProjectName() { return $this->projectName; }
    public function setProjectName($projectName) { $this->projectName = $projectName; }

    public function getSalesmanName() { return $this->salesmanName; }
    public function setSalesmanName($salesmanName) { $this->salesmanName = $salesmanName; }
    
    public function getModelName() { return $this->modelName; }
    public function setModelName($modelName) { $this->modelName = $modelName; }

    public function getTotalQuantity() { return $this->totalQuantity; }
    public function setTotalQuantity($totalQuantity) { $this->totalQuantity = $totalQuantity; }

    public function getTotalMaterialCost() { return $this->totalMaterialCost; }
    public function setTotalMaterialCost($totalMaterialCost) { $this->totalMaterialCost = $totalMaterialCost; }

    public function getStaffFirst() { return $this->staffFirst; }
    public function setStaffFirst(Staff $staffFirst = null) { $this->staffFirst = $staffFirst; }

    public function getStaffLast() { return $this->staffLast; }
    public function setStaffLast(Staff $staffLast = null) { $this->staffLast = $staffLast; }

    public function getQuotations() { return $this->quotations; }
    public function setQuotations(Collection $quotations) { $this->quotations = $quotations; }

    public function getEstimationDetails() { return $this->estimationDetails; }
    public function setEstimationDetails(Collection $estimationDetails) { $this->estimationDetails = $estimationDetails; }
    
    public function sync()
    {
        $totalQuantity = 0;
        $totalMaterialCost = 0.00;
        foreach ($this->estimationDetails as $estimationDetail) {
            $estimationDetail->sync();
            $totalMaterialCost += $estimationDetail->getTotal();
            $totalQuantity += $estimationDetail->getQuantity();
        }
        $this->totalQuantity = $totalQuantity;
        $this->totalMaterialCost = $totalMaterialCost;
    }
}
