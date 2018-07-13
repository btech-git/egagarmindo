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
use AppBundle\Entity\Transaction\SampleRequestHeader;
use AppBundle\Entity\Transaction\SampleRequestDetail;
use AppBundle\Entity\Transaction\Quotation;

class SampleRequestHeaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transactionDate', DateType::class)
            ->add('deliveryDate', DateType::class)
            ->add('note')
            ->add('quotation')
            ->add('sampleRequestDetails', CollectionType::class, array(
                'entry_type' => SampleRequestDetailType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype_data' => new SampleRequestDetail(),
                'label' => false,
            ))
        ;
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($options) {
                $sampleRequestHeader = $event->getData();
                $options['service']->initialize($sampleRequestHeader, $options['init']);
                $form = $event->getForm();
                $formOptions = array(
                    'class' => Quotation::class,
                );
                if (!empty($sampleRequestHeader->getId())) {
                    $formOptions['disabled'] = true;
                }
                $form->add('quotation', EntityTextType::class, $formOptions);
            })
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) use ($options) {
                $sampleRequestHeader = $event->getData();
                $options['service']->finalize($sampleRequestHeader);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SampleRequestHeader::class,
        ));
        $resolver->setRequired(array('service', 'init'));
    }
}
