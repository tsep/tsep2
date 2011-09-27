<?php
namespace TSEP\Bundle\SearchBundle\Form;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder->add('query');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'TSEP\Bundle\SearchBundle\Entity\Search',
        );
    }

    public function getName()
    {
        return 'Search';
    }
}