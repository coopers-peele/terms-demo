<?php

namespace CP\Terms\DemoBundle\Form\Type;

use CP\Terms\UserBundle\Form\Type\ProfileRegistrationFormType;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationFormType extends BaseType
{
    protected $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'translation_domain' => 'FOSUserBundle',
            'intention' => 'registration',
            'cascade_validation' => true
        ));
    }

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'username',
            'text',
            array(
                'label' => 'form.username',
                'attr' => array(
                    'class' => 'form-control'
                )
            )
        );

        $builder->add(
            'plainPassword',
            'repeated',
            array(
                'type' => 'password',
                'first_options' => array('label' => 'form.password','attr' => array('class' => 'form-control')),
                'second_options' => array('label' => 'form.password_confirmation','attr' => array('class' => 'form-control')),
                'invalid_message' => 'fos_user.password.mismatch'
            )
        );

        $builder->add(
            'profile',
            new ProfileRegistrationFormType(),
            array(
                'label' => FALSE
            )
        );
    }

    public function getName()
    {
        return 'admin_user_registration';
    }
}
