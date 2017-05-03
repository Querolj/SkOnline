<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Map
 *
 * @ORM\Table(name="map")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MapRepository")
 */
class Map
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
     * @ORM\Column(name="region", type="integer")
     */
    private $region;

    /**
     * @var int
     *
     * @ORM\Column(name="emplacement", type="integer")
     */
    private $emplacement;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Characters", inversedBy="location")
     * @ORM\JoinColumn(name="idCharacter", referencedColumnName="id")
     */
    private $character;

    /**
     * @var int
     * @ORM\Column(name="idCharacter", type="integer")
     */
    private $idCharacter;



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
     * Set region
     *
     * @param integer $region
     *
     * @return Map
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
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
     * Set emplacement
     *
     * @param integer $emplacement
     *
     * @return Map
     */
    public function setEmplacement($emplacement)
    {
        $this->emplacement = $emplacement;

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
     * Set character
     *
     * @param \AppBundle\Entity\Characters $character
     *
     * @return Characters
     */
    public function setCharacter(\AppBundle\Entity\Characters $character = null)
    {
        $this->character = $character;

        return $this;
    }

    /**
     * Get character
     *
     * @return \AppBundle\Entity\Map
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * Set idCharacter
     *
     * @param integer $idCharacter
     *
     * @return Map
     */
    public function setIdCharacter($idCharacter)
    {
        $this->idCharacter = $idCharacter;

        return $this;
    }

    /**
     * Get idcharacter
     *
     * @return int
     */
    public function getIdCharacter()
    {
        return $this->idCharacter;
    }
}

