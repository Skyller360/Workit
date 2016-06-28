<?php

namespace ArtInvest\CommentBundle\Controller;

use ArtInvest\CommentBundle\Entity\Comment;
use ArtInvest\CommentBundle\Form\CommentType;
use ArtInvest\ProjectBundle\Entity\Project;
use ArtInvest\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;   

/**
 * Comment controller.
 *
 */
class CommentController extends Controller
{

    /**
     * Lists all Comment entities.
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

        $entities = $em->getRepository('CommentBundle:Comment')->findAll();

        return $this->render('CommentBundle:Comment:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Comment entity.
     *
     */
    public function createAction(Request $request)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id'])) {
            $this->notice('error', 'You need to be connected to post a comment');
            return $this->redirect($this->generateUrl('_index'));
        }
        $entity = new Comment();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('UserBundle:User')->find($session['id']);
        $project = $em->getRepository('ProjectBundle:Project')->find($session['project_id']);
        $date = new \DateTime('now');

        if ($form->isValid()) {
        
            $entity->setProject($project);
            $entity->setUser($user);
            $entity->setDate($date);
            $em->persist($entity);
            $em->flush();
            $this->notice('success', 'Comment successfully posted');

            return $this->redirect($this->generateUrl('_project_showOne', array('id' => $project->getId())));
        }else{
            $this->notice('error', 'Your comment length is incorrect');
        }

        return $this->redirect($this->generateUrl('_project_showOne', array('id' => $project->getId())));
    }

    /**
     * Creates a form to create a Comment entity.
     *
     * @param Comment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Comment $entity)
    {
        $form = $this->createForm(new CommentType(), $entity, array(
            'action' => $this->generateUrl('comment_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Comment entity.
     *
     */
    public function newAction()
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        $entity = new Comment();
        $form   = $this->createCreateForm($entity);

        return $this->render('CommentBundle:Comment:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Comment entity.
     *
     */
    public function showAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CommentBundle:Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CommentBundle:Comment:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Comment entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CommentBundle:Comment')->find($id);
        $user = $entity->getUser();
        $userId = $user->getId();

        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id']) || $session['id'] != $userId) {
            return $this->redirect($this->generateUrl('_index'));
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CommentBundle:Comment:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Comment entity.
    *
    * @param Comment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Comment $entity)
    {
        $form = $this->createForm(new CommentType(), $entity, array(
            'action' => $this->generateUrl('comment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Comment entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CommentBundle:Comment')->find($id);
        $user = $entity->getUser();
        $userId = $user->getId();

        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id']) || $session['id'] != $userId) {
            return $this->redirect($this->generateUrl('_index'));
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->notice('success', 'Comment successfully edited');
            return $this->redirect($this->generateUrl('comment_edit', array('id' => $id)));
        }

        return $this->render('CommentBundle:Comment:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Comment entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CommentBundle:Comment')->find($id);
        $user = $entity->getUser();
        $userId = $user->getId();
        
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id']) || $session['id'] != $userId) {
            return $this->redirect($this->generateUrl('_index'));
        }
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CommentBundle:Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $em->remove($entity);
        $em->flush();
        
        $this->notice('success', 'Comment successfully deleted');
        return $this->redirect($this->generateUrl('_admin_comment'));
    }

    /**
     * Creates a form to delete a Comment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    public function showCommentAction($id){

        $session = new Session();
        $session = $this->get('session')->all();
        $simple = false;
        if (!isset($session['id'])) {
            $simple = true;
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CommentBundle:Comment')->findByProject($id);

        return $this->render('CommentBundle:Comment:showcomment.html.twig', array('entities' => $entity, 'simple' => $simple, 'id' => $id));
    }

    public function reportAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CommentBundle:Comment')->find($id);
        $entity->setReported(true);
        $em->flush();

        $this->notice('success', 'Comment successfully reported');
        return $this->redirect($this->generateUrl('_project_showOne', array('id' => $entity->getProject()->getId())));
    }

    private function security($value)
    {
        $securedValue = htmlentities(addslashes($value));
        return $securedValue;
    }

    private function formDatas($form, $name)
    {
        $value = $form[$name]->getData();
        $secured = $this->security($value);
        return $secured;
    }

    private function notice($type, $msg){
        $session = new Session;
        $session->getFlashBag()->add($type, $msg);
    }

}
