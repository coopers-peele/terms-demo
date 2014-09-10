<?php

namespace CP\Terms\DemoBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsersFilterType extends BaseAbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'name' => 'users_filters'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'text', array(
            'attr' => array(
                'class' => 'form-control'
            )
        ));

        $builder->add('username', 'text', array(
            'attr' => array(
                'class' => 'form-control'
            )
        ));

        $builder->add('tos', 'cp_terms_choice', array(
            'final_only' => true,
            'include_none' => true,
            'attr' => array(
                'class' => 'form-control'
            )
        ));
    }

    public function getName()
    {
        return 'users_filters';
    }
}
