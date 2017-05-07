<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Characters
 *
 * @ORM\Table(name="characters")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CharactersRepository")
 */
class Characters
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
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="characters")
     * @ORM\JoinColumn(name="id_player", referencedColumnName="id")
     */
    private $player;


    /**
     * @var string
     * @ORM\Column(name="pseudo", type="string", length=255, unique=true)
     */
    private $pseudo;

    /**
     * Image
     * @var string
     * @ORM\Column(name="image", type="string", length=255, unique=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="building", mappedBy="perso")
     */
    private $buildings;

    /**
     * @ORM\OneToMany(targetEntity="units", mappedBy="perso")
     */
    private $units;

    /**
     * @ORM\OneToMany(targetEntity="Ressources", mappedBy="perso")
     */
    private $ressources;

    /**
     * @ORM\OneToMany(targetEntity="Map", mappedBy="character" )
     */
    private $location;

    /**
     * @ORM\OneToMany(targetEntity="AttackLog", mappedBy="attacker" )
     */
    private $attacker_log;

    /**
     * @ORM\OneToMany(targetEntity="AttackLog", mappedBy="defender" )
     */
    private $defender_log;


    public function __construct(){
        $this->ressources = new ArrayCollection();
        $this->units = new ArrayCollection();
        $this->buildings = new ArrayCollection();
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
     * @return Characters
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
     * Set image
     *
     * @param string $image
     *
     * @return Characters
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }



    /**
     * Set buildings
     *
     * @param array $buildings
     *
     * @return Characters
     */
    public function setBuildings($buildings)
    {
        $this->buildings = $buildings;

        return $this;
    }

    /**
     * Get buildings
     *
     * @return array
     */
    public function getBuildings()
    {
        return $this->buildings;
    }

    /**
     * Set units
     *
     * @param array $units
     *
     * @return Characters
     */
    public function setUnits($units)
    {
        $this->units = $units;

        return $this;
    }

    /**
     * Get units
     *
     * @return array
     */
    public function getUnits()
    {
        return $this->units;
    }

    /**
     * Set ressources
     *
     * @param array $ressources
     *
     * @return Characters
     */
    public function setRessources($ressources)
    {
        $this->ressources = $ressources;

        return $this;
    }

    /**
     * Get ressources
     *
     * @return array
     */
    public function getRessources()
    {
        return $this->ressources;
    }

    /**
     * Set player
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return Map
     */
    public function setPlayer(\AppBundle\Entity\Player $player = null)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \AppBundle\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Add building
     *
     * @param \AppBundle\Entity\building $building
     *
     * @return Characters
     */
    public function addBuilding(\AppBundle\Entity\building $building)
    {
        $this->buildings[] = $building;

        return $this;
    }

    /**
     * Remove building
     *
     * @param \AppBundle\Entity\building $building
     */
    public function removeBuilding(\AppBundle\Entity\building $building)
    {
        $this->buildings->removeElement($building);
    }

    /**
     * Add unit
     *
     * @param \AppBundle\Entity\units $unit
     *
     * @return Characters
     */
    public function addUnit(\AppBundle\Entity\units $unit)
    {
        $this->units[] = $unit;

        return $this;
    }

    /**
     * Remove unit
     *
     * @param \AppBundle\Entity\units $unit
     */
    public function removeUnit(\AppBundle\Entity\units $unit)
    {
        $this->units->removeElement($unit);
    }

    /**
     * Add ressource
     *
     * @param \AppBundle\Entity\Ressources $ressource
     *
     * @return Characters
     */
    public function addRessource(\AppBundle\Entity\Ressources $ressource)
    {
        $this->ressources[] = $ressource;

        return $this;
    }

    /**
     * Remove ressource
     *
     * @param \AppBundle\Entity\Ressources $ressource
     */
    public function removeRessource(\AppBundle\Entity\Ressources $ressource)
    {
        $this->ressources->removeElement($ressource);
    }


    /**
     * Add location
     *
     * @param \AppBundle\Entity\Map $location
     *
     * @return Characters
     */
    public function addLocation(\AppBundle\Entity\Map $location)
    {
        $this->location[] = $location;

        return $this;
    }

    /**
     * Remove location
     *
     * @param \AppBundle\Entity\Map $location
     */
    public function removeLocation(\AppBundle\Entity\Map $location)
    {
        $this->location->removeElement($location);
    }

    /**
     * Get location
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Get attacker_log
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttackerLog()
    {
        return $this->attacker_log;
    }

    /**
     * Set AttackLog
     *
     * @param array $attack_log
     *
     * @return AttackLog
     */
    public function setAttackLog($attack_log)
    {
        $this->attack_log = $attack_log;

        return $this;
    }

    /**
     * Get defender_log
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDefenderLog()
    {
        return $this->defender_log;
    }

    /**
     * Set AttackLog
     *
     * @param array $defender_log
     *
     * @return AttackLog
     */
    public function setDefenderLog($defender_log)
    {
        $this->defender_log = $defender_log;
        return $this;
    }

    /**
     * Add attackerLog
     *
     * @param \AppBundle\Entity\AttackLog $attackerLog
     *
     * @return Characters
     */
    public function addAttackerLog(\AppBundle\Entity\AttackLog $attackerLog)
    {
        $this->attacker_log[] = $attackerLog;

        return $this;
    }

    /**
     * Remove attackerLog
     *
     * @param \AppBundle\Entity\AttackLog $attackerLog
     */
    public function removeAttackerLog(\AppBundle\Entity\AttackLog $attackerLog)
    {
        $this->attacker_log->removeElement($attackerLog);
    }

    /**
     * Add defenderLog
     *
     * @param \AppBundle\Entity\AttackLog $defenderLog
     *
     * @return Characters
     */
    public function addDefenderLog(\AppBundle\Entity\AttackLog $defenderLog)
    {
        $this->defender_log[] = $defenderLog;

        return $this;
    }

    /**
     * Remove defenderLog
     *
     * @param \AppBundle\Entity\AttackLog $defenderLog
     */
    public function removeDefenderLog(\AppBundle\Entity\AttackLog $defenderLog)
    {
        $this->defender_log->removeElement($defenderLog);
    }
}
