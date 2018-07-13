<?php

namespace AppBundle\Service\Transaction;

use AppBundle\Entity\Transaction\EstimationHeader;
use AppBundle\Repository\Transaction\EstimationHeaderRepository;

class EstimationHeaderForm
{
    private $estimationHeaderRepository;
    
    public function __construct(EstimationHeaderRepository $estimationHeaderRepository)
    {
        $this->estimationHeaderRepository = $estimationHeaderRepository;
    }
    
    public function initialize(EstimationHeader $estimationHeader, array $params = array())
    {
        list($month, $year, $staff) = array($params['month'], $params['year'], $params['staff']);
        
        if (empty($estimationHeader->getId())) {
            $lastEstimationHeader = $this->estimationHeaderRepository->findRecentBy($year, $month);
            $currentEstimationHeader = ($lastEstimationHeader === null) ? $estimationHeader : $lastEstimationHeader;
            $estimationHeader->setCodeNumberToNext($currentEstimationHeader->getCodeNumber(), $year, $month);
            
            $estimationHeader->setStaffFirst($staff);
        }
        $estimationHeader->setStaffLast($staff);
    }
    
    public function finalize(EstimationHeader $estimationHeader, array $params = array())
    {
        foreach ($estimationHeader->getEstimationDetails() as $estimationDetail) {
            $estimationDetail->setEstimationHeader($estimationHeader);
        }
        $this->sync($estimationHeader);
    }
    
    private function sync(EstimationHeader $estimationHeader)
    {
        $estimationHeader->sync();
    }
    
    public function save(EstimationHeader $estimationHeader)
    {
        if (empty($estimationHeader->getId())) {
            $this->estimationHeaderRepository->add($estimationHeader, array(
                'estimationDetails' => array('add' => true),
            ));
        } else {
            $this->estimationHeaderRepository->update($estimationHeader, array(
                'estimationDetails' => array('add' => true, 'remove' => true),
            ));
        }
    }
    
    public function delete(EstimationHeader $estimationHeader)
    {
        $this->beforeDelete($estimationHeader);
        if (!empty($estimationHeader->getId())) {
            $this->estimationHeaderRepository->remove($estimationHeader, array(
                'estimationDetails' => array('remove' => true),
            ));
        }
    }
    
    protected function beforeDelete(EstimationHeader $estimationHeader)
    {
        $estimationHeader->getEstimationDetails()->clear();
        $this->sync($estimationHeader);
    }
}