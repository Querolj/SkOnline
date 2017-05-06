<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use AppBundle\Entity\Map;
use AppBundle\Entity\Characters;
use AppBundle\Entity\Message;
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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

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
                Recherche dans la base de donnée    s si les différents éléments entrés
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


        $player = $this->container->get("security.token_storage")->getToken()->getUser();
        $message = new Message;
        //dump($player->getCharacters());
        

        $form = $this->createFormBuilder($message)
            ->add('message', TextareaType::class, array('label' => ' ', 
                'attr' => array(
                    'style' => 'width: 500px; height: 200px', 
                    'placeholder' => 'Ecrivez votre message...' )))
            ->add('envoie', SubmitType::class, array('label' => 'Envoie du message', 
                'attr' => array(
                    'id' => 'envoie_message'
                    )))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $to = $this->getDoctrine()->getRepository('AppBundle:Player')
                            ->findOneByPseudo($request->query->get('to'));
            $map = $this->getDoctrine()->getRepository('AppBundle:Map')->findByRegion(10);
                return $this->render('Sko/carte.html.twig', array(
                    'form' => $form->createView(),
                    'map' => $map,
                    'text' => $form['message']->getData(),
                    'sender' => $player->getUsername(),
                    "mess_type" => "message", 
                    "to" => $to->getPseudo()
                    ));

            if($request->query->has('to'))
            {
                /*dump("sending...");
                $message->setMessage($form['message']->getData());
                $message->setSender($player->getUsername());
                $message->setVu(false);
                $message->setDateSend(date_create());
                $message->setMessType("message");
                $to = $this->getDoctrine()->getRepository('AppBundle:Player')
                            ->findOneByPseudo($request->query->get('to'));
                $id_to = $to->getId();
                dump($id_to);
                dump($to);
                //$message->setIdPlayer($id_to);
                $message->setPlayer($to);
                
                //TODO : get to depuis la database et lui foutre le message
                $em = $this->getDoctrine()->getManager();
                $em->persist($message); // prépare l'insertion dans la BD
                $em->flush();
                window.location = '/carte?to=' + player_envoie;*/
            }
            


        }
        
        if ($request->isMethod('GET') && $request->query->has('region')) {
            $action = $request->query->get('action');
            $region = $request->query->get('region');

            if($action == 'droite' )
            {
                $map = $this->getDoctrine()->getRepository('AppBundle:Map')->findByRegion($region);
                return $this->render('Sko/carte.html.twig', array(
                    'map' => $map,
                    'form' => $form->createView(),
                    'region' => $region
                    ));
            }
            else if($action == 'gauche')
            {
                $map = $this->getDoctrine()->getRepository('AppBundle:Map')->findByRegion($region);
                return $this->render('Sko/carte.html.twig', array(
                    'map' => $map,
                    'form' => $form->createView(),
                    'region' => $region
                    ));

            }
        }
        else
        {
            $map = $this->getDoctrine()->getRepository('AppBundle:Map')->findByRegion(10);
            return $this->render('Sko/carte.html.twig', array(
                    'map' => $map,
                    'form' => $form->createView(),
                    'region' => 10
                    ));
        }
        
        
        return $this->render('Sko/carte.html.twig', array(
            'map' => $map,
            'form' => $form->createView(),
            'region' => 3
            ));
    }

    /**
     * @Route("/send", name="send")
     */
    public function send(Request $request)
    {
        $message = new Message();
        $mess_type = $request->request->get('mess_type');
        $text = $request->request->get('text');
        $sender = $request->request->get('sender');
        $to = $request->request->get('to');
        $player = $this->getDoctrine()
                ->getRepository('AppBundle:Player')
                ->findOneByPseudo($to);

        $message->setMessage($text);
        $message->setMessType($mess_type);
        $message->setSender($sender);
        $message->setDateSend(date_create());
        $message->setPlayer($player);
        $message->setVu(false);
        dump($message);
        $em = $this->getDoctrine()->getManager();
                $em->persist($message); // prépare l'insertion dans la BD
                $em->flush();

        return new Response('send');
    }

    /**
     * @Route("/messagerie", name="messagerie")
     */
    public function messagerieAction(Request $request)
    {
        $pseudo = $this->container->get("security.token_storage")->getToken()->getUser()->getUsername();

        $player = $this->getDoctrine()
                ->getRepository('AppBundle:Player')
                ->findOneByPseudo($pseudo);
        
        $messages = $this->getDoctrine()
                ->getRepository('AppBundle:Message')
                ->findByPlayer($player->getId());
        dump($messages[0]);    
        return $this->render('Sko/messagerie.html.twig', array(
            'messages' => $messages
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
    // /**
    //  * @Route("/ressource_new", name="ressource_new")
    //  */
    // public function newRessource(Request $request){
    //     if ($request->isXMLHttpRequest()) {
    //         $content = $request->getContent();
    //         if (!empty($content)) {
    //             $params = json_decode($content, true);
    //             $new = new timeEntry;
    //             $new->setDescription($params['description']);
    //             $new->setLocation($params['location']);
    //             $new->setSubject($params['subject']);
    //             $new->setAllDay($params['allDay']);
    //             $new->setEndTime(new \DateTime($params['endTime']));
    //             $new->setStartTime(new \DateTime($params['startTime']));
    //
    //             $em = $this->getDoctrine()->getManager();
    //             $calendar = $em->getRepository('AppBundle:calendar')
    //                 ->findOneBy(['id' => 1]);
    //
    //             $offers = $em->getRepository('AppBundle:offer')
    //                 ->findOneBy(['id' => 1]);
    //
    //             $new->setCalendar($calendar);
    //             $new->setOffer($offers);
    //             $new->setStatus('Open');
    //             $new->setUser($this->getUser());
    //
    //             $em->persist($new);
    //             $em->flush();
    //         }
    //         return new JsonResponse(array('data' => $params));
    //     }
    //     return new Response('Error!', 400);
    // }
}
