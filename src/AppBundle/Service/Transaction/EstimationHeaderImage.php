<?php

namespace AppBundle\Service\Transaction;

use LibBundle\Doctrine\ObjectPersister;
use LibBundle\Doctrine\EntityRepository;
use AppBundle\Entity\Transaction\EstimationHeader;
use AppBundle\Entity\Transaction\EstimationImage;

class EstimationHeaderImage
{
    private $estimationImageRepository;
    
    public function __construct(EntityRepository $estimationImageRepository)
    {
        $this->estimationImageRepository = $estimationImageRepository;
    }
    
    public function save(array $uploadedFiles, EstimationHeader $estimationHeader, $directory)
    {
        $estimationImages = array();
        foreach ($uploadedFiles as $i => $uploadedFile) {
            if ($uploadedFile->isValid()) {
                $estimationImage = new EstimationImage();
                $estimationImage->setFileExtension($uploadedFile->getClientOriginalExtension());
                $estimationImage->setEstimationHeader($estimationHeader);
                $estimationImages[$i] = $estimationImage;
            }
        }
        if (count($uploadedFiles) === count($estimationImages)) {
            ObjectPersister::save(function() use ($estimationImages) {
                $this->estimationImageRepository->add($estimationImages);
            });
            foreach ($estimationImages as $i => $estimationImage) {
                $uploadedFiles[$i]->move($directory, $estimationImage->getId() . "." . $estimationImage->getFileExtension());
            }
        }
    }
    
    public function delete(array $estimationImages)
    {
        $this->estimationImageRepository->remove($estimationImages);
    }
}