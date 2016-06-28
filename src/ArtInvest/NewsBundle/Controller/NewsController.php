<?php

namespace ArtInvest\NewsBundle\Controller;

use ArtInvest\NewsBundle\Entity\News;
use ArtInvest\NewsBundle\Form\NewsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


/**
 * News controller.
 *
 */
class NewsController extends Controller
{

    /**
     * Lists all News entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('NewsBundle:News')->findAll();

        return $this->render('NewsBundle:News:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new News entity.
     *
     */
    public function createAction(Request $request)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ProjectBundle:Project')->find($session['project_id']);
        $user = $project->getUser();

        if (!isset($session['id']) || $session['id'] != $user->getId()) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $entity = new News();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $title = $this->formDatas($form, 'title');
            $entity->setTitle($title);
            
            $entity->setProjects($project);

            $date = new \DateTime('now');
            $entity->setDate($date);

            $em->persist($entity);
            $em->flush();

            $this->notice('success', 'News successfully created');

            $creator = false;

            return $this->redirect($this->generateUrl('_project_showOne', array('id' => $project->getId())));
        }
            return $this->redirect($this->generateUrl('_project_showOne', array('id' => $project->getId())));

        // return $this->render('NewsBundle:News:new.html.twig', array(
        //     'entity' => $entity,
        //     'form'   => $form->createView(),
        // ));
    }

    /**
     * Creates a form to create a News entity.
     *
     * @param News $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(News $entity)
    {
        $form = $this->createForm(new NewsType(), $entity, array(
            'action' => $this->generateUrl('news_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new News entity.
     *
     */
    public function newAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        $entity = new News();
        $form   = $this->createCreateForm($entity);
        $em = $this->getDoctrine()->getManager();
        $project = $em->getRepository('ProjectBundle:Project')->find($id);
        $user = $project->getUser();

        if (!isset($session['id']) || $user->getId() != $session['id']) {
            return $this->redirect($this->generateUrl('_index'));
        }

        return $this->render('NewsBundle:News:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a News entity.
     *
     */
    public function showAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NewsBundle:News')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('NewsBundle:News:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function showNewsAction($id){

        $session = new Session();
        $session = $this->get('session')->all();
        $creator = false;
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('NewsBundle:News')->findByProjects($id);
        $project = $em->getRepository('ProjectBundle:Project')->find($id);
        $user = $project->getUser();
        if(isset($session['id'])){
            if($session['id'] == $user->getId()){
                $creator = true;
            }
        }

        return $this->render('NewsBundle:News:shownews.html.twig', array('entities' => $entity, 'id' => $id, 'creator' => $creator));
    }

    /**
     * Displays a form to edit an existing News entity.
     *
     */
    public function editAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NewsBundle:News')->find($id);

        $project = $entity->getProjects();
        $user = $project->getUser();
        $userId = $user->getId();

        if (!isset($session['id']) || $session['id'] != $userId) {
            return $this->redirect($this->generateUrl('_index'));
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        $project_id = $session['project_id'];

        return $this->render('NewsBundle:News:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'project_id'  => $project_id,
        ));
    }

    /**
    * Creates a form to edit a News entity.
    *
    * @param News $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(News $entity)
    {
        $form = $this->createForm(new NewsType(), $entity, array(
            'action' => $this->generateUrl('news_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing News entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('NewsBundle:News')->find($id);

        $project = $entity->getProjects();
        $user = $project->getUser();
        $userId = $user->getId();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        if (!isset($session['id']) || $session['id'] != $userId) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {

            $title = $this->formDatas($editForm, 'title');
            $entity->setTitle($title);

            $content = $this->formDatas($editForm, 'content');            
            $entity->setContent($content);
            $em->flush();

            $this->notice('success', 'News successfully edited');
            return $this->redirect($this->generateUrl('news_edit', array('id' => $id)));
        }

        

        return $this->render('NewsBundle:News:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a News entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);
    
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('NewsBundle:News')->find($id);

        $project = $entity->getProjects();
        $user = $project->getUser();

        if (!isset($session['id']) || $user->getId() != $session['id']) {
            return $this->redirect($this->generateUrl('_index'));
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find News entity.');
        }

        $em->remove($entity);
        $em->flush();
        
        $this->notice('success', 'News successfully deleted');
        return $this->redirect($this->generateUrl('news_show'));
    }

    /**
     * Creates a form to delete a News entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('news_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
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
