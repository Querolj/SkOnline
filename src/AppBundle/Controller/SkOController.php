<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class SkOController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $player = new Player;

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
                return $this->redirectToRoute('accueil',  array('pseudo' => $pseudo));
            }
        }

        return $this->render('Sko/menu.html.twig', array('form' => $form->createView()));
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
            $encoded_pass = sha1($form['password']->getData());
            $date = date_create();
            $player->setPseudo($pseudo);
            $player->setEmail($email);
            $player->setPassword($encoded_pass);
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
        $em = $this->getDoctrine()->getEntityManager();

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

}
