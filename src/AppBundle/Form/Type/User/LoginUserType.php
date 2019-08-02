<?php

namespace AppBundle\Form\Type\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class LoginUserType extends AbstractType
{
    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, array(
                'label' => 'Nom d\'utilisateur'
            ))
            ->add('_password', PasswordType::class, array(
                'label' => 'Mot de passe',
            ))
            ->add('_remember_me', CheckboxType::class, array(
                'required' => false,
                'label' => 'Se souvenir de moi',
            ))
            ->add('_csrf_token', HiddenType::class)
            ->add('_target_path', HiddenType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'action' => $this->router->generate('app_user_check'),
                'method' => Request::METHOD_POST,
                'csrf_protection' => false,
            )
        );
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
