<?php

namespace AppBundle\Form\Transaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Transaction\SampleRequestDetail;
use AppBundle\Entity\Transaction\EstimationDetail;
use AppBundle\Entity\Master\Material;
use LibBundle\Form\Type\EntityHiddenType;

class SampleRequestDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity')
            ->add('material', EntityHiddenType::class, array('class' => Material::class))
            ->add('estimationDetail', EntityHiddenType::class, array('class' => EstimationDetail::class))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SampleRequestDetail::class,
        ));
    }
}
