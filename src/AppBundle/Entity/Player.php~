<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityRepository;
/**
 * Player
 *
 * @ORM\Table(name="player")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlayerRepository")
 */
class Player extends EntityRepository implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255, unique=true)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_log", type="datetime", nullable=true)
     */
    private $dateLog;

    /**
     * @ORM\OneToMany(targetEntity="Characters", mappedBy="player")
     */
    private $characters;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    protected $salt;

    public function __construct(){
        $this->characters = new ArrayCollection();
    }
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set pseudo
     *
     * @param string $pseudo
     *
     * @return Player
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Player
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Player
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set dateLog
     *
     * @param \DateTime $dateLog
     *
     * @return Player
     */
    public function setDateLog($date_log)
    {
        $this->dateLog = $date_log;

        return $this;
    }

    /**
     * Get dateLog
     *
     * @return \DateTime
     */
    public function getDateLog()
    {
        return $this->dateLog;
    }

    /**
     * Set Characters
     *
     * @param array $characters
     *
     * @return Characters
     */
    public function setCharacters($characters)
    {
        $this->characters = $characters;

        return $this;
    }

    /**
     * Get characters
     *
     * @return array
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * Add character
     *
     * @param \AppBundle\Entity\Characters $character
     *
     * @return Player
     */
    public function addCharacter(\AppBundle\Entity\Characters $character)
    {
        $this->characters[] = $character;

        return $this;
    }

    /**
     * Remove character
     *
     * @param \AppBundle\Entity\Characters $character
     */
    public function removeCharacter(\AppBundle\Entity\Characters $character)
    {
        $this->characters->removeElement($character);
    }

    public function getUsername()
    {
        return $this->pseudo;
    }


    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->pseudo,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->pseudo,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return Player
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    public function findOneByPseudo($pseudo)
    {
        return $this->createQueryBuilder('u')
        ->andWhere('u.pseudo = :pseudo')
        ->setParameter('pseudo', $pseudo)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
}
