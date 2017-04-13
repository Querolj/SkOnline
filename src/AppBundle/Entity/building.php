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
     * @var string
     *
     * @ORM\Column(name="pseudo", type="string", length=255, unique=true)
     */
    private $pseudo;

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
     * @return building
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
}

