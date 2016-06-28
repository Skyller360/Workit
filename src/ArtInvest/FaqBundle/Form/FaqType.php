<?php

namespace ArtInvest\FaqBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FaqType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', 'text', array('label'=>'Ask Your Question :'))
            ->add('answer', 'text', array('label'=>false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArtInvest\FaqBundle\Entity\Faq'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'artinvest_faqbundle_faq';
    }
}
