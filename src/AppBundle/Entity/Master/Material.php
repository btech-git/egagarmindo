<?php

namespace AppBundle\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="master_material") @ORM\Entity
 * @UniqueEntity("code")
 * @UniqueEntity({"name"})
 */
class Material
{
    /**
     * @ORM\Column(type="integer") @ORM\Id @ORM\GeneratedValue
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=20, unique=true)
     * @Assert\NotNull()
     */
    private $code;
    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $name;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $weightedPurchasePrice;
    /**
     * @ORM\Column(type="decimal", precision=18, scale=2)
     * @Assert\NotNull() @Assert\GreaterThanOrEqual(0)
     */
    private $sellingPrice;
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     */
    private $isActive = true;
    /**
     * @ORM\ManyToOne(targetEntity="MaterialCategory", inversedBy="materials")
     * @Assert\NotNull()
     */
    private $materialCategory;
    /**
     * @ORM\ManyToOne(targetEntity="Unit", inversedBy="materials")
     * @Assert\NotNull()
     */
    private $unit;
    
    public function __construct()
    {
    }
    
    public function __toString()
    {
        return $this->name;
    }
    
    public function getId() { return $this->id; }

    public function getCode() { return $this->code; }
    public function setCode($code) { $this->code = $code; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getWeightedPurchasePrice() { return $this->weightedPurchasePrice; }
    public function setWeightedPurchasePrice($weightedPurchasePrice) { $this->weightedPurchasePrice = $weightedPurchasePrice; }

    public function getSellingPrice() { return $this->sellingPrice; }
    public function setSellingPrice($sellingPrice) { $this->sellingPrice = $sellingPrice; }

    public function getIsActive() { return $this->isActive; }
    public function setIsActive($isActive) { $this->isActive = $isActive; }

    public function getMaterialCategory() { return $this->materialCategory; }
    public function setMaterialCategory(MaterialCategory $materialCategory = null) { $this->materialCategory = $materialCategory; }

    public function getUnit() { return $this->unit; }
    public function setUnit(Unit $unit = null) { $this->unit = $unit; }
}
