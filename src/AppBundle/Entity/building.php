<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * building
 *
 * @ORM\Table(name="building")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\buildingRepository")
 */
class building
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
     * @ORM\Column(name="bones_mine", type="integer")
     */
    private $bonesMine;

    /**
     * @var int
     *
     * @ORM\Column(name="stone_mine", type="integer")
     */
    private $stoneMine;

    /**
     * @var int
     *
     * @ORM\Column(name="gem_mine", type="integer")
     */
    private $gemMine;

    /**
     * @var int
     *
     * @ORM\Column(name="human_prison", type="integer")
     */
    private $humanPrison;

    /**
     * @var int
     *
     * @ORM\Column(name="skeleton_barrack", type="integer")
     */
    private $skeletonBarrack;

    /**
     * @var int
     *
     * @ORM\Column(name="mage_skeleton_building", type="integer")
     */
    private $mageSkeletonBuilding;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Characters", inversedBy="buildings")
     * @ORM\JoinColumn(name="id_perso", referencedColumnName="id")
     */
    private $perso;

    public function __construct(){
        $this->bonesMine = 1;
        $this->stoneMine = 1;
        $this->gemMine = 1;
        $this->humanPrison = 1;
        $this->skeletonBarrack = 1;
        $this->mageSkeletonBuilding = 1;
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
     * Set bonesMine
     *
     * @param integer $bonesMine
     *
     * @return building
     */
    public function setBonesMine($bonesMine)
    {
        $this->bonesMine = $bonesMine;

        return $this;
    }

    /**
     * Get bonesMine
     *
     * @return int
     */
    public function getBonesMine()
    {
        return $this->bonesMine;
    }

    /**
     * Set stoneMine
     *
     * @param integer $stoneMine
     *
     * @return building
     */
    public function setStoneMine($stoneMine)
    {
        $this->stoneMine = $stoneMine;

        return $this;
    }

    /**
     * Get stoneMine
     *
     * @return int
     */
    public function getStoneMine()
    {
        return $this->stoneMine;
    }

    /**
     * Set gemMine
     *
     * @param integer $gemMine
     *
     * @return building
     */
    public function setGemMine($gemMine)
    {
        $this->gemMine = $gemMine;

        return $this;
    }

    /**
     * Get gemMine
     *
     * @return int
     */
    public function getGemMine()
    {
        return $this->gemMine;
    }

    /**
     * Set humanPrison
     *
     * @param integer $humanPrison
     *
     * @return building
     */
    public function setHumanPrison($humanPrison)
    {
        $this->humanPrison = $humanPrison;

        return $this;
    }

    /**
     * Get humanPrison
     *
     * @return int
     */
    public function getHumanPrison()
    {
        return $this->humanPrison;
    }

    /**
     * Set skeletonBarrack
     *
     * @param integer $skeletonBarrack
     *
     * @return building
     */
    public function setSkeletonBarrack($skeletonBarrack)
    {
        $this->skeletonBarrack = $skeletonBarrack;

        return $this;
    }

    /**
     * Get skeletonBarrack
     *
     * @return int
     */
    public function getSkeletonBarrack()
    {
        return $this->skeletonBarrack;
    }

    /**
     * Set mageSkeletonBuilding
     *
     * @param integer $mageSkeletonBuilding
     *
     * @return building
     */
    public function setMageSkeletonBuilding($mageSkeletonBuilding)
    {
        $this->mageSkeletonBuilding = $mageSkeletonBuilding;

        return $this;
    }

    /**
     * Get mageSkeletonBuilding
     *
     * @return int
     */
    public function getMageSkeletonBuilding()
    {
        return $this->mageSkeletonBuilding;
    }

    /**
     * Set perso
     *
     * @param \AppBundle\Entity\Characters $perso
     *
     * @return Characters
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


    /**
     * Add perso
     *
     * @param \AppBundle\Entity\Characters $perso
     *
     * @return building
     */
    public function addPerso(\AppBundle\Entity\Characters $perso)
    {
        $this->perso[] = $perso;

        return $this;
    }

    /**
     * Remove perso
     *
     * @param \AppBundle\Entity\Characters $perso
     */
    public function removePerso(\AppBundle\Entity\Characters $perso)
    {
        $this->perso->removeElement($perso);
    }
}
