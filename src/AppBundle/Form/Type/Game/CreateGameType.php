<?php

namespace AppBundle\Form\Type\Game;

use AppBundle\Entity\Game;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateGameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom de la partie',
            ))
            ->add('roundMoney', IntegerType::class, array(
                'label' => 'Quantité d\'argent par tour'
            ))
            ->add('roundNumber', IntegerType::class, array(
                'label' => 'Nombre de tour'
            ))
            ->add('maxPlayerNumber', IntegerType::class, array(
                'label' => 'Nombre maximum de joueur',
                'required' => false,
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Game::class,
        ));
    }
}
