<?php

namespace TSEP\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PageType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('url')
            ->add('text')
            ->add('profile')
        ;
    }

    public function getName()
    {
        return 'tsep_bundle_adminbundle_pagetype';
    }
}
