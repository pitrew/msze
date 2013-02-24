<?php

namespace Oip\MszeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start_time')
            ->add('details')
            ->add('day_mon')
            ->add('day_tue')
            ->add('day_wed')
            ->add('day_thu')
            ->add('day_fri')
            ->add('day_sat')
            ->add('day_sun')
            ->add('church')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Oip\MszeBundle\Entity\Mass'
        ));
    }

    public function getName()
    {
        return 'oip_mszebundle_masstype';
    }
}
