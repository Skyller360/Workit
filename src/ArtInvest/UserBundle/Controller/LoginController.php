<?php
namespace ArtInvest\UserBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

//
use ArtInvest\MediaBundle\Entity\Media;
use ArtInvest\UserBundle\Entity\User;
use ArtInvest\ProjectBundle\Entity\Project;

use Doctrine\ORM\Query;

class LoginController extends Controller
{
    /**
    * Détermine si l'utilisateur est connecté,
    *
    **/
    public function IndexAction(){
        $session = new Session();
        $session = $this->get('session')->all();
        $logged;
        $em = $this->getDoctrine()->getManager();

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
           ->getForm();

        // Get FreshProjects (last four projects)
        $freshprojects = $em->createQuery('SELECT p FROM ProjectBundle:Project p ORDER BY p.id DESC')
                                                 ->setMaxResults(4)
                                                 ->getResult();

        // Get dayLeft for each project
        foreach ($freshprojects as $key) {
            $today = (string)date('Y/m/d');
            $delay = $key->getDelay();
            $delay = get_object_vars($delay);
            $delay = $delay['date'];
            $dateDiff = $this->DayDiff($today, $delay);
            $projectId = $key->getId();
            $dayLeft[$projectId] =  $dateDiff;
        }

        // Get donations total
        $users = [];
        foreach ($freshprojects as $value) {
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


        // Get FreshCategory(NOTRandomYET)
        $categories = $em->createQuery('SELECT c FROM CategoryBundle:Category c ORDER BY c.id DESC')
                                                 ->setMaxResults(4)
                                                 ->getResult();

        // Get an image for each category
        $category_pics = array(
            "Art" => "http://i.imgur.com/OFo5882.jpg",
            "Design" => "http://i.imgur.com/G1Ikkpd.jpg",
            "Edition" => "http://i.imgur.com/DiPtVmy.jpg",
            "Fashion" => "http://i.imgur.com/83OVNSH.jpg",
            "Games" => "http://i.imgur.com/PUbA2eK.jpg",
            "Music" => "http://i.imgur.com/FDuFshr.jpg",
            "Photography" => "http://i.imgur.com/0lOL0p8.jpg",
            "Theater" => "http://i.imgur.com/ox2jxwc.jpg",
            "Video" => "http://i.imgur.com/8JYeGY0.jpg",
            "Web" => "http://i.imgur.com/LvrBQSg.jpg",
        );

        // Get Last4Finishedprojects
        $finishedprojects = $em->getRepository('ProjectBundle:Project')->findAll();

        foreach ($finishedprojects as $value) {
            $total = 0;
            $donations = $em->getRepository('DonationBundle:Donation')->findByProjects($value->getId());

            foreach ($donations as $donation) {
                $total += $donation->getAmount();
            }
            if ($total >= $value->getAmount() ) {
                $isFinished[$value->getId()] = $value;
            }
        }

        // Check if user's logged
        (isset($session['id']))? $logged = true : $logged = false;

        return $this->render('UserBundle:Index:index.html.twig', array(
                'logged' => $logged,
                'session' => $session,
                'freshprojects' => $freshprojects,
                'categories' => $categories,
                'dayLeft' => $dayLeft,
                'donations' => $max,
                'donaters' => $donaters,
                'pourcent' => $pourcent,
                'isFinished' => $isFinished,
                'subscribe_form' => $subscribe_form->createView(),
                'category_pics' => $category_pics,
        ));
    }

    //Redirect to the login page
    public function LoginAction()
    {
        $session = new Session();
        $session = $this->get('session')->all();
        $logged;

        (isset($session['id']))? $logged = true : $logged = false;
        if($logged){
             throw new \Exception('You are already logged !');
        }else{
            return $this->render('UserBundle:Login:Login.html.twig');
        }

    }
    //invalidate session variable & redirect to the index
    public function LogoutAction(){
        $session = new Session();
        $session->invalidate();
        $this->notice('success', 'You are now disconnected');

        return $this->redirectToRoute('_index');
    }
    //Check Login
    public function Login_checkAction(Request $request)
    {
        $login = $request->request->get('login');
        $pass = $request->request->get('password');
        $repository = $this->getDoctrine()->getRepository('UserBundle:User');
        $user = $repository->findOneBylogin($login);
        if (!$user)
        {
            throw new \Exception('This login does not exist !');
        }
        else
        {
            $userHash = $user->getHash();
            $userLogin = $user->getLogin();
            $password = crypt($pass, $userHash);
            if($login === $userLogin && $userHash === $password)
            {
                $session = new Session();
                $session->set('id', $user->getId());
                $session->set('login', $user->getLogin());
                $session->set('usergroup', $user->getUsergroup());
                $this->notice('success', 'You are now connected');
                return $this->redirectToRoute('_index');
            }else{
                throw new \Exception('Wrong login and/or wrong password');
            }
        }

    }
    //Var_Dump
    public function dumpAction(){
        $session = new Session;
        $session = $this->get('session')->all();
        echo '<pre>';
        var_dump($session);
        echo '</pre>';
        return false;
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
