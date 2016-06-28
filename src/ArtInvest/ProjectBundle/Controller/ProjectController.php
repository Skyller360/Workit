<?php

namespace ArtInvest\ProjectBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
//
use ArtInvest\CommentBundle\Entity\Comment;
use ArtInvest\ProjectBundle\Entity\Project;
use ArtInvest\UserBundle\Entity\User;
use ArtInvest\NewsBundle\Entity\News;
use ArtInvest\ProjectBundle\Form\ProjectType;
use ArtInvest\CommentBundle\Form\CommentType;
use ArtInvest\UserBundle\Form\UserType;
use ArtInvest\MediaBundle\Entity\Media;
use ArtInvest\NewsBundle\Form\NewsType;


/**
 * Project controller.
 *
 * @Route("/project")
 */
class ProjectController extends Controller
{
     /**
     * Lists all Project entities.
     *
     * @Method("GET")
     * @Template()
     */
    public function adminshowOneAction($id)
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

        $entities = $em->getRepository('ProjectBundle:Project')->find($id);
        $deleteForm = $this->createDeleteForm($id); 
        
        $g = $entities->getGoodies();           
        $goodies = explode("|", $g); 
        
        $i=0;
        while(isset($goodies[$i])){
            $goodie[] = explode('#', $goodies[$i]);
            $i++;
        }

        //video link modification (fix the X-Frame-Options SAMEORIGIN problem) 
        $v = $entities->getVideo();
        $video = str_replace("watch?v=", "v/", $v);
        
        return array(
            'entity' => $entities,            
            'delete_form' => $deleteForm->createView(),
            'goodies' => $goodie,
            'video'   => $video,
        );
    }

    
     /**
     * Lists all Project entities.
     *
     * @Method("GET")
     * @Template("ProjectBundle:Project:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $connect = false;
        $id = 0;
        $session = new Session();
        $session = $this->get('session')->all();
        if (isset($session['id'])) {
            $connect = true;
            $id = $session['id'];
        }
        $users = [];

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ProjectBundle:Project')->findAll();

        $categories = $em->getRepository('CategoryBundle:Category')->findAll();
        $count = count($categories);

        foreach ($entities as $value) {
            $total = 0;
            $donations = $em->getRepository('DonationBundle:Donation')->findByProjects($value->getId());

            foreach ($donations as $donation) {
                $total += $donation->getAmount();
                $user = $donation->getUser();
                $users[] = $user->getId();
            }
            $donaters[$value->getId()] = $users;
            unset($users);
            $users = [];
            $max[$value->getId()] = $total;
            $pourcent[$value->getId()] = ($max[$value->getId()]*100)/$value->getAmount();
        }

        // Get dayLeft for each project
        foreach ($entities as $key) {           
            $today = (string)date('Y/m/d');
            $delay = $key->getDelay();
            $delay = get_object_vars($delay);
            $delay = $delay['date'];
            $dateDiff = $this->DayDiff($today, $delay);
            $projectId = $key->getId();
            $dayLeft[$projectId] =  $dateDiff;
        }

        if (isset($response)) {
            $entities = json_decode($response);
        }

        if($request->isXmlHttpRequest())
        {
            $array = [112,113,114,115,116,117,118,119,120,121];
            $data = $request->request->get('data');
            if (in_array($data, $array)) {
                
                $id = '';
                $response = [];
                $id = $request->request->get('data');
                $em = $this->container->get('doctrine')->getManager();
        
                    if($id != '')
                    {
    
                        $query = $em->createQuery(
                                'SELECT p.id FROM ProjectBundle:Project p
                                JOIN p.category c
                                WHERE p.category = :keyword'
                        )->setParameter('keyword', $id);               
    
                        $entities = $query->getResult();
                        $em = $this->getDoctrine()->getManager();
                        foreach ($entities as $entity) {
                           $project = $em->getRepository('ProjectBundle:Project')->find($entity['id']);
                           $response[] = $project;
                        }
    
                        $entities = $response;
                    }
            }

            if ($data == 'trending') {
                $i = rand(112,121);
                $query = $em->createQuery(
                                'SELECT p FROM ProjectBundle:Project p
                                 WHERE p.id = :i'
                        )->setParameter(':i', $i);               
                $entities = $query->getResult();
            }

            if ($data == 'fresh') {
                $query = $em->createQuery(
                                'SELECT p FROM ProjectBundle:Project p
                                 ORDER BY p.delay DESC'
                        );               
                $entities = $query->getResult();
            }

            if ($data == 'almost') {
                $almost = new \DateTime('now');
                $almost->sub(new \DateInterval('P4D'));
                $almost = $almost->format('Y-m-d');
                $query = $em->createQuery(
                                'SELECT p FROM ProjectBundle:Project p
                                 WHERE p.delay > :almost'
                        )->setParameter(':almost', $almost);               
                $entities = $query->getResult();
            }

            if ($data == 'finished') {
                $query = $em->createQuery(
                                'SELECT p FROM ProjectBundle:Project p
                                 WHERE p.delay < :now'
                        )->setParameter(':now', new \DateTime('now'));               
                $entities = $query->getResult();
            }

            if ($data == 'allproject') {
                $entities = $em->getRepository('ProjectBundle:Project')->findAll();
            }
            
        }

        return array(
            'entities' => $entities,
            'connect' => $connect,
            'userId' => $id,
            'logged' => $connect,
            'session' => $session,
            'count' => $count,
            'categories' => $categories,
            'donations' => $max,
            'donaters' => $donaters,
            'dayLeft' => $dayLeft,
            'pourcent' => $pourcent,
        );



    }

    /**
     * Lists all Project entities.
     *
     * @Method("GET")
     * @Template()
     */
    //Admin Action
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
        $entities = $em->getRepository('ProjectBundle:Project')->findAll();
        //get accepted and reported project
        $accepted = $em->getRepository('ProjectBundle:Project')->findBy(array('accepted' => NULL));      
        $reported = $em->getRepository('ProjectBundle:Project')->findBy(array('reported' => !NULL));  

        if (!$entities) {
            throw $this->createNotFoundException('Unable to find Project with this category.');
        }
        
        return array(
            'entities' => $entities,
            'accepted' => $accepted,
            'reported' => $reported

        );
    }

    /**
     * Lists all reported comments.
     *
     * @Method("GET")
     * @Template()
     */
    //Admin Action
    public function adminCommentsAction()
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
        $comments = $em->getRepository('CommentBundle:Comment')->findAll();
        //get accepted and reported project    
        $reported = $em->getRepository('CommentBundle:comment')->findBy(array('reported' => !NULL));  
        

        return array(
            'comments' => $comments,
            'reported' => $reported,
        );
    }
   
    /**
     * admin views : get single comment.
     *
     * @Method("GET")
     * @Template()
     */
    //Admin Action
    public function adminShowOneCommentsAction($id)
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
        $comment = $em->getRepository('CommentBundle:Comment')->find($id);

        //Get Project and user object
        return array(
            'comment' => $comment
        );
    }

    /**
     * Check a project
     *
     * @Method("GET")
     * @Template()
     */
    public function checkAction($id){ 
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        if (!$session['usergroup']) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ProjectBundle:Project')->find($id);    
        $old = $entity->getCover();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }
        else{            
            $sql = "UPDATE project SET accepted = 1 WHERE id =".$id;
            $em->getConnection()->exec($sql);
        }

        
        // FlashMessages   
        $this->notice('success', 'Project succesfully accepted.');

        return $this->render('ProjectBundle:Project:adminshowOne.html.twig', array(
            'id' => $id,
            'entity' => $entity
        ));
    }
    
    /**
    *
    * Display about page
    *
    ***/
    public function aboutAction(){        
        //Check log state
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
            ->add('save', 'submit', array('attr' => array('class' => 'form-control btn btn-primary')))            
            ->add('reset', 'reset', array('attr' => array('class' => 'form-control btn btn-info')))
           ->getForm();
        return $this->render('ProjectBundle:Project:about.html.twig', array('logged' => $logged, 'subscribe_form' => $subscribe_form->createView()));
    }

     /**
    *
    * Display CONTACT page
    *
    ***/
    public function contactAction(){        
        //Check log state
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
            ->add('save', 'submit', array('attr' => array('class' => 'form-control btn btn-primary')))            
            ->add('reset', 'reset', array('attr' => array('class' => 'form-control btn btn-info')))
           ->getForm();
        return $this->render('ProjectBundle:Project:contact.html.twig', array('logged' => $logged, 'subscribe_form' => $subscribe_form->createView()));
    }

    /**
    *ReportProjectAction
    *
    ***/
    public function reportAction($id)
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
        $entity = $em->getRepository('ProjectBundle:Project')->find($id);
        $entity->setReported(true);
        $em->flush();

        $this->notice('success', 'Project successfully reported');
        return $this->redirect($this->generateUrl('_project_showOne', array('id' => $entity->getId())));
    }

    /**
    *User delete projects
    *
    ***/
    public function userDeleteAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ProjectBundle:Project')->find($id);
        $entity->setDeleted(true);
        $em->flush();

        $this->notice('success', 'Project successfully deleted');
        return $this->redirect($this->generateUrl('_project_showOne', array('id' => $entity->getId())));
    }

    /**
     * Creates a new Project entity.
     *
     * @Method("POST")
     * @Template("ProjectBundle:Project:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        else{
            $connect = true;
        }
        $entity = new Project();
          
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {             

            $session = $this->get('session')->all();
            $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($session['id']);
            $entity->setUser($user);            

            $title = $this->formDatas($form, 'title');
            $entity->setTitle($title);

            $amount = $this->formDatas($form, 'amount');
            $entity->setAmount($amount);

            $abstract = $this->formDatas($form, 'abstract');
            $entity->setAbstract($abstract);
            
            //Get&Set Goodies
            $i = 1;
            $json ='';
            if(!empty($_POST["goodies_title$i"]))
            {
               
                var_dump($_POST["goodies_title$i"]);die;

                while(!empty($_POST["goodies_title$i"]))
                {
                    $i > 1 ? $json .= '|' : $json .='';

                    $json .=  $i.'#';
                    $json .=  $_POST["goodies_title$i"].'#';
                    $json .=  $_POST["goodies_amount$i"].'#';
                    $json .=  $_POST["goodies_desc$i"];                   
                    $i++;
                }        
                

                $entity->setGoodies($json);
            }
            $em = $this->getDoctrine()->getManager();
            $mediaEm = $this->getDoctrine()->getManager();
            $cover = $_POST['link'];
            $entity->setCover($cover);

            $em->persist($entity);
            $em->flush();

            for ($i=0; $i < 5; $i++) { 
                if (!empty($_POST['link'.$i])){
                    $media = new Media();
                    $media->setProjects($entity);    
                    $image = $_POST['link'.$i];
                    $media->setImage($image);
                    $mediaEm->persist($media);
                }
            }
            $mediaEm->flush();

            // FlashMessages
            $this->notice('success', 'Project succesfully created');
            return $this->redirect($this->generateUrl('_project_showOne', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'logged' => $connect,
        );
    }

    /**
     * Creates a form to create a Project entity.
     *
     * @param Project $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Project $entity)
    {
        $form = $this->createForm(new ProjectType(), $entity, array(
            'action' => $this->generateUrl('_project_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Project entity.
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Project();
        $form   = $this->createCreateForm($entity);
        $session = new Session();
        $session = $this->get('session')->all();
        (isset($session['id']))? $logged = true : $logged = false;
        $user = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->find($session['id']);
        // Create subscribe form
        $User = new User();
        $subscribe_form = $this->createFormBuilder($User)
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
            ->add('save', 'submit', array('attr' => array('class' => 'form-control btn btn-primary')))            
            ->add('reset', 'reset', array('attr' => array('class' => 'form-control btn btn-info')))
           ->getForm();



        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'logged' => $logged,
            'subscribe_form' => $subscribe_form->createView(),
            'session' => $session,
            'user' => $user,
        );
    }

    /**
     * Finds and displays a Project entity.
     *
     * @Method("GET")
     * @Template()
     */
     public function showAction($id)
    {
        $session = new Session();
        $session = $this->get('session')->all();
        $simple = false;
        $creator = false;
        $logged; 
        $total = 0;
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ProjectBundle:Project')->find($id);
        if (!$entity) { throw $this->createNotFoundException('Unable to find Project entity.'); }  
        $user = $entity->getUser(); 
        (isset($session['id']))? $logged = true : $logged = false;          

        $thisUser = false; 
        //video link modification (fix the X-Frame-Options SAMEORIGIN problem) 
        $v = $entity->getVideo();
        $video = str_replace("watch?v=", "v/", $v);

        if (!isset($session['id'])) {
            $simple = true;

        }        
        else{
            $thisUser = $session['login'];
            if ($session['id'] != $user->getId()) {$creator = true;}
        }

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
            ->add('save', 'submit', array('attr' => array('class' => 'form-control btn btn-primary')))            
            ->add('reset', 'reset', array('attr' => array('class' => 'form-control btn btn-info')))
           ->getForm();        
      
                         
        // retrieves news by projects
        $news = $em->getRepository('NewsBundle:News')->findByProjects($id);      
        // retrieves comments by projects  
        $comments = $em->getRepository('CommentBundle:Comment')->findByProject($id);

        // Render createComment Form
        $comment_form = new Comment();
        $comments_form   = $this->createCommentsForm($comment_form);

        // Render createNews Forms
        $new_form = new News();
        $news_form   = $this->createNewsForm($new_form);

        // Get dayLeft for this project         
        $today = (string)date('Y/m/d');
        $delay = $entity->getDelay();
        $delay = get_object_vars($delay);
        $delay = $delay['date'];
        $dateDiff = $this->DayDiff($today, $delay);
        $projectId = $entity->getId();
        $dayLeft[$projectId] =  $dateDiff;
        

        //Get medias for this project
        $medias = $em->getRepository('MediaBundle:Media')->findByProjects($entity->getId());
        
        // Get donations total, donaters numbers, & percent
        $users = [];        
        $donaterss = [];
        $total = 0;
        $donations = $em->getRepository('DonationBundle:Donation')->findByProjects($entity->getId());

        foreach ($donations as $donation) {
            $total += $donation->getAmount();
            $user = $donation->getUser();
            $hidden = $donation->getHidden();
            $users[] = $user->getId();
            $donaterss[] = ['users' => $user, 'amount' => $donation->getAmount(), 'hidden' => $hidden];
        }
        $donaters[$entity->getId()] = $users;
        $max[$entity->getId()] = $total;
        $pourcent[$entity->getId()] = ($max[$entity->getId()]*100)/$entity->getAmount();
        
        // Get goodies of this project 

        $g = $entity->getGoodies();           
        $goodies = explode("|", $g); 
        
        $i=0;
        while(isset($goodies[$i])){
            $goodie[] = explode('#', $goodies[$i]);
            $i++;
        }         
        

        $deleteForm = $this->createDeleteForm($id);
        $session = new Session();
        $session->set('project_id', $entity->getId());

        return $this->render('ProjectBundle:Project:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'simple' => $simple,
            'creator' => $creator,
            'projectId' => $entity->getId(),
            'total' => $total,            
            'logged' => $logged,
            'session' => $session,
            'dayLeft' => $dayLeft,
            'donations' => $max,
            'donaters' => $donaters,
            'donaterss' => $donaterss,
            'pourcent' => $pourcent,
            'video' => $video,
            'news' => $news,
            'comments' => $comments,
            'comments_form' => $comments_form->createView(),
            'goodies' => $goodie, 
            'subscribe_form' => $subscribe_form->createView(),
            'medias' => $medias,
            'news_form' => $news_form->createView(),
            'thisUser' => $thisUser,
        ));
    }


    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Method("GET")
     * @Template()
     */
   public function editAction($id)
    {
        (isset($session['id']))? $logged = true : $logged = false;   
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ProjectBundle:Project')->find($id);
        $user = $entity->getUser();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        if ($session['id'] != $user->getId()) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $cover = $em->getRepository('MediaBundle:Media')->findByProjects($entity->getId());

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
            'logged' => $logged,
            'cover' => $cover,
        );
    }

    /**
    * Creates a form to edit a Project entity.
    *
    * @param Project $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Project $entity)
    {
        $form = $this->createForm(new ProjectType(), $entity, array(
            'action' => $this->generateUrl('_project_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    
    /**
     * Edits an existing Project entity.
     *
     * @Method("PUT")
     * @Template("ProjectBundle:Project:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id'])) {
            return $this->redirect($this->generateUrl('_index'));
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ProjectBundle:Project')->find($id);
        $user = $entity->getUser();

        if ($session['id'] != $user->getId()) {
            return $this->redirect($this->generateUrl('_index'));
        }        

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }


        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);


        if ($editForm->isValid()) {

            // FlashMessages            
            $session = new Session;
            $session->getFlashBag()->add('notice', 'Project succesfully updated.');

            $em->flush();

            // FlashMessages            
            $this->notice('success', 'Project succesfully updated.');
            return $this->redirect($this->generateUrl('_project_showOne', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    
    /**
     * Deletes a Project entity.
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
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

       
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ProjectBundle:Project')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Project entity.');
        }

        $em->remove($entity);

        // FlashMessages
        $this->notice('success', 'Project succesfully deleted.');

        $em->flush();

        $usergroup = $session->get('usergroup');
        
        if(!isset($usergroup))
        {
            return $this->redirect($this->generateUrl('_project_show'));
        }
        else
        {
            return $this->redirect($this->generateUrl('_admin_project'));
        }
}


    /**
     * Creates a form to delete a Project entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('_project_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-danger')))
            ->getForm()
        ;
    }   

    // -----------------------------------------------------------------

    /**
     * Creates a form to create a Comment entity.
     *
     * @param Comment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCommentsForm(Comment $entity)
    {
        $form = $this->createForm(new CommentType(), $entity, array(
            'action' => $this->generateUrl('comment_create'),
            'method' => 'POST',
        ));

        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

     /**
     * Creates a form to create a News entity.
     *
     * @param News $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createNewsForm(News $entity)
    {
        $form = $this->createForm(new NewsType(), $entity, array(
            'action' => $this->generateUrl('news_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Submit your news'));

        return $form;
    }

    public function textCreateAction()
    {
        $session = new Session();
        $session = $this->get('session')->all();

        if (!isset($session['id'])) {
            $this->notice('error', 'You must be connected to create a project !');  
            return $this->redirect($this->generateUrl('_index'));
        }
        else{
            $connect = true;
        }

        return $this->render('ProjectBundle:Project:textCreate.html.twig', array('session' => $session, 'logged' => $connect));
    }

    // -----------------------------------------------------------------

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

    //DayDiff function
    private function DayDiff($start, $end) {
      $start = strtotime($start);
      $end = strtotime($end);
      $difference = $end - $start;
      return round($difference / 86400);       
    }

    private function notice($type, $msg){
        $session = new Session;
        $session->getFlashBag()->add($type, $msg);
    }
}
