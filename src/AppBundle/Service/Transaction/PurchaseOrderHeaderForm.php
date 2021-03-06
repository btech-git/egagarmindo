<?php

namespace AppBundle\Service\Transaction;

use LibBundle\Doctrine\ObjectPersister;
use AppBundle\Entity\Transaction\PurchaseOrderHeader;
use AppBundle\Repository\Transaction\PurchaseOrderHeaderRepository;

class PurchaseOrderHeaderForm
{
    private $purchaseOrderHeaderRepository;
    
    public function __construct(PurchaseOrderHeaderRepository $purchaseOrderHeaderRepository)
    {
        $this->purchaseOrderHeaderRepository = $purchaseOrderHeaderRepository;
    }
    
    public function initialize(PurchaseOrderHeader $purchaseOrderHeader, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($purchaseOrderHeader->getId())) {
            $lastPurchaseOrderHeader = $this->purchaseOrderHeaderRepository->findRecentBy($year, $month);
            $currentPurchaseOrderHeader = ($lastPurchaseOrderHeader === null) ? $purchaseOrderHeader : $lastPurchaseOrderHeader;
            $purchaseOrderHeader->setCodeNumberToNext($currentPurchaseOrderHeader->getCodeNumber(), $year, $month);
            
            $purchaseOrderHeader->setStaffFirst($staff);
            $purchaseOrderHeader->setTransactionType(PurchaseOrderHeader::TRANSACTION_TYPE_SAMPLE);
        }
        $purchaseOrderHeader->setStaffLast($staff);
    }
    
    public function finalize(PurchaseOrderHeader $purchaseOrderHeader, array $params = array())
    {
        foreach ($purchaseOrderHeader->getPurchaseOrderDetails() as $purchaseOrderDetail) {
            $purchaseOrderDetail->setPurchaseOrderHeader($purchaseOrderHeader);
        }
        $this->sync($purchaseOrderHeader);
    }
    
    private function sync(PurchaseOrderHeader $purchaseOrderHeader)
    {
        $sampleRequestHeader = $purchaseOrderHeader->getSampleRequestHeader();
        if ($sampleRequestHeader !== null) {
            $sampleRequestDetails = $sampleRequestHeader->getSampleRequestDetails();
            foreach ($purchaseOrderHeader->getPurchaseOrderDetails() as $index => $purchaseOrderDetail) {
                if ($sampleRequestDetails->containsKey($index)) {
                    $purchaseOrderDetail->setSampleRequestDetail($sampleRequestDetails->get($index));
                    $purchaseOrderDetail->setMaterial($sampleRequestDetails->get($index)->getMaterial());
                }
            }
        }
        $purchaseOrderHeader->sync();
    }
    
    public function save(PurchaseOrderHeader $purchaseOrderHeader)
    {
        if (empty($purchaseOrderHeader->getId())) {
            ObjectPersister::save(function() use ($purchaseOrderHeader) {
                $this->purchaseOrderHeaderRepository->add($purchaseOrderHeader, array(
                    'purchaseOrderDetails' => array('add' => true),
                ));
            });
        } else {
            ObjectPersister::save(function() use ($purchaseOrderHeader) {
                $this->purchaseOrderHeaderRepository->update($purchaseOrderHeader, array(
                    'purchaseOrderDetails' => array('add' => true, 'remove' => true),
                ));
            });
        }
    }
    
    public function delete(PurchaseOrderHeader $purchaseOrderHeader)
    {
        $this->beforeDelete($purchaseOrderHeader);
        if (!empty($purchaseOrderHeader->getId())) {
            ObjectPersister::save(function() use ($purchaseOrderHeader) {
                $this->purchaseOrderHeaderRepository->remove($purchaseOrderHeader, array(
                    'purchaseOrderDetails' => array('remove' => true),
                ));
            });
        }
    }
    
    protected function beforeDelete(PurchaseOrderHeader $purchaseOrderHeader)
    {
        $purchaseOrderHeader->getPurchaseOrderDetails()->clear();
        $this->sync($purchaseOrderHeader);
    }
}