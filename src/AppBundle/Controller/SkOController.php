<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use AppBundle\Entity\Map;
use AppBundle\Entity\Characters;
use AppBundle\Entity\building;
use AppBundle\Entity\units;
use AppBundle\Entity\Ressources;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

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
            return $this->render('Sko/accueil.html.twig', array('pseudo' => $pseudo, 'persos' => $player->getCharacters()));
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
        $pseudo = $request->query->get('pseudo');
        $player = $this->getDoctrine()
            ->getRepository('AppBundle:Player')
            ->findOneByPseudo($pseudo);
        $persos = $player->getCharacters();
        return $this->render('Sko/accueil.html.twig', array('pseudo' => $pseudo, 'persos' => $persos));
    }


    /**
     * @Route("/carte", name="carte")
     */
    public function carteAction(Request $request)
    {
        $map = $this->getDoctrine()->getRepository('AppBundle:Map')->findAll();

        dump($request->getUser());
        return $this->render('Sko/carte.html.twig', array(
            'map' => $map
            ));
    }



    /**
     * @Route("/construction", name="construction")
     * @Security("has_role('ROLE_USER')")
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
     * @Route("/createPerso", name="createPerso")
     */
    public function createPersoAction(Request $request)
    {
        $character = new Characters;
        $form = $this->createFormBuilder($character)
        ->add('pseudo', TextType::class, array('label' => 'Nom du personnage :'))
        ->add('image', FileType::class, array('label' => 'Image : '))
        ->add('creation', SubmitType::class, array('label' => 'Création'))
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $pseudo = $request->query->get('pseudo');
            $player = $this->getDoctrine()
                ->getRepository('AppBundle:Player')
                ->findOneByPseudo($pseudo);
            $idPlayer = $player->getId();
            $name = $form['pseudo']->getData();
            $image = $form['image']->getData();
            $village = "Village 1";

            $file = $character->getImage();
           $fileName = uniqid().'.'.$file->guessExtension();
           $file->move(
               $this->getParameter('image_directory'),
               $fileName
           );
           $character->setPseudo($name);
           $character->setPlayer($player);
           $character->setImage($fileName);
           $em = $this->getDoctrine()->getManager();
           $em->persist($character); // prépare l'insertion dans la BD
           $em->flush(); // insère dans la BD

           // Création des éléments de base du joueur
           $building = new building;
           $units = new units;
           $ressources = new ressources;

            $building->setPseudo($village);
            $building->setPerso($character);
            $em = $this->getDoctrine()->getManager();
            $em->persist($building); // prépare l'insertion dans la BD
            $em->flush();

            $units->setPerso($character);
            $em = $this->getDoctrine()->getManager();
            $em->persist($units); // prépare l'insertion dans la BD
            $em->flush();

            $ressources->setPerso($character);
            $em = $this->getDoctrine()->getManager();
            $em->persist($ressources); // prépare l'insertion dans la BD
            $em->flush();

            return $this->render('Sko/accueil.html.twig', array('pseudo' => $pseudo, 'persos' => $player->getCharacters()));
        }
        return $this->render('Sko/createPerso.html.twig', array('form' => $form->createView()));
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function uniteLogout(Request $request)
    {
        return $this->render('Sko/menu.html.twig', array(

            ));
    }
    /**
     * @Route("/ressource_new", name="ressource_new")
     */
    public function newRessource(Request $request){
        if ($request->isXMLHttpRequest()) {
            $content = $request->getContent();
            if (!empty($content)) {
                $params = json_decode($content, true);
                $ressource = new Ressources;
                $ressource->setOs($params['ressource'].getOs());
                $ressource->setPierre($params['ressource'].getPierre());
                $ressource->setMetal($params['ressource'].getMetal());

                $em = $this->getDoctrine()->getManager();
                $perso = $em->getRepository('AppBundle:Characters')
                    ->findOneBy(['pseudo' => $params['pseudo']]);

                $perso->setRessources($ressource);
                $em->persist($perso);
                $em->flush();
            }
            return new JsonResponse(array('data' => $params));
        }
        return new Response('Error!', 400);
    }
}
