<?php

namespace AppBundle\Form\Transaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use LibBundle\Form\Type\EntityTextType;
use AppBundle\Entity\Transaction\SampleMaterialOutgoingHeader;
use AppBundle\Entity\Transaction\SampleMaterialOutgoingDetail;

class SampleMaterialOutgoingHeaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transactionDate', DateType::class)
            ->add('outgoingDate', DateType::class)
            ->add('note')
            ->add('sampleRequestHeader')
            ->add('sampleMaterialOutgoingDetails', CollectionType::class, array(
                'entry_type' => SampleMaterialOutgoingDetailType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype_data' => new SampleMaterialOutgoingDetail(),
                'label' => false,
            ))
        ;
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($options) {
                $sampleMaterialOutgoingHeader = $event->getData();
                $options['service']->initialize($sampleMaterialOutgoingHeader, $options['init']);
                $form = $event->getForm();
                $formOptions = array(
                    'class' => SampleRequestHeader::class,
                );
                if (!empty($sampleMaterialOutgoingHeader->getId())) {
                    $formOptions['disabled'] = true;
                }
                $form->add('sampleRequestHeader', EntityTextType::class, $formOptions);
            })
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) use ($options) {
                $sampleMaterialOutgoingHeader = $event->getData();
                $options['service']->finalize($sampleMaterialOutgoingHeader);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SampleMaterialOutgoingHeader::class,
        ));
        $resolver->setRequired(array('service', 'init'));
    }
}
