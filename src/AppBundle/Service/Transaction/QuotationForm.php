<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\Quotation;
use AppBundle\Repository\Transaction\QuotationRepository;

class QuotationForm
{
    private $quotationRepository;
    
    public function __construct(QuotationRepository $quotationRepository)
    {
        $this->quotationRepository = $quotationRepository;
    }
    
    public function initialize(Quotation $quotation, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($quotation->getId())) {
            $lastQuotation = $this->quotationRepository->findRecentBy($year, $month);
            $currentQuotation = ($lastQuotation === null) ? $quotation : $lastQuotation;
            $quotation->setCodeNumberToNext($currentQuotation->getCodeNumber(), $year, $month);
            
            $quotation->setStaffFirst($staff);
        }
        $quotation->setStaffLast($staff);
    }
    
    public function finalize(Quotation $quotation, array $params = array())
    {
        $this->sync($quotation);
    }
    
    private function sync(Quotation $quotation)
    {
//        $estimationHeader = $quotation->getEstimationHeader();
//        if ($estimationHeader !== null) {
//            $quotation->setTotalMaterialCost('100'); //$estimationHeader->getTotalMaterialCost());
//        }
        $quotation->sync();
    }
    
    public function save(Quotation $quotation)
    {
        if (empty($quotation->getId())) {
            $this->quotationRepository->add($quotation);
        } else {
            $this->quotationRepository->update($quotation);
        }
    }
    
    public function delete(Quotation $quotation)
    {
        $this->beforeDelete($quotation);
        if (!empty($quotation->getId())) {
            $this->quotationRepository->remove($quotation);
        }
    }
    
    protected function beforeDelete(Quotation $quotation)
    {
        $this->sync($quotation);
    }
}