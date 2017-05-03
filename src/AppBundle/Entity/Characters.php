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
     * Image file
     *
     * @var File
     *
     * @Assert\File(
     *     maxSize = "5M",
     *     mimeTypes = {"image/jpeg", "image/gif", "image/png", "image/tiff"},
     *     maxSizeMessage = "The maxmimum allowed file size is 5MB.",
     *     mimeTypesMessage = "Only the filetypes image are allowed."
     * )
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="building", mappedBy="player")
     */
    private $buildings;

    /**
     * @ORM\OneToMany(targetEntity="units", mappedBy="player")
     */
    private $units;

    /**
     * @ORM\OneToMany(targetEntity="Ressources", mappedBy="player")
     */
    private $ressources;

    /**
     * @ORM\OneToMany(targetEntity="Map", mappedBy="character" )
     */
    private $location;

    /**
     * @var int
     * @ORM\Column(name="region", type="integer")
     */
    private $region;

    /**
     * @var int
     * @ORM\Column(name="emplacement", type="integer")
     */
    private $emplacement;


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
     * @param File $image
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
     * @return File
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
     * Get region
     *
     * @return int
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set region
     *
     * @param int $region
     *
     * @return Characters
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get emplacement
     *
     * @return int
     */
    public function getEmplacement()
    {
        return $this->emplacement;
    }

    /**
     * Set emplacement
     *
     * @param int $emplacement
     *
     * @return Characters
     */
    public function setEmplacement($emplacement)
    {
        $this->emplacement = $emplacement;
        return $this;
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
}
