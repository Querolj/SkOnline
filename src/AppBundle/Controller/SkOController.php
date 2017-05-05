<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use AppBundle\Entity\Map;
use AppBundle\Entity\Characters;
use AppBundle\Entity\Message;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SkOController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $player = new Player;
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'))
        {

        }
        else
        {
            $form = $this->createFormBuilder($player)
            ->add('email', TextType::class, array('label' => 'Email :'))
            ->add('password', PasswordType::class, array('label' => 'Mot de passe :'))
            ->add('login', SubmitType::class, array('label' => 'Login'))
            ->getForm();

            $form->handleRequest($request);



            if($form->isSubmitted() && $form->isValid())
            {
                $password = $form['password']->getData();
                $email = $form['email']->getData();
                $encoded_pass = sha1($form['password']->getData());
                $date = date_create();
            /*
                Recherche dans la base de données si les différents éléments entrés
                sont présents afin de connecter la personne
            */
                $player = $this->getDoctrine()
                ->getRepository('AppBundle:Player')
                ->findOneByEmail($email);
                $pass_check = $this->getDoctrine()
                ->getRepository('AppBundle:Player')
                ->findByPassword($encoded_pass);

                if(!$player)
                {
                    return $this->redirectToRoute('registration');
                }
                else
                {
                    $pseudo = $this->getDoctrine()
                    ->getRepository('AppBundle:Player')
                    ->findOneByEmail($email)->getPseudo();
                    /* Met à jour la date de connection */
                    $player->setDateLog($date);
                    /* Entre les différents élements dans la base */
                    $em = $this->getDoctrine()->getManager();
                $em->persist($player); // prépare l'insertion dans la BD
                $em->flush(); // insère dans la BD


                $token = new UsernamePasswordToken($player, $player->getPassword(), "main", $player->getRoles());

                $event = new InteractiveLoginEvent(new Request(), $token);

                $this->container->get("event_dispatcher")->dispatch("security.interactive_login", $event);

                $this->container->get("security.token_storage")->setToken($token);

                return $this->redirectToRoute('accueil',  array('pseudo' => $pseudo));
            }
        }
        return $this->render('Sko/menu.html.twig', array('form' => $form->createView()));
    }


    return $this->render('Sko/menu.html.twig');
        // En plus renvoyer la balise
}

    /**
     * @Route("/registration", name="registration")
     */
    public function registrationAction(Request $request)
    {

        $player = new Player;

        $form = $this->createFormBuilder($player)
        ->add('pseudo', TextType::class, array('label' => 'Pseudo :'))
        ->add('email', TextType::class, array('label' => 'Email :'))
        ->add('password', PasswordType::class, array('label' => 'Mot de passe :'))
        ->add('registration', SubmitType::class, array('label' => 'Inscription'))
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $pseudo = $form['pseudo']->getData();
            $email = $form['email']->getData();
            $player->setSalt(uniqid(mt_rand()));

            $encoder = $this->container->get('sha256salted_encoder')
            ->getEncoder($player);
            $password = $encoder->encodePassword($form['password']->getData(), $player->getSalt());

            //$encoded_pass = sha1($form['password']->getData());

            $date = date_create();
            $player->setPseudo($pseudo);
            $player->setEmail($email);
            $player->setPassword($password);
            $player->setDateLog($date);

            $em = $this->getDoctrine()->getManager();
            $em->persist($player); // prépare l'insertion dans la BD
            $em->flush(); // insère dans la BD
            return $this->render('Sko/accueil.html.twig', array('pseudo' => $pseudo));
        }
        return $this->render('Sko/registration.html.twig', array('form' => $form->createView()));
    }


    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('Sko/login.html.twig', array());
    }

    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueilAction(Request $request)
    {
        $player = $request->query->get('pseudo');
        return $this->render('Sko/accueil.html.twig', array('pseudo' => $player));
    }


    /**
     * @Route("/carte", name="carte")
     */
    public function carteAction(Request $request)
    {


        $player = $this->container->get("security.token_storage")->getToken()->getUser()->getUsername();
        $map = $this->getDoctrine()->getRepository('AppBundle:Map')->findAll();

        $messagerie = new Message();
        $form = $this->createFormBuilder($messagerie)
            ->add('message', TextareaType::class, array('label' => 'Message :'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {



        }
        /*
             $.ajax({
            type: "GET",
            url: "/carte?action=droite&region={{ region }}",
            data: {request : "droite"},
            success: function(data, dataType)
            {
                alert("success");
            },

            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                alert('Error : ' + errorThrown);
            }

        });
        */        
        if ($request->isMethod('GET') && $request->request->has('action')) {
            dump('AH');
            $action = $request->query->get('action');
            $region = $request->query->get('region');
            dump($action);
            dump($region);
            if($action == 'droite')
                return $this->render('Sko/carte.html.twig', array(
                    'map' => $map,
                    'form' => $form->createView(),
                    'region' => $region + 1
                    ));
        }
        
        
        return $this->render('Sko/carte.html.twig', array(
            'map' => $map,
            'form' => $form->createView(),
            'region' => 3
            ));
    }


    /**
     * @Route("/messagerie", name="messagerie")
     */
    public function messagerieAction(Request $request)
    {
        $messagerie = new Message();
        $form = $this->createFormBuilder($messagerie)
            ->add('message', TextareaType::class, array('label' => 'Message :'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {


        }


        return $this->render('Sko/messagerie.html.twig', array(
                'form' => $form->createView()
            ));
    }

    /**
     * @Route("/construction", name="construction")
     */
    public function constructionAction(Request $request)
    {
        return $this->render('Sko/construction.html.twig', array(

            ));
    }

    /**
     * @Route("/unite", name="unite")
     */
    public function uniteAction(Request $request)
    {
        return $this->render('Sko/unite.html.twig', array(

            ));
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function uniteLogout(Request $request)
    {
        return $this->render('Sko/menu.html.twig', array(

            ));
    }
}
