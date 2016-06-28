<?php

namespace ArtInvest\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

//
use Doctrine\ORM\Mapping as ORM;

use ArtInvest\UserBundle\Entity\User;
use ArtInvest\CategoryBundle\Entity\Category;
use ArtInvest\ProjectBundle\Entity\Project;

use ArtInvest\CategoryBundle\Form\CategoryType;
use ArtInvest\CategoryBundle\Form\EditorType;

class ProjectType extends AbstractType
{
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text', array('label' => false))
            // ->add('cover', 'file', array('data_class' =>  null, 'label' => false))
            ->add('category', 'entity', [
                'class' => 'CategoryBundle:Category',
                'query_builder' => function($repo){
                    return $repo->createQueryBuilder('c');
                },
                'property' => 'type',
            ], array('label' => false))
            ->add('description', 'textarea', array('label' => '*Description'))
            ->add('risk', 'textarea', array('label' => '*Risks'))
            ->add('delay', 'date', array(
               'widget' => 'single_text',
               'label' => false
            ))
            ->add('amount', null, array('label' => false))
            ->add('abstract', 'textarea', array('label' => false))

            ->add('video', null, array('required'=>false, 'label' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArtInvest\ProjectBundle\Entity\Project'

        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'artinvest_projectbundle_project';
    }
}
