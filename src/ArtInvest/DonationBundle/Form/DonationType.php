<?php

namespace ArtInvest\DonationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DonationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', 'number', array('required' => true,'attr' => array('class' => 'form-control totalInput', 'placeholder' => '$')))
            ->add('hidden', 'checkbox', array('label' => '', 'required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArtInvest\DonationBundle\Entity\Donation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'artinvest_donationbundle_donation';
    }
}
