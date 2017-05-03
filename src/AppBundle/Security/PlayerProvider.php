<?php
namespace AppBundle\Security;

use AppBundle\Entity\Player;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class PlayerProvider implements UserProviderInterface
{
	protected $doctrine;
	public function __construct(\Doctrine\Bundle\DoctrineBundle\Registry $doctrine){
        $this->doctrine = $doctrine;
    }

	public function loadUserByUsername($username)
    {
        // make a call to your webservice here
        $player = new Player();
        $player = $this->doctrine
                         ->getRepository('AppBundle:Player')
                         ->findOneByPseudo($username);
        // pretend it returns an array on success, false if there is no user

        if ($player) {
            $password = $player->getPassword();
            $salt = $player->getSalt();
            $roles = $player->getRoles();
            return $player;
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof Player) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getPseudo());
    }

    public function supportsClass($class)
    {
        return Player::class === $class;
    }

}



?>