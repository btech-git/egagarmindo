<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\SampleRequestHeader;
use AppBundle\Repository\Transaction\SampleRequestHeaderRepository;

class SampleRequestHeaderForm
{
    private $sampleRequestHeaderRepository;
    
    public function __construct(SampleRequestHeaderRepository $sampleRequestHeaderRepository)
    {
        $this->sampleRequestHeaderRepository = $sampleRequestHeaderRepository;
    }
    
    public function initialize(SampleRequestHeader $sampleRequestHeader, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($sampleRequestHeader->getId())) {
            $lastSampleRequestHeader = $this->sampleRequestHeaderRepository->findRecentBy($year, $month);
            $currentSampleRequestHeader = ($lastSampleRequestHeader === null) ? $sampleRequestHeader : $lastSampleRequestHeader;
            $sampleRequestHeader->setCodeNumberToNext($currentSampleRequestHeader->getCodeNumber(), $year, $month);
            
            $sampleRequestHeader->setStaffFirst($staff);
        }
        $sampleRequestHeader->setStaffLast($staff);
    }
    
    public function finalize(SampleRequestHeader $sampleRequestHeader, array $params = array())
    {
        foreach ($sampleRequestHeader->getSampleRequestDetails() as $sampleRequestDetail) {
            $sampleRequestDetail->setSampleRequestHeader($sampleRequestHeader);
        }
        $this->sync($sampleRequestHeader);
    }
    
    private function sync(SampleRequestHeader $sampleRequestHeader)
    {
        $estimationHeader = $sampleRequestHeader->getQuotation()->getEstimationHeader();
        if ($estimationHeader !== null) {
            $estimationDetails = $estimationHeader->getEstimationDetails();
            foreach ($sampleRequestHeader->getSampleRequestDetails() as $index => $sampleRequestDetail) {
                if ($estimationDetails->containsKey($index)) {
                    $sampleRequestDetail->setEstimationDetail($estimationDetails->get($index));
                    $sampleRequestDetail->setMaterial($estimationDetails->get($index)->getMaterial());
                }
            }
        }
        $sampleRequestHeader->sync();
    }
    
    public function save(SampleRequestHeader $sampleRequestHeader)
    {
        if (empty($sampleRequestHeader->getId())) {
            $this->sampleRequestHeaderRepository->add($sampleRequestHeader, array(
                'sampleRequestDetails' => array('add' => true),
            ));
        } else {
            $this->sampleRequestHeaderRepository->update($sampleRequestHeader, array(
                'sampleRequestDetails' => array('add' => true, 'remove' => true),
            ));
        }
    }
    
    public function delete(SampleRequestHeader $sampleRequestHeader)
    {
        $this->beforeDelete($sampleRequestHeader);
        if (!empty($sampleRequestHeader->getId())) {
            $this->sampleRequestHeaderRepository->remove($sampleRequestHeader, array(
                'sampleRequestDetails' => array('remove' => true),
            ));
        }
    }
    
    protected function beforeDelete(SampleRequestHeader $sampleRequestHeader)
    {
        $sampleRequestHeader->getSampleRequestDetails()->clear();
        $this->sync($sampleRequestHeader);
    }
}