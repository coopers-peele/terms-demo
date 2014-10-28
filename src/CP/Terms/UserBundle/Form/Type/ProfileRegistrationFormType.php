<?php

namespace CP\Terms\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileRegistrationFormType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CP\Terms\UserBundle\Model\Profile',
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
            'agreement_for_latest_terms',
            'cp_terms_agreement',
            array(
                'label' => false
            )
        );
    }

    public function getName()
    {
        return 'user_profile';
    }
}
