<?php

namespace ArtInvest\FaqBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ArtInvest\FaqBundle\Entity\Faq;
use ArtInvest\UserBundle\Entity\User;

use ArtInvest\FaqBundle\Form\FaqType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * Faq controller.
 *
 */
class FaqController extends Controller
{

    /**
     * Lists all Faq entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('FaqBundle:Faq')->findAll();
        $session =  new Session();
        $session = $this->get('session')->all();
        (isset($session['id']))? $logged = true : $logged = false; 

        // Create subscribe form
        $user = new User();
        $subscribe_form = $this->createFormBuilder($user)
            ->setAction($this->generateUrl('user_homepage'))
            ->add('login', 'text', array('attr' => array('class' => "form-control")))
            ->add('mail', 'email', array('attr' => array('class' => "form-control")))            
            ->add('hash', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'Password doesn\'t match',
                    'options' => array('required' => true),
                    'first_options'  => array('label' => 'Password'),
                    'second_options' => array('label' => 'Confirm Password'),
                    'attr' => array('class' => 'form-control')
            ))
            ->add('birthday', 'birthday', array(
                    'widget' => 'single_text',
                    'label' => 'Birthday (yyyy-mm-dd)',
                    'attr' => array('class' => 'form-control')
            ))
            ->add('save', 'submit', array('attr' => array('class' => 'form-control btn orange-btn')))            
            ->add('reset', 'reset', array('attr' => array('class' => 'form-control btn btn-info')))
           ->getForm();   

        foreach ($entities as $value) {
            if ($value->getAnswer() != NULL) {
                $responds[] = $value;
            }
         } 

         // Create Faq Form
        $faq = new Faq();
        $faq_form   = $this->createCreateForm($faq);

        $faq_form->add('question', 'text', array('label' => 'Ask Your Question :'));        
        $faq_form->add('answer', 'text');
        $faq_form->add('submit', 'submit', array('label' => 'Submit question'));

        return $this->render('FaqBundle:Faq:index.html.twig', array(
            'entities' => $entities,
            'responds' => $responds,
            'logged' => $logged,
            'session' => $session,
            'subscribe_form' => $subscribe_form->createView(),
            'faq_form' => $faq_form->createView(),

        ));
    }

    /**
     * Lists all Faq entities.
     *
     */
    public function adminAction()
    {
        $session = new Session();
        $session = $this->get('session')->all();
        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        if (!$session['usergroup']) {
            return $this->redirect($this->generateUrl('_index'));
        }
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FaqBundle:Faq')->findBy(array('answer' => NULL)); 

        return $this->render('FaqBundle:Faq:adminIndex.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Faq entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Faq();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->notice('success', 'Question asked ! you must wait for an admin to answer');
            return $this->redirect($this->generateUrl('faq'));
        }

        return $this->render('FaqBundle:Faq:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Faq entity.
     *
     * @param Faq $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Faq $entity)
    {
        $form = $this->createForm(new FaqType(), $entity, array(
            'action' => $this->generateUrl('faq_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ask My Question'));

        return $form;
    }

    /**
     * Displays a form to create a new Faq entity.
     *
     */
    public function newAction()
    {
        $entity = new Faq();
        $form   = $this->createCreateForm($entity);

        $form->add('question', 'text', array('label' => 'Ask Your Question :'));        
        $form->add('answer', 'text', array('label' => 'Give an answer :'));
        $form->add('submit', 'submit', array('label' => 'Submit question'));

        return $this->render('FaqBundle:Faq:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Faq entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FaqBundle:Faq')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Faq entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FaqBundle:Faq:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Faq entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FaqBundle:Faq')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Faq entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FaqBundle:Faq:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing Faq entity.
     *
     */
    public function answerFormAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FaqBundle:Faq')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Faq entity.');
        }
        
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        $editForm->add('question', 'text', array('label' => 'Edit asked question :'));
        $editForm->add('answer', 'text', array('label' => 'Give an answer :'));
        $editForm->add('submit', 'submit', array('label' => 'Answer this question','attr' => array('class'=>'btn btn-primary')));

        return $this->render('FaqBundle:Faq:answer.html.twig', array(
            'entity'      => $entity,
            'form'        => $editForm->createView()
        ));
    }
    
    /**
    * Creates a form to edit a Faq entity.
    *
    * @param Faq $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Faq $entity)
    {
        $form = $this->createForm(new FaqType(), $entity, array(
            'action' => $this->generateUrl('faq_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Faq entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        
        $session = new Session();
        $session = $this->get('session')->all();
        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        if (!$session['usergroup']) {
            return $this->redirect($this->generateUrl('_index'));
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FaqBundle:Faq')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Faq entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            //FlashMessages
            $this->notice('success', 'Question succesfully updated');
            return $this->redirect($this->generateUrl('_admin_faq', array('id' => $id)));
        }

        return $this->render('FaqBundle:Faq:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView()
        ));
    }
    /**
     * Deletes a Faq entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        if (!$session['usergroup']) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FaqBundle:Faq')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Faq entity.');
        }

        $em->remove($entity);
        $em->flush();

        // FlashMessages
        $this->notice('success', 'Question succesfully deleted.');


        return $this->redirect($this->generateUrl('_admin_faq'));
    }

    /**
     * Creates a form to delete a Faq entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('faq_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    private function notice($type, $msg){
        $session = new Session;
        $session->getFlashBag()->add($type, $msg);
    }
}
