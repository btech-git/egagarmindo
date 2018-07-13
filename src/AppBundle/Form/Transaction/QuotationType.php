<?php

namespace AppBundle\Form\Transaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use LibBundle\Form\Type\EntityTextType;
use AppBundle\Entity\Transaction\Quotation;
use AppBundle\Entity\Transaction\EstimationHeader;

class QuotationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('transactionDate', DateType::class)
            ->add('samplingPercentage')
            ->add('overheadPercentage')
            ->add('transportationPercentage')
            ->add('estimatedSellingPercentage1')
            ->add('estimatedSellingPercentage2')
            ->add('estimatedSellingPercentage3')
            ->add('estimatedSellingPercentage4')
            ->add('estimatedSellingPercentage5')
            ->add('quantity')
            ->add('sellingUnitPrice')
            ->add('note')
            ->add('estimationHeader')
        ;
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($options) {
                $quotation = $event->getData();
                $options['service']->initialize($quotation, $options['init']);
                $form = $event->getForm();
                $formOptions = array(
                    'class' => EstimationHeader::class,
                );
                if (!empty($quotation->getId())) {
                    $formOptions['disabled'] = true;
                }
                $form->add('estimationHeader', EntityTextType::class, $formOptions);
            })
            ->addEventListener(FormEvents::SUBMIT, function(FormEvent $event) use ($options) {
                $quotation = $event->getData();
                $options['service']->finalize($quotation);
            })
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Quotation::class,
        ));
        $resolver->setRequired(array('service', 'init'));
    }
}
