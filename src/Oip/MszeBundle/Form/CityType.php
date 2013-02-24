<?php

namespace Oip\MszeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('id', 'hidden')
            ->add('name')
            ->add('district')
            ->add('foto')
            //->add('captcha', 'genemu_recaptcha', array("property_path" => false,));
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Oip\MszeBundle\Entity\City'
        ));
    }

    public function getName()
    {
        return 'oip_mszebundle_citytype';
    }
}
