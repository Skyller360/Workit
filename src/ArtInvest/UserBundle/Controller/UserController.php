<?php

namespace ArtInvest\UserBundle\Controller;

use ArtInvest\MediaBundle\Entity\Media;
use ArtInvest\UserBundle\Entity\User;
use ArtInvest\UserBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;




/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
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
        $entities = $em->getRepository('UserBundle:User')->findBy(array('accepted' => true, 'deleted' => false));


        return array(
            'entities' => $entities
        );
    }
    /**
     * Lists all User entities.
     *
     * @Method("GET")
     * @Template()
     */
    public function adminIndexAction()
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
        $entities = $em->getRepository('UserBundle:User')->findAll();

        //Get stats count
        $p_accepted = $em->getRepository('ProjectBundle:Project')->findBy(array('accepted' => NULL));
        $p_reported = $em->getRepository('ProjectBundle:Project')->findBy(array('reported' => !NULL));
        $faq = $em->getRepository('FaqBundle:Faq')->findBy(array('answer' => NULL));

        //get reported comments
        $c_reported = $em->getRepository('CommentBundle:Comment')->findBy(array('reported' => !NULL));


        return array(
            'entities' => $entities,
            'p_accepted' => $p_accepted,
            'p_reported' => $p_reported,
            'c_reported' => $c_reported,
            'faq'        => $faq
        );
    }
    /**
     * Creates a new User entity.
     *
     * @Method("POST")
     * @Template("UserBundle:User:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $bio = $this->formDatas($form, 'bio');
            $entity->setBio($bio);

            $face = $this->formDatas($form, 'facebook');
            $entity->setFacebook($face);

            $location = $this->formDatas($form, 'location');
            $entity->setLocation($location);

            $web = $this->formDatas($form, 'website');
            $entity->setWebsite($web);

            $em->persist($entity);
            $em->flush();

            // FlashMessages
            $session = new Session;
            $session->getFlashBag()->add('notice', 'User succesfully created.');


            return $this->redirect($this->generateUrl('user_show', array('id' => $entity->getId())));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        $connect = false;
        if (isset($session['id'])) {
            if ($session['id'] == $id) {
                $connect = true;
            }
        }else{

            $this->notice('error', "You need to be logged to see user's profiles");
            return $this->redirect($this->generateUrl('_index'));
        }
        (isset($session['id']))? $logged = true : $logged = false;
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (isset($session['project_id'])) {
            $project = $em->getRepository('ProjectBundle:Project')->find($session['project_id']);
        }
        else{
            $project = 'fakit';
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'edit_form' => $editForm->createView(),
            'connect' => $connect,
            'session' => $session,
            'logged' => $logged,
            'project' => $project,
        );
    }

    /**
     * Finds and displays a User entity.
     *
     * @Method("GET")
     * @Template()
     */
    public function adminShowAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        $connect = false;
        if ($session['id'] == $id) {
            $connect = true;
        }
        if (!$session['usergroup']) {
            return $this->redirect($this->generateUrl('_index'));
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'connect' => $connect,
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        if (!$session['usergroup'] && $session['id'] != $id) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        $password_form = $this->createPasswordForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'password_form' => $password_form->createView(),
        );
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {

        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('submit', 'submit', array('label' => 'Save settings', 'attr' => array('class' => 'orange-btn col-xs-12 col-sm-6 col-md-6')));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     * @Method("PUT")
     * @Template("UserBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        if (!$session['usergroup'] && $session['id'] != $id) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $passForm = $this->createPasswordForm($id);
        $editForm->handleRequest($request);

        if ('PUT' === $request->getMethod()) {


            if ($request->request->has('artinvest_userbundle_user')) {

                $link = $_POST['link'];
                $entity->setAvatar($link);

                $bio = $this->formDatas($editForm, 'bio');
                $entity->setBio($bio);

                $face = $this->formDatas($editForm, 'facebook');
                $entity->setFacebook($face);
                $location = $this->formDatas($editForm, 'location');
                $entity->setLocation($location);

                $web = $this->formDatas($editForm, 'website');
                $entity->setWebsite($web);
                $em->flush();
                return $this->redirect($this->generateUrl('user_show', array('id' => $id)));
            }

               if ($request->request->has('form')) {

                    $data = $request->get('form');
                    if ($data['hash']['first'] == $data['hash']['second']) {
                        $pass = $data['hash']['first'];
                        $salt = '$45$54$datstring';
                        $securedHash = crypt($pass, $salt);
                        $entity->setHash($securedHash);

                        $em->flush();

                        // FlashMessages
                        $session = new Session;
                        $session->getFlashBag()->add('notice', 'User information succesfully updated.');
                        $this->notice('success', "User information succesfully updated");
                        return $this->redirect($this->generateUrl('user_show', array('id' => $id)));
                    }
                    else{
                        return new Response('Mot de passe non identique!');
                    }
            }


        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'password_form' => $passForm->createView(),
        );
    }
}


    /**
     * Deletes a User entity.
     *
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        if (!$session['usergroup'] && $session['id'] != $id) {
            return $this->redirect($this->generateUrl('_index'));
        }
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);

            // FlashMessages
            $session = new Session;
            $session->getFlashBag()->add('notice', 'User succesfully deleted.');

            $em->flush();
            return $this->redirect($this->generateUrl('_index'));
        }
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete account', 'attr' => array('class' => 'orange-btn col-xs-12 col-sm-6 col-md-6')))
            ->getForm()
        ;
    }

   private function createPasswordForm($id)
    {
        return $this->createFormBuilder(null, array(
            'action' => $this->generateUrl('user_update', array('id' => $id)),
            'data_class' => 'ArtInvest\UserBundle\Entity\User',
            'method' => 'PUT',
        ))
            ->add('hash', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'Password doesn\'t match',
                    'options' => array('required' => true),
                    'first_options'  => array('label' => 'Password'),
                    'second_options' => array('label' => 'Confirm Password')))
            ->add('submit', 'submit', array('label' => 'Change Password'))
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
