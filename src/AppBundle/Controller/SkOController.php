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
        ->add('registration', SubmitType::class, array('label' => 'Inscription'))
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $password = $form['password']->getData();
            $email = $form['email']->getData();
            $encoded_pass = sha1($form['password']->getData());

            $player = $this->getDoctrine()
                         ->getRepository('AppBundle:Player')
                         ->findByEmail($email);
            $pass_check = $this->getDoctrine()
                         ->getRepository('AppBundle:Player')
                         ->findByPassword($encoded_pass);
            console.log($player);
            if(!$player)
            {
                throw $this->createNotFoundException('Le mot de passe ou l\'email est faux'.$player);
            }
            else
            {

                return $this->redirectToRoute('login',  array('player' => $player));
            }

           

            $em = $this->getDoctrine()->getManager();
            $em->persist($player); // prépare l'insertion dans la BD
            $em->flush(); // insère dans la BD
        }


        return $this->render('Sko/menu.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/registration", name="registration_page")
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
            $player->setPseudo($pseudo);
            $player->setEmail($email);
            $player->setPassword($encoded_pass);

            $em = $this->getDoctrine()->getManager();
            $em->persist($player); // prépare l'insertion dans la BD
            $em->flush(); // insère dans la BD
        }



        return $this->render('Sko/registration.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        return $this->render('Sko/login.html.twig', array(

            ));
    }
}
