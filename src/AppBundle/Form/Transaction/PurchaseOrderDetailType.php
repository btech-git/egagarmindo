<?php

namespace AppBundle\Form\Transaction;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Transaction\PurchaseOrderDetail;
use AppBundle\Entity\Transaction\SampleRequestDetail;
use AppBundle\Entity\Master\Material;
use LibBundle\Form\Type\EntityHiddenType;

class PurchaseOrderDetailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity')
            ->add('unitPrice')
            ->add('discount')
            ->add('sampleRequestDetail', EntityHiddenType::class, array('class' => SampleRequestDetail::class))
            ->add('material', EntityHiddenType::class, array('class' => Material::class))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PurchaseOrderDetail::class,
        ));
    }
}
