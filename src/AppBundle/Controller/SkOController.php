<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Player;
use AppBundle\Entity\Map;
use AppBundle\Entity\Characters;
use AppBundle\Entity\Message;
use AppBundle\Entity\building;
use AppBundle\Entity\units;
use AppBundle\Entity\Ressources;
use AppBundle\Entity\AttackLog;

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
            $player->setCurrentChar(NULL);
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

            $token = new UsernamePasswordToken($player, $player->getPassword(), "main", $player->getRoles());  
            $event = new InteractiveLoginEvent(new Request(), $token);
            $this->container->get("event_dispatcher")->dispatch("security.interactive_login", $event);
            $this->container->get("security.token_storage")->setToken($token);

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
        $pseudo_char = $player->getCurrentChar();
        $current_char = $this->getDoctrine()->getRepository('AppBundle:Characters')
                            ->findOneByPseudo($pseudo_char);


        $message = new Message;
        //$units = $current_char->getUnits();

        //dump($units->getSkeleton());


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
                    "to" => $to->getPseudo(), 
                    "current_char" => $current_char
                    ));

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
                    'region' => $region,
                    "current_char" => $current_char
                    ));
            }
            else if($action == 'gauche')
            {
                $map = $this->getDoctrine()->getRepository('AppBundle:Map')->findByRegion($region);
                return $this->render('Sko/carte.html.twig', array(
                    'map' => $map,
                    'form' => $form->createView(),
                    'region' => $region,
                    "current_char" => $current_char
                    ));

            }
        }
        else
        {
            $map = $this->getDoctrine()->getRepository('AppBundle:Map')->findByRegion(10);
            return $this->render('Sko/carte.html.twig', array(
                    'map' => $map,
                    'form' => $form->createView(),
                    'region' => 10,
                    "current_char" => $current_char
                    ));
        }


        return $this->render('Sko/carte.html.twig', array(
            'map' => $map,
            'form' => $form->createView(),
            'region' => 3,
            "current_char" => $current_char
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

    public function damageCalcul($skeleton,$war_skeleton,$mage_skeleton,$is_defender)
    {
        $attack_dmg = 0;
        for($i = 1;$i <= $skeleton; $i++)
        {
            $attack_dmg = $attack_dmg + rand(1,3);
        }
        for($i = 1;$i <= $war_skeleton; $i++)
        {
            $attack_dmg = $attack_dmg + rand(2,3);
        }
        for($i = 1;$i <= $mage_skeleton; $i++)
        {
            $attack_dmg = $attack_dmg + rand(6,14);
        }
            return $attack_dmg;

    }

    public function rand_needed($skeleton_lp, $war_skeleton_lp, $mage_skeleton_lp)
    {
        if($skeleton_lp > 0 && $war_skeleton_lp > 0 && $mage_skeleton_lp > 0){
            return rand(1,3);
        }
        else if($skeleton_lp > 0 && $war_skeleton_lp > 0 ){
            return rand(1,2);
        }
        else if($war_skeleton_lp > 0 && $mage_skeleton_lp > 0){
            return rand(2,3);
        }
        else if($skeleton_lp > 0 && $mage_skeleton_lp > 0){
            if(rand(1,2)==1)
                return 1;
            else
                return 3;
        }
        else if($skeleton_lp > 0){
            return 1;
        }
        else if($war_skeleton_lp > 0){
            return 2;
        }
        else if($mage_skeleton_lp > 0){
            return 3;
        }
        else{
            return -1;
        }

    }

    public function damageTaken($damage, $war_skeleton,$mage_skeleton)
    {
        $defence = 0;
            //$attack_dmg = $skeleton+$war_skeleton+$mage_skeleton;

        for($i = 1;$i <= $war_skeleton; $i++)
        {
            $defence = $defence + 1;
        }
        for($i = 1;$i <= $mage_skeleton; $i++)
        {
            $defence = $defence + 2;
        }
        if( ($damage - $defence) > 0)
            return ($damage - $defence);
        else
            return 0;
    }

    /**
     * @Route("/update_attack_log", name="update_attack_log")
     */
    public function updateAttackLog(Request $request)
    {
        $pseudo = $request->request->get('pseudo');
        $pseudo_player = $request->request->get('player');
        $player = $this->getDoctrine()
                ->getRepository('AppBundle:Player')
                ->findOneByPseudo($pseudo_player);

        $em = $this->getDoctrine()->getManager();
        $current_char = $this->getDoctrine()
                ->getRepository('AppBundle:Characters')
                ->findOneByPseudo($pseudo);

        $attacks_log = $current_char->getAttackerLog();
        $defences_log = $current_char->getDefenderLog();
        $now = date_create();

        $rep = '';

        foreach ($attacks_log as $attack_log){
            echo $attack_log->getStartingTime()->format('Y-m-d H:i:s');
            echo "   : ";
            if(date_create() > $attack_log->getCampaignTime())
            {
                $attacker = $attack_log->getAttacker();
                $skeleton = $attack_log->getSkeleton();
                $war_skeleton = $attack_log->getWarSkeleton();
                $mage_skeleton = $attack_log->getMageSkeleton();
                $attack_dmg = $this->damageCalcul($skeleton, $war_skeleton, $mage_skeleton, false);

                $defender = $attack_log->getDefender();
                $def_skeleton = $defender->getUnits()[0]->getSkeleton(); 
                $def_war_skeleton = $defender->getUnits()[0]->getSkeletonWar(); 
                $def_mage_skeleton = $defender->getUnits()[0]->getMageSkeleton(); 
                $defence_dmg = $this->damageCalcul($def_skeleton, $def_war_skeleton, $def_mage_skeleton, true);

                //Les unités prennent des dommages et sont sauvegardés dans la DDB
                $defender_dmg_taken = $this->damageTaken($attack_dmg, $def_war_skeleton, $def_mage_skeleton);
                $attacker_dmg_taken = $this->damageTaken($defence_dmg, $war_skeleton, $mage_skeleton);

                //Perte d'unité de l'attaquant
                $skeleton_lp = $skeleton*2;
                $war_skeleton_lp = $war_skeleton*3;
                $mage_skeleton_lp = $mage_skeleton*5;
                for($i = 1; $i <= $attacker_dmg_taken; $i++)
                {
                    $rand = $this->rand_needed($skeleton_lp, $war_skeleton_lp, $mage_skeleton_lp);
                    if($rand == 1)
                        $skeleton_lp = $skeleton_lp - 1;
                    else if($rand == 2)
                        $war_skeleton_lp = $war_skeleton_lp - 1;
                    else
                        $mage_skeleton_lp = $mage_skeleton_lp - 1;
                }
                if($skeleton_lp <=0 )
                    $attacker->getUnits()[0]->setSkeleton(0);
                else
                    $attacker->getUnits()[0]->setSkeleton(ceil($skeleton_lp/2));
                if($war_skeleton_lp <= 0)
                    $attacker->getUnits()[0]->setSkeletonWar(0);
                else
                    $attacker->getUnits()[0]->setSkeletonWar(ceil($war_skeleton_lp/3));
                if($mage_skeleton_lp <= 0)
                    $attacker->getUnits()[0]->setMageSkeleton(0);
                else
                    $attacker->getUnits()[0]->setMageSkeleton(ceil($mage_skeleton_lp/2));

                $em->persist($attacker);

                //Perte d'unité du défenseur
                $def_skeleton_lp = $def_skeleton*2;
                $def_war_skeleton_lp = $def_war_skeleton*3;
                $def_mage_skeleton_lp = $def_mage_skeleton*5;
                for($i = 1; $i <= $defender_dmg_taken; $i++)
                {
                    $rand = $this->rand_needed($def_skeleton_lp, $def_war_skeleton_lp, $def_mage_skeleton_lp);
                    if($rand == 1)
                        $def_skeleton_lp = $def_skeleton_lp - 1;
                    else if($rand == 2)
                        $def_war_skeleton_lp = $def_war_skeleton_lp - 1;
                    else
                        $def_mage_skeleton_lp = $def_mage_skeleton_lp - 1;
                }
                if($def_skeleton_lp <=0 )
                    $defender->getUnits()[0]->setSkeleton(0);
                else
                    $defender->getUnits()[0]->setSkeleton(ceil($skeleton_lp/2));
                if($def_war_skeleton_lp <= 0)
                    $defender->getUnits()[0]->setSkeletonWar(0);
                else
                    $defender->getUnits()[0]->setSkeletonWar(ceil($war_skeleton_lp/3));
                if($def_mage_skeleton_lp <= 0)
                    $defender->getUnits()[0]->setMageSkeleton(0);
                else
                    $defender->getUnits()[0]->setMageSkeleton(ceil($mage_skeleton_lp/2));

                $em->remove($attack_log);
                $em->persist($defender); 
                
                //On envoie 2 messages

                $mess_def = new Message;
                $mess_atk = new Message;

                
                $mess_def->setPlayer($defender->getPlayer());
                $mess_atk->setPlayer($player);
                $mess_def->setVu(false);
                $mess_atk->setVu(false);
                $mess_def->setSender("Rapport");
                $mess_atk->setSender("Rapport");
                $mess_def->setMessType("Defense");
                $mess_atk->setMessType("Attaque");
                $mess_def->setDateSend(date_create());
                $mess_atk->setDateSend(date_create());
                

                $mess_def->setMessage("Vous avez défendu contre {$attacker->getPseudo()} à 
                    {$now->format('Y-m-d H:i:s')}.\nIl vous reste
                    {$defender->getUnits()[0]->getSkeleton()} squelettes, 
                    {$defender->getUnits()[0]->getSkeletonWar()} guerriers squelettes et
                    {$defender->getUnits()[0]->getMageSkeleton()} mages squelettes.");


                $mess_atk->setMessage("Vous avez attaqué {$defender->getPseudo()} à 
                    {$now->format('Y-m-d H:i:s')}.\nIl vous reste
                    {$attacker->getUnits()[0]->getSkeleton()} squelettes, 
                    {$attacker->getUnits()[0]->getSkeletonWar()} guerriers squelettes et
                    {$attacker->getUnits()[0]->getMageSkeleton()} mages squelettes.");

                $em->persist($mess_atk);
                $em->persist($mess_def);
                $rep = 'rep';

            }
              
            
        }

        foreach ($defences_log as $defence_log){
            echo $defence_log->getStartingTime()->format('Y-m-d H:i:s');
            if(date_create() > $defence_log->getCampaignTime())
            {
                $attacker = $defence_log->getAttacker();
                $skeleton = $defence_log->getSkeleton();
                $war_skeleton = $defence_log->getWarSkeleton();
                $mage_skeleton = $defence_log->getMageSkeleton();
                $attack_dmg = $this->damageCalcul($skeleton, $war_skeleton, $mage_skeleton, false);

                $defender = $defence_log->getDefender();
                $def_skeleton = $defender->getUnits()[0]->getSkeleton(); 
                $def_war_skeleton = $defender->getUnits()[0]->getSkeletonWar(); 
                $def_mage_skeleton = $defender->getUnits()[0]->getMageSkeleton();  
                $defence_dmg = $this->damageCalcul($def_skeleton, $def_war_skeleton, $def_mage_skeleton, true);

                //Les unités prennent des dommages et sont sauvegardés dans la DDB
                $defender_dmg_taken = $this->damageTaken($attack_dmg, $def_war_skeleton, $def_mage_skeleton);
                $attacker_dmg_taken = $this->damageTaken($defence_dmg, $war_skeleton, $mage_skeleton);

                //Perte d'unité de l'attaquant
                $skeleton_lp = $skeleton*2;
                $war_skeleton_lp = $war_skeleton*3;
                $mage_skeleton_lp = $mage_skeleton*5;
                for($i = 1; $i <= $attacker_dmg_taken; $i++)
                {
                    $rand = $this->rand_needed($skeleton_lp, $war_skeleton_lp, $mage_skeleton_lp);
                    if($rand == 1)
                        $skeleton_lp = $skeleton_lp - 1;
                    else if($rand == 2)
                        $war_skeleton_lp = $war_skeleton_lp - 1;
                    else
                        $mage_skeleton_lp = $mage_skeleton_lp - 1;
                }
                if($skeleton_lp <=0 )
                    $attacker->getUnits()[0]->setSkeleton(0);
                else
                    $attacker->getUnits()[0]->setSkeleton(ceil($skeleton_lp/2));
                if($war_skeleton_lp <= 0)
                    $attacker->getUnits()[0]->setSkeletonWar(0);
                else
                    $attacker->getUnits()[0]->setSkeletonWar(ceil($war_skeleton_lp/3));
                if($mage_skeleton_lp <= 0)
                    $attacker->getUnits()[0]->setMageSkeleton(0);
                else
                    $attacker->getUnits()[0]->setMageSkeleton(ceil($mage_skeleton_lp/2));

                $em->persist($attacker);

                //Perte d'unité du défenseur
                $def_skeleton_lp = $def_skeleton*2;
                $def_war_skeleton_lp = $def_war_skeleton*3;
                $def_mage_skeleton_lp = $def_mage_skeleton*5;
                for($i = 1; $i <= $defender_dmg_taken; $i++)
                {
                    $rand = $this->rand_needed($def_skeleton_lp, $def_war_skeleton_lp, $def_mage_skeleton_lp);
                    if($rand == 1)
                        $def_skeleton_lp = $def_skeleton_lp - 1;
                    else if($rand == 2)
                        $def_war_skeleton_lp = $def_war_skeleton_lp - 1;
                    else
                        $def_mage_skeleton_lp = $def_mage_skeleton_lp - 1;
                }
                if($def_skeleton_lp <=0 )
                    $defender->getUnits()[0]->setSkeleton(0);
                else
                    $defender->getUnits()[0]->setSkeleton(ceil($skeleton_lp/2));
                if($def_war_skeleton_lp <= 0)
                    $defender->getUnits()[0]->setSkeletonWar(0);
                else
                    $defender->getUnits()[0]->setSkeletonWar(ceil($war_skeleton_lp/3));
                if($def_mage_skeleton_lp <= 0)
                    $defender->getUnits()[0]->setMageSkeleton(0);
                else
                    $defender->getUnits()[0]->setMageSkeleton(ceil($mage_skeleton_lp/2));

                $em->remove($defence_log);
                $em->persist($defender); 
                
                //On envoie 2 messages

                $mess_def = new Message;
                $mess_atk = new Message;

                
                $mess_def->setPlayer($defender->getPlayer());
                $mess_atk->setPlayer($player);
                $mess_def->setVu(false);
                $mess_atk->setVu(false);
                $mess_def->setSender("Rapport");
                $mess_atk->setSender("Rapport");
                $mess_def->setMessType("Defense");
                $mess_atk->setMessType("Attaque");
                $mess_def->setDateSend(date_create());
                $mess_atk->setDateSend(date_create());
                
                $mess_def->setMessage("Vous avez défendu contre {$attacker->getPseudo()} à 
                    {$now->format('Y-m-d H:i:s')}.\nIl vous reste
                    {$defender->getUnits()[0]->getSkeleton()} squelettes, 
                    {$defender->getUnits()[0]->getSkeletonWar()} guerriers squelettes et
                    {$defender->getUnits()[0]->getMageSkeleton()} mages squelettes.");


                $mess_atk->setMessage("Vous avez attaqué {$defender->getPseudo()} à 
                    {$now->format('Y-m-d H:i:s')}.\nIl vous reste
                    {$attacker->getUnits()[0]->getSkeleton()} squelettes, 
                    {$attacker->getUnits()[0]->getSkeletonWar()} guerriers squelettes et
                    {$attacker->getUnits()[0]->getMageSkeleton()} mages squelettes.");

                $em->persist($mess_atk);
                $em->persist($mess_def);
                $rep = 'rep';
            } 
        }

        $em->flush();
        return new Response($rep);
    }

    /**
     * @Route("/GOGOAttack", name="GOGOAttack")
     */
    public function GOGOAttack(Request $request)
    {
        $current_char_pseudo = $request->request->get('current_char_pseudo');
        $character_to_attack_pseudo = $request->request->get('character_to_attack_pseudo');
        $character_to_attack = $this->getDoctrine()
                ->getRepository('AppBundle:Characters')
                ->findOneByPseudo($character_to_attack_pseudo);
        $current_char = $this->getDoctrine()
                ->getRepository('AppBundle:Characters')
                ->findOneByPseudo($current_char_pseudo);
        $skeleton = $request->request->get('skeleton');
        $war_skeleton = $request->request->get('war_skeleton');
        $mage_skeleton = $request->request->get('mage_skeleton');

        $attack_log = new AttackLog;
        $attack_log->setStartingTime(date_create());
        $travel_time = abs($character_to_attack->getLocation()[0]->getRegion() - $current_char->getLocation()[0]->getRegion())*10 + abs($character_to_attack->getLocation()[0]->getEmplacement() - $current_char->getLocation()[0]->getEmplacement())*5;
        $travel_time_date = date_create()->add(new \DateInterval("PT{$travel_time}S"));
        $attack_log->setCampaignTime($travel_time_date);
        $attack_log->setAttacker($current_char);
        $attack_log->setDefender($character_to_attack);
        $attack_log->setSkeleton($skeleton);
        $attack_log->setWarSkeleton($war_skeleton);
        $attack_log->setMageSkeleton($mage_skeleton);
        $em = $this->getDoctrine()->getManager();
        $em->persist($attack_log); 
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
                $skeleton = $attack_log->getSkeleton();
                $war_skeleton = $attack_log->getWarSkeleton();
                $mage_skeleton = $attack_log->getMageSkeleton();
                $when_hit = $attack_log->getCampaignTime();

                $to_string = "Votre personnage {$pseudo_char} attaque {$defender->getPseudo()} avec 
                {$skeleton} squelettes, {$war_skeleton} guerriers squelettes et {$mage_skeleton}
                 mage squelettes.\nVotre troupe est partie le {$attack_log->getStartingTime()->format('Y-m-d H:i:s')}
                .\nVous atteindrez l'ennemi dans {$when_hit->format('Y-m-d H:i:s')}.";
                array_push($attacks_displayed, $to_string);
            }

            foreach ($defends_log as $defend_log) {
                $attacker = $defend_log->getAttacker();
                $skeleton = $defend_log->getSkeleton();
                $war_skeleton = $defend_log->getWarSkeleton();
                $mage_skeleton = $defend_log->getMageSkeleton();
                $when_hit = $defend_log->getCampaignTime();

                
                $to_string = "Votre personnage {$pseudo_char} va se défendre contre {$attacker->getPseudo()}, sa troupe 
                est constituée de {$skeleton} squelettes, {$war_skeleton} guerriers squelettes et {$mage_skeleton}
                 mage squelettes.\nL'attaquant est partie le {$defend_log->getStartingTime()->format('Y-m-d H:i:s')}
                et vous atteindra le {$when_hit->format('Y-m-d H:i:s')}.";
                array_push($defences_displayed, $to_string);
            }
        }

        if((count($attacks_displayed)>0) && (count($defences_displayed)>0))
        {
            return $this->render('Sko/attack_log.html.twig', array(
            'attacks' => $attacks_displayed,
            'defences' => $defences_displayed
            ));
        }
        else if((count($attacks_displayed)>0))
        {
            return $this->render('Sko/attack_log.html.twig', array(
            'attacks' => $attacks_displayed
            ));
        }
        else if((count($defences_displayed)>0))
        {
            return $this->render('Sko/attack_log.html.twig', array(
            'defences' => $defences_displayed
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
        $player->setCurrentChar($pseudo);
        $em = $this->getDoctrine()->getManager();
        $em->persist($player);
        $em->flush();

        $token = new UsernamePasswordToken($player, $player->getPassword(), "main", $player->getRoles());  
        $event = new InteractiveLoginEvent(new Request(), $token);
        $this->container->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        $this->container->get("security.token_storage")->setToken($token);

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
           $player->setCurrentChar($name);
           $em = $this->getDoctrine()->getManager();
           $em->persist($character); // prépare l'insertion dans la BD
           $em->persist($player);
           $em->flush(); // insère dans la BD

           $token = new UsernamePasswordToken($player, $player->getPassword(), "main", $player->getRoles());  
            $event = new InteractiveLoginEvent(new Request(), $token);
            $this->container->get("event_dispatcher")->dispatch("security.interactive_login", $event);
            $this->container->get("security.token_storage")->setToken($token);

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
            return new Response('la map a déjà été initialisée.');
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
            return new Response('La map vient d\'etre initialisée.');
        }



    }

}
