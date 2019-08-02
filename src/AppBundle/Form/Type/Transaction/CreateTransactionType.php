<?php

namespace AppBundle\Form\Type\Transaction;

use AppBundle\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateTransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('taxes', IntegerType::class, array(
                'attr' => array(
                    'min' => 0,
                    'max' => $options['max'],
                )
            ))
            ->add('piggyBank', IntegerType::class, array(
                'attr' => array(
                    'min' => 0,
                    'max' => $options['max'],
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Transaction::class,
            'max' => 5,
        ));
    }
}
