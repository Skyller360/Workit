<?php

namespace ArtInvest\MediaBundle\Controller;

use ArtInvest\MediaBundle\Entity\Media;
use ArtInvest\MediaBundle\Form\MediaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Media controller.
 *
 */
class MediaController extends Controller
{

    /**
     * Lists all Media entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MediaBundle:Media')->findAll();

        return $this->render('MediaBundle:Media:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Media entity.
     *
     */
    public function createAction(Request $request)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        $entity = new Media();
        
        $id = $session['project_id'];
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ProjectBundle:Project')->find($id);
        $user = $project->getUser();

        if (!isset($session['id']) || $session['id'] != $user->getId()) {
            return $this->redirect($this->generateUrl('_index'));
        }
        

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if($form->isValid()){        

            $link = $_POST['link'];
            $entity->setImage($link);        
            
            $entity->setProjects($project);

            $em->persist($entity);
            $em->flush();
    
            return $this->redirect($this->generateUrl('_project_showOne', array('id' => $project->getId())));
        }

        throw new \Exception('Bad format !');
    }

    /**
     * Creates a form to create a Media entity.
     *
     * @param Media $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Media $entity)
    {
        $form = $this->createForm(new MediaType(), $entity, array(
            'action' => $this->generateUrl('media_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Media entity.
     *
     */
    public function newAction($id)
    {
        $entity = new Media();
        $form   = $this->createCreateForm($entity);

        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }

        return $this->render('MediaBundle:Media:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'id' => $id,
        ));
    }


    /**
     * Finds and displays a Media entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MediaBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MediaBundle:Media:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function showMediaAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        $em = $this->getDoctrine()->getManager();
        $creator = false;
        $set = false;
        $image = false;
        $empty = false;
        $document = false;


        $project = $em->getRepository('ProjectBundle:Project')->find($id);
        $user = $project->getUser();

        $entities = $em->getRepository('MediaBundle:Media')->findByProjects($id);

        if (isset($session['id']) && $session['id'] == $user->getId()) {
            $creator = true;
        }

        if(empty($entities)){
            $empty = true;
        }

        foreach ($entities as $value) {
            if ($value->getDocument() != NULL) {
                $documents[] = $value->getDocument();
                $documentId = $value->getId();
                $document = end($documents);
            }
        }

        if (!$empty) {
            $entityId = end($entities)->getId();
            $deleteForm = $this->createDeleteForm($entityId);
        }
        else{
            $deleteForm = $this->createDeleteForm($id);
            $entityId = false;
        }
        

        if (isset($document)) {
            $set = true;
        }



        return $this->render('MediaBundle:Media:showMedia.html.twig', array(
            'entities'      => $entities,
            'project_id' => $session['project_id'],
            'delete_form' => $deleteForm->createView(),
            'document' => $document,
            'documentId' => $entityId,
            'set' => $set,
            'creator' => $creator,
            'empty' => $empty,
        ));
    }

    /**
     * Displays a form to edit an existing Media entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $session = new Session();
        $session = $this->get('session')->all();
        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        if (!$session['usergroup']) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $entity = $em->getRepository('MediaBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MediaBundle:Media:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Media entity.
    *
    * @param Media $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Media $entity)
    {
        $form = $this->createForm(new MediaType(), $entity, array(
            'action' => $this->generateUrl('media_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Media entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MediaBundle:Media')->find($id);

        $session = new Session();
        $session = $this->get('session')->all();
        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        if (!$session['usergroup']) {
            return $this->redirect($this->generateUrl('_index'));
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('media_edit', array('id' => $id)));
        }

        return $this->render('MediaBundle:Media:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Media entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        
        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MediaBundle:Media')->find($id);
        $em->remove($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('_project_showOne',array('id' => $session['project_id'])));
    }

    /**
     * Creates a form to delete a Media entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        $projectId = $session['project_id'];

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('_project_showOne', array('id' => $projectId)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


}
