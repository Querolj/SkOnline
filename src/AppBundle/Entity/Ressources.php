<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ressources
 *
 * @ORM\Table(name="ressources")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RessourcesRepository")
 */
class Ressources
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="os", type="integer", nullable=true)
     */
    private $os;

    /**
     * @var int
     *
     * @ORM\Column(name="pierre", type="integer", nullable=true)
     */
    private $pierre;

    /**
     * @var int
     *
     * @ORM\Column(name="metal", type="integer", nullable=true)
     */
    private $metal;

    /**
     * @var int
     *
     * @ORM\Column(name="human", type="integer", nullable=true)
     */
    private $human;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Characters", inversedBy="ressources")
     * @ORM\JoinColumn(name="id_perso", referencedColumnName="id")
     */
    private $perso;

    public function __construct(){
        $this->os = 10;
        $this->pierre = 10;
        $this->metal = 10;
        $this->human = 3;
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
     * Set os
     *
     * @param integer $os
     *
     * @return Ressources
     */
    public function setOs($os)
    {
        $this->os = $os;

        return $this;
    }

    /**
     * Get os
     *
     * @return int
     */
    public function getOs()
    {
        return $this->os;
    }

    /**
     * Set pierre
     *
     * @param integer $pierre
     *
     * @return Ressources
     */
    public function setPierre($pierre)
    {
        $this->pierre = $pierre;

        return $this;
    }

    /**
     * Get pierre
     *
     * @return int
     */
    public function getPierre()
    {
        return $this->pierre;
    }

    /**
     * Set metal
     *
     * @param integer $metal
     *
     * @return Ressources
     */
    public function setMetal($metal)
    {
        $this->metal = $metal;

        return $this;
    }

    /**
     * Get metal
     *
     * @return int
     */
    public function getMetal()
    {
        return $this->metal;
    }

    /**
     * Set human
     *
     * @param integer $human
     *
     * @return Ressources
     */
    public function setHuman($human)
    {
        $this->human = $human;

        return $this;
    }

    /**
     * Get human
     *
     * @return int
     */
    public function getHuman()
    {
        return $this->human;
    }

    /**
     * Set player
     *
     * @param integer $player
     *
     * @return Ressources
     */
    public function setPlayer($player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return int
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set perso
     *
     * @param \AppBundle\Entity\Characters $perso
     *
     * @return Map
     */
    public function setPerso(\AppBundle\Entity\Characters $perso = null)
    {
        $this->perso = $perso;

        return $this;
    }

    /**
     * Get perso
     *
     * @return \AppBundle\Entity\Characters
     */
    public function getPerso()
    {
        return $this->perso;
    }
}
