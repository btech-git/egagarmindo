<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\SampleMaterialOutgoingHeader;
use AppBundle\Repository\Transaction\SampleMaterialOutgoingHeaderRepository;

class SampleMaterialOutgoingHeaderForm
{
    private $sampleMaterialOutgoingHeaderRepository;
    
    public function __construct(SampleMaterialOutgoingHeaderRepository $sampleMaterialOutgoingHeaderRepository)
    {
        $this->sampleMaterialOutgoingHeaderRepository = $sampleMaterialOutgoingHeaderRepository;
    }
    
    public function initialize(SampleMaterialOutgoingHeader $sampleMaterialOutgoingHeader, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($sampleMaterialOutgoingHeader->getId())) {
            $lastSampleMaterialOutgoingHeader = $this->sampleMaterialOutgoingHeaderRepository->findRecentBy($year, $month);
            $currentSampleMaterialOutgoingHeader = ($lastSampleMaterialOutgoingHeader === null) ? $sampleMaterialOutgoingHeader : $lastSampleMaterialOutgoingHeader;
            $sampleMaterialOutgoingHeader->setCodeNumberToNext($currentSampleMaterialOutgoingHeader->getCodeNumber(), $year, $month);
            
            $sampleMaterialOutgoingHeader->setStaffFirst($staff);
        }
        $sampleMaterialOutgoingHeader->setStaffLast($staff);
    }
    
    public function finalize(SampleMaterialOutgoingHeader $sampleMaterialOutgoingHeader, array $params = array())
    {
        foreach ($sampleMaterialOutgoingHeader->getSampleMaterialOutgoingDetails() as $sampleMaterialOutgoingDetail) {
            $sampleMaterialOutgoingDetail->setSampleMaterialOutgoingHeader($sampleMaterialOutgoingHeader);
        }
        $this->sync($sampleMaterialOutgoingHeader);
    }
    
    private function sync(SampleMaterialOutgoingHeader $sampleMaterialOutgoingHeader)
    {
        $sampleMaterialOutgoingHeader->sync();
    }
    
    public function save(SampleMaterialOutgoingHeader $sampleMaterialOutgoingHeader)
    {
        if (empty($sampleMaterialOutgoingHeader->getId())) {
            $this->sampleMaterialOutgoingHeaderRepository->add($sampleMaterialOutgoingHeader, array(
                'sampleMaterialOutgoingDetails' => array('add' => true),
            ));
        } else {
            $this->sampleMaterialOutgoingHeaderRepository->update($sampleMaterialOutgoingHeader, array(
                'sampleMaterialOutgoingDetails' => array('add' => true, 'remove' => true),
            ));
        }
    }
    
    public function delete(SampleMaterialOutgoingHeader $sampleMaterialOutgoingHeader)
    {
        $this->beforeDelete($sampleMaterialOutgoingHeader);
        if (!empty($sampleMaterialOutgoingHeader->getId())) {
            $this->sampleMaterialOutgoingHeaderRepository->remove($sampleMaterialOutgoingHeader, array(
                'sampleMaterialOutgoingDetails' => array('remove' => true),
            ));
        }
    }
    
    protected function beforeDelete(SampleMaterialOutgoingHeader $sampleMaterialOutgoingHeader)
    {
        $sampleMaterialOutgoingHeader->getEstimationDetails()->clear();
        $this->sync($sampleMaterialOutgoingHeader);
    }
}