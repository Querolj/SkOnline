<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var int
     * @ORM\Column(name="id_player", type="integer")
     */
    private $idPlayer;

    /**
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255, unique=true)
     */
    private $pseudo;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var array
     *
     * @ORM\Column(name="buildings", type="array")
     */
    private $buildings;

    /**
     * @var array
     *
     * @ORM\Column(name="units", type="array")
     */
    private $units;

    /**
     * @var array
     *
     * @ORM\Column(name="ressources", type="array")
     */
    private $ressources;

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
     * Set idPlayer
     *
     * @param integer $idPlayer
     *
     * @return Characters
     */
    public function setIdPlayer($idPlayer)
    {
        $this->idPlayer = $idPlayer;

        return $this;
    }

    /**
     * Get idPlayer
     *
     * @return int
     */
    public function getIdPlayer()
    {
        return $this->idPlayer;
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
     * @return Characters
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
}
