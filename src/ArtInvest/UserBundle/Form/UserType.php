<?php

namespace ArtInvest\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fname','text', array('label' => 'First name', 'attr' => array('class' => 'form-control')))
            ->add('lname','text', array('label' => 'Last Name', 'attr' => array('class' => 'form-control')))
            ->add('mail','email', array('label' => 'E-mail', 'attr' => array('class' => 'form-control')))
            // ->add('avatar',null, array('label' => 'Avatar',
            //                               'data_class' => null,
            //                               'required' => false,
            //                               'label' => false,
            //                               'attr' => array('class' => 'form-control')
            //     ))
            ->add('facebook','text', array('label' => 'Facebook', 'required' => false, 'attr' => array('class' => 'form-control')))
            ->add('bio','textarea', array('label' => 'Biography', 'attr' => array('class' => 'form-control')))
            ->add('location','text', array('label' => 'Location', 'attr' => array('class' => 'form-control')))
            ->add('website','text', array('label' => 'Website', 'required' => false, 'attr' => array('class' => 'form-control')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArtInvest\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'artinvest_userbundle_user';
    }
}
