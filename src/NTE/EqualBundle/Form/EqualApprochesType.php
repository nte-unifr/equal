<?php

namespace NTE\EqualBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EqualApprochesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('dimensions', 'entity', array(
            'class' => 'NTEEqualBundle:EqualDimensions',
            'property' => 'nom',
            'multiple' => true,
            'expanded' => true,
            'label' => 'Veuillez choisir les dimensions que vous désirez évaluer :'
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    public function getName()
    {
        return 'equalapprochestype';
    }
}
