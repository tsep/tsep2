<?php

namespace TSEP\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('url')
            ->add('regex')
        ;
    }

    public function getName()
    {
        return 'tsep_bundle_adminbundle_profiletype';
    }
}
