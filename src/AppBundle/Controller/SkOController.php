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
use Symfony\Component\HttpFoundation\JsonResponse;

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
        $player = $this->container->get("security.token_storage")->getToken()->getUser();
        // $pseudo = $request->query->get('pseudo');
        // $player = $this->getDoctrine()
        //     ->getRepository('AppBundle:Player')
        //     ->findOneByPseudo($pseudo);
        $persos = $player->getCharacters();
        return $this->render('Sko/accueil.html.twig', array('pseudo' => $player->getPseudo(), 'persos' => $persos));
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
     * @Route("/update_attack_log", name="update_attack_log")
     */
    public function updateAttackLog(Request $request)
    {
        //Vérifier le log de l'user et comparer à date_create()
        $pseudo = $request->request->get('player');

        $current_char = $this->getDoctrine()
                ->getRepository('AppBundle:Characters')
                ->findOneByPseudo($pseudo);
        echo $current_char->getPseudo();
        $attack_log = $current_char->getAttackerLog();
        $defender_log = $current_char->getDefenderLog();

        foreach ($attack_log as $attack){
            echo $attack->getStartTime();
        }

        foreach ($defender_log as $defender){
            echo $defender->getStartTime();
        }


        return new Response('');
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
     * @Route("/attackLog", name="attackLog")
     */
    public function attackLogAction(Request $request)
    {
        $attacks_displayed = array();
        $defences_displayed = array();

        $pseudo = $this->container->get("security.token_storage")->getToken()->getUser()->getUsername();
        $player = $this->getDoctrine()
                ->getRepository('AppBundle:Player')
                ->findOneByPseudo($pseudo);
        $characters = $player->getCharacters();

        foreach ($characters as $character) {
            $pseudo_char = $character->getPseudo();
            $attacks_log = $character->getAttackerLog();
            $defends_log = $character->getDefenderLog();

            foreach ($attacks_log as $attack_log) {
                $defender = $attack_log->getDefender();
                $unit_number = $attack_log->getUnitNumber();
                $skeleton_or_skeletons = " squelettes";
                $when_hit = date_diff(date_create(), $attack_log->getCampaignTime());

                if($unit_number == 1)
                    $skeleton_or_skeletons = " squelette";
                $to_string = "Votre personnage "+$pseudo_char+" attaque "+$defender+" avec "+
                $unit_number+$skeleton_or_skeletons+", votre troupe est partie le "+$attack_log->getStartTime()+
                ".\nVous atteindrez l'ennemi dans "+$when_hit->format('%R%a days')+".";
                array_push($attacks_displayed, $to_string);
            }

            foreach ($defends_log as $defend_log) {
                $attacker = $defend_log->getAttacker();
                $unit_number = $defend_log->getUnitNumber();
                $skeleton_or_skeletons = " squelettes";
                $when_hit = date_diff(date_create(), $defend_log->getCampaignTime());

                if($unit_number == 1)
                    $skeleton_or_skeletons = " squelette";
                $to_string = "Votre personnage "+$pseudo_char+" va défendre contre "+$attacker+"qui arrive avec "+
                $unit_number+$skeleton_or_skeletons+", sa troupe est partie le "+$defend_log->getStartTime()+
                ".\nL'ennemi atteind vos rempart dans "+$when_hit->format('%R%a days')+".";
                array_push($defences_displayed, $to_string);
            }
        }

        if((count($attacks_displayed)>0) && (count($defences_displayed)>0))
        {
            return $this->render('Sko/attack_log.html.twig', array(
            'attacks' -> $attacks_displayed,
            'defences' -> $defences_displayed
            ));
        }
        else if((count($attacks_displayed)>0))
        {
            return $this->render('Sko/attack_log.html.twig', array(
            'attacks' -> $attacks_displayed,
            ));
        }
        else if((count($defences_displayed)>0))
        {
            return $this->render('Sko/attack_log.html.twig', array(
            'defences' -> $defences_displayed
            ));
        }
        else
        {
            return $this->render('Sko/attack_log.html.twig', array(
            ));
        }

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
        $pseudo = $request->query->get('perso');
        //$pseudoPlayer = $request->query->get('pseudo');
        $perso = $this->getDoctrine()
            ->getRepository('AppBundle:Characters')
            ->findOneByPseudo($pseudo);
        $player = $this->container->get("security.token_storage")->getToken()->getUser();
        return $this->render('Sko/unite.html.twig', array('pseudo' => $player->getPseudo(),'perso' => $perso));
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
            $player = $this->container->get("security.token_storage")->getToken()->getUser();
            // $pseudo = $request->query->get('pseudo');
            // $player = $this->getDoctrine()
            //     ->getRepository('AppBundle:Player')
            //     ->findOneByPseudo($pseudo);
            $idPlayer = $player->getId();
            $name = $form['pseudo']->getData();
            $image = $form['image']->getData();

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

            return $this->render('Sko/accueil.html.twig', array('pseudo' => $player->getPseudo(), 'persos' => $player->getCharacters()));
        }
        return $this->render('Sko/createPerso.html.twig', array('form' => $form->createView()));
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function uniteLogout(Request $request)
    {
        return $this->render('Sko/menu.html.twig', array());
    }
    /**
     * @Route("/save", name="save")
     */
    public function saveAction(Request $request){
        if ($request->isXMLHttpRequest()) {
            $content = $request->getContent();
            if (!empty($content)) {

                $params = json_decode($content, true);
                $perso = $this->getDoctrine()
                    ->getRepository('AppBundle:Characters')
                    ->findOneByPseudo($params['perso']);

                $ressource = $this->getDoctrine()
                    ->getRepository('AppBundle:Ressources')
                    ->findOneByPerso($perso);
                $ressource->setOs($params['ressources']['os']);
                $ressource->setPierre($params['ressources']['pierre']);
                $ressource->setMetal($params['ressources']['metal']);
                $ressource->setHuman($params['ressources']['human']);


                $em = $this->getDoctrine()->getManager();
                $em->persist($ressource);
                $em->flush();
            }
            return new JsonResponse(array('data' =>$params));
        }
        return new Response('Error!', 400);
    }
    /**
     * @Route("/actionB", name="actionBuilding")
     */
    public function buildingAction(Request $request)
    {
        $pseudo = $request->query->get('pseudo');
        $modif = $request->query->get('ressource');
        $perso = $this->getDoctrine()
            ->getRepository('AppBundle:Characters')
            ->findOneByPseudo($pseudo);

        $ressource = $this->getDoctrine()
                ->getRepository('AppBundle:Ressources')
                ->findOneByPerso($perso);
        $building = $this->getDoctrine()
                ->getRepository('AppBundle:building')
                ->findOneByPerso($perso);
        switch ($modif) {
            case 'os':
                $os = $ressource->getOs();
                $pierre = $ressource->getPierre();
                if ($os >= 20 && $pierre >= 40) {
                    $ressource->setOs($os-20);
                    $ressource->setPierre($pierre-40);
                    $building->setBonesMine($building->getBonesMine()+1);
                }
                break;

            case 'pierre':
                $os = $ressource->getOs();
                $pierre = $ressource->getPierre();
                if ($os >= 40 && $pierre >= 20) {
                    $ressource->setOs($os-40);
                    $ressource->setPierre($pierre-20);
                    $building->setStoneMine($building->getStoneMine()+1);
                }
                break;

            case 'métal':
                $os = $ressource->getOs();
                $pierre = $ressource->getPierre();
                $metal = $ressource->getMetal();
                if ($os >= 50 && $pierre >= 50 && $metal >= 40) {
                    $ressource->setOs($os-50);
                    $ressource->setPierre($pierre-50);
                    $ressource->setMetal($metal-40);
                    $building->setGemMine($building->getGemMine()+1);
                }
                break;

            case 'humain':
                $os = $ressource->getOs();
                $pierre = $ressource->getPierre();
                $metal = $ressource->getMetal();
                if ($os >= 100 && $pierre >= 100 && $metal >= 50) {
                    $ressource->setOs($os-100);
                    $ressource->setPierre($pierre-100);
                    $ressource->setMetal($metal-50);
                    $building->setHumanPrison($building->getHumanPrison()+1);
                }
                break;

            case 'squelette':
                $os = $ressource->getOs();
                $pierre = $ressource->getPierre();
                $metal = $ressource->getMetal();
                if ($os >= 200 && $pierre >= 200 && $metal >= 100) {
                    $ressource->setOs($os-200);
                    $ressource->setPierre($pierre-200);
                    $ressource->setMetal($metal-100);
                    $building->setSkeletonBarrack($building->getSkeletonBarrack()+1);
                }
                break;

            case 'mage':
                $os = $ressource->getOs();
                $pierre = $ressource->getPierre();
                $metal = $ressource->getMetal();
                if ($os >= 200 && $pierre >= 200 && $metal >= 100) {
                    $ressource->setOs($os-200);
                    $ressource->setPierre($pierre-200);
                    $ressource->setMetal($metal-100);
                    $building->setMageSkeletonBuilding($building->getMageSkeletonBuilding()+1);
                }
                break;

            default:
                break;
        }
        $building->setPerso($perso);
        $em = $this->getDoctrine()->getManager();
        $em->persist($building);
        $em->flush();
        return $this->render('Sko/unite.html.twig', array('perso' => $perso));
    }
    /**
     * @Route("/actionU", name="actionUnite")
     */
    public function newUniteAction(Request $request)
    {
        $pseudo = $request->query->get('pseudo');
        $modif = $request->query->get('ressource');
        $perso = $this->getDoctrine()
            ->getRepository('AppBundle:Characters')
            ->findOneByPseudo($pseudo);
        $ressource = $this->getDoctrine()
                ->getRepository('AppBundle:Ressources')
                ->findOneByPerso($perso);
        $units = $this->getDoctrine()
                ->getRepository('AppBundle:units')
                ->findOneByPerso($perso);
        switch ($modif) {
            case 'squelette':
                $os = $ressource->getOs();
                $human = $ressource->getHuman();
                if ($os >= 60 && $human > 1) {
                    $ressource->setOs($os-60);
                    $ressource->setHuman($human-1);
                    $units->setSkeleton($units->getSkeleton()+1);
                }
                break;

            case 'war':
                $os = $ressource->getOs();
                $metal = $ressource->getMetal();
                $human = $ressource->getHuman();
                if ($os >= 60 && $metal >= 10 && $human > 1) {
                    $ressource->setOs($os-60);
                    $ressource->setMetal($metal-10);
                    $ressource->setHuman($human-1);
                    $units->setSkeletonWar($units->getSkeletonWar()+1);
                }
                break;

            case 'mage':
                $os = $ressource->getOs();
                $metal = $ressource->getMetal();
                $human = $ressource->getHuman();
                if ($os >= 180 && $metal >= 30 && $human > 3) {
                    $ressource->setOs($os-180);
                    $ressource->setMetal($metal-30);
                    $ressource->setHuman($human-3);
                    $units->setMageSkeleton($units->getMageSkeleton()+1);
                }
                break;

            default:
                break;
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($units);
        $em->persist($ressource);
        $em->flush();
        return $this->render('Sko/unite.html.twig', array('perso' => $perso));
    }


    /**
     * @Route("/initMap", name="initMap")
     */
    public function initMapAction(Request $request)
    {
        return $this->render('Sko/init.html.twig', array(

            ));
    }

    /**
     * @Route("/init", name="init")
     */
    public function initMap(Request $request)
    {
        $check_map = $this->getDoctrine()->getRepository("AppBundle:Map")->findById(5);
        if($check_map)
        {
            return new Response('la map a déjà été initialisé.');
        }
        else
        {
            $empty_character = $this->getDoctrine()->getRepository("AppBundle:Characters")->findOneById(1);
            $em = $this->getDoctrine()->getManager();
            $location = new Map();

            for ($r = 1; $r <= 20; $r++)
            {
                for ($e = 1; $e <= 10; $e++)
                {
                    $location = new Map();
                    $location->setEmplacement($e);
                    $location->setRegion($r);
                    $location->setCharacter(NULL);

                    $em->persist($location);

                }

            }

            $em->flush();
            return new Response('La map vient d\'etre initialisé.');
        }



    }

}
