<?php

namespace ArtInvest\DonationBundle\Controller;

use ArtInvest\DonationBundle\Entity\Donation;
use ArtInvest\DonationBundle\Form\DonationType;
use ArtInvest\ProjectBundle\Entity\Project;
use ArtInvest\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * Donation controller.
 *
 */
class DonationController extends Controller
{

    /**
     * Lists all Donation entities.
     *
     */
    public function indexAction()
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['usergroup'])) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DonationBundle:Donation')->findAll();

        return $this->render('DonationBundle:Donation:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Donation entity.
     *
     */
    public function createAction(Request $request)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        $thisUser = $session['login'];

        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $entity = new Donation();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);


        // Get project info
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ProjectBundle:Project')->find($session['project_id']);

        // Get Goodies of this project
        $g = $em->getRepository('ProjectBundle:Project')->find($session['project_id'])->getGoodies();          
        $goodies = explode("|", $g); 
        
        $i=0;
        while(isset($goodies[$i])){
            $goodie[] = explode('#', $goodies[$i]);
            $i++;
        }

        (isset($session['id']))? $logged = true : $logged = false; 

        if ($form->isValid()) {

            $project = $em->getRepository('ProjectBundle:Project')->find($session['project_id']);
            $entity->setProjects($project);

            $user = $em->getRepository('UserBundle:User')->find($session['id']);
            $entity->setUser($user);

            $em->persist($entity);
            $em->flush();


            $this->notice('success', 'Your contribution was succesfully sended');

            return $this->redirect($this->generateUrl('_project_showOne', array('id' => $project->getId())));
        }else{
        }
            
       

        return $this->render('DonationBundle:Donation:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'id' => $session['project_id'],
            'logged' => $logged,
            'project' => $project,
            'goodies' => $goodie,
            'thisUser' => $thisUser,
        ));
    }

    /**
     * Creates a form to create a Donation entity.
     *
     * @param Donation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Donation $entity)
    {
        $form = $this->createForm(new DonationType(), $entity, array(
            'action' => $this->generateUrl('donation_create'),
            'method' => 'POST',
        ));

        $form->add('save', 'submit', array('label' => 'Pay', 'attr' => array('class' => 'col-xs-12 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 btn orange-btn')));

        return $form;
    }

    /**
     * Displays a form to create a new Donation entity.
     *
     */
    public function newAction()
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $entity = new Donation();
        $form   = $this->createCreateForm($entity);

        return $this->render('DonationBundle:Donation:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Donation entity.
     *
     */
    public function showAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DonationBundle:Donation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Donation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DonationBundle:Donation:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'project_id' => $session['project_id'],
        ));
    }

    public function showDonationAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        $users;
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('DonationBundle:Donation')->findByProjects($id);

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DonationBundle:Donation:showDonation.html.twig', array(
            'entities'      => $entities,
            'project_id' => $session['project_id'],
        ));
    }

    /**
     * Displays a form to edit an existing Donation entity.
     *
     */
    public function editAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['usergroup'])) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DonationBundle:Donation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Donation entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('DonationBundle:Donation:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Donation entity.
    *
    * @param Donation $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Donation $entity)
    {
        $form = $this->createForm(new DonationType(), $entity, array(
            'action' => $this->generateUrl('donation_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Donation entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['usergroup'])) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('DonationBundle:Donation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Donation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('donation_edit', array('id' => $id)));
        }

        return $this->render('DonationBundle:Donation:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Donation entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['usergroup'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('DonationBundle:Donation')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Donation entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('donation'));
    }

    /**
     * Creates a form to delete a Donation entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('donation_delete', array('id' => $id)))
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
