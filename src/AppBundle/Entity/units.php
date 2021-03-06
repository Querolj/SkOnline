<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * units
 *
 * @ORM\Table(name="units")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\unitsRepository")
 */
class units
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
     * @ORM\Column(name="skeleton", type="integer")
     */
    private $skeleton;

    /**
     * @var int
     *
     * @ORM\Column(name="skeleton_war", type="integer", nullable=true)
     */
    private $skeletonWar;

    /**
     * @var int
     *
     * @ORM\Column(name="mage_skeleton", type="integer", nullable=true)
     */
    private $mageSkeleton;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Characters", inversedBy="units")
     * @ORM\JoinColumn(name="id_perso", referencedColumnName="id")
     */
    private $perso;

    public function __construct(){
        $this->skeleton = 3;
        $this->skeletonWar = 1;
        $this->mageSkeleton = 0;
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
     * Set skeleton
     *
     * @param integer $skeleton
     *
     * @return units
     */
    public function setSkeleton($skeleton)
    {
        $this->skeleton = $skeleton;

        return $this;
    }

    /**
     * Get skeleton
     *
     * @return int
     */
    public function getSkeleton()
    {
        return $this->skeleton;
    }

    /**
     * Set skeletonWar
     *
     * @param integer $skeletonWar
     *
     * @return units
     */
    public function setSkeletonWar($skeletonWar)
    {
        $this->skeletonWar = $skeletonWar;

        return $this;
    }

    /**
     * Get skeletonWar
     *
     * @return int
     */
    public function getSkeletonWar()
    {
        return $this->skeletonWar;
    }

    /**
     * Set mageSkeleton
     *
     * @param integer $mageSkeleton
     *
     * @return units
     */
    public function setMageSkeleton($mageSkeleton)
    {
        $this->mageSkeleton = $mageSkeleton;

        return $this;
    }

    /**
     * Get mageSkeleton
     *
     * @return int
     */
    public function getMageSkeleton()
    {
        return $this->mageSkeleton;
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
