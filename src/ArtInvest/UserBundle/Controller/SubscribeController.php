<?php

namespace ArtInvest\UserBundle\Controller;

use ArtInvest\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class SubscribeController extends Controller
{
	public function subscribeAction(Request $request)
	{
		$session = new Session();
        $session = $this->get('session')->all();
        $logged;    
        (isset($session['id']))? $logged = true : $logged = false;

        if ($logged) {
        	if (!$session['usergroup']) {
            return $this->redirect($this->generateUrl('_index'));
        	}
        }
        
        
		$user = new User();

        $form = $this->createFormBuilder($user)
            ->add('login', 'text')
            ->add('mail', 'email')            
            ->add('hash', 'repeated', array(
    				'type' => 'password',
    				'invalid_message' => 'Password doesn\'t match',
    				'options' => array('required' => true),
    				'first_options'  => array('label' => 'Password'),
    				'second_options' => array('label' => 'Confirm Password')))
            ->add('birthday', 'birthday', array(
				    'widget' => 'single_text',
				    'label' => 'Birthday (yyyy-mm-dd)'
				))
			->add('save', 'submit', array('attr' => array('class' => 'form-control btn orange-btn')))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isValid()) {

        	$login = $form['login']->getData();
			$securedLogin = htmlentities(addslashes($login));

			$pass = $form['hash']->getData();
			$salt = '$45$54$datstring';
			$securedHash = crypt($pass, $salt);		
	
			$mail = $form['mail']->getData();
			$securedMail = htmlentities(addslashes($mail));	

			$user->setLogin($securedLogin);
			$user->setHash($securedHash);
			$user->setMail($securedMail);

			$birthday = $form['birthday']->getData();
			$user->setBirthday($birthday);
	
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
			
        	//return $this->render('UserBundle:Index:index.html.twig');
        	$this->notice('success', 'Successfully Registered !');
        	return $this->redirect($this->generateUrl('_index'));
        }
        return $this->render('UserBundle:Subscribe:subscribe.html.twig', array(
            'form' => $form->createView(),
        ));	


	}

	public function subscribedAction()
	{
		return $this->render('UserBundle:Subscribe:subscribed.html.twig');
	}
	
	private function notice($type, $msg){
        $session = new Session;
        $session->getFlashBag()->add($type, $msg);
    }
}
