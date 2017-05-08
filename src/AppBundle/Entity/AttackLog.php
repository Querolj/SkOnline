<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AttackLog
 *
 * @ORM\Table(name="attack_log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AttackLogRepository")
 */
class AttackLog
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
     * @var \DateTime
     *
     * @ORM\Column(name="starting_time", type="datetime")
     */
    private $startingTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="campagn_time", type="datetime")
     */
    private $campaignTime;

    /**
     * @var int
     *
     * @ORM\Column(name="skeleton", type="integer")
     */
    private $skeleton;

    /**
     * @var int
     *
     * @ORM\Column(name="war_skeleton", type="integer")
     */
    private $war_skeleton;

    /**
     * @var int
     *
     * @ORM\Column(name="mage_skeleton", type="integer")
     */
    private $mage_skeleton;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Characters", inversedBy="attacker_log")
     * 
     */
    private $attacker;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Characters", inversedBy="defender_log")
     * 
     */
    private $defender;


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
     * Set startingTime
     *
     * @param \DateTime $startingTime
     *
     * @return AttackLog
     */
    public function setStartingTime($startingTime)
    {
        $this->startingTime = $startingTime;

        return $this;
    }

    /**
     * Get startingTime
     *
     * @return \DateTime
     */
    public function getStartingTime()
    {
        return $this->startingTime;
    }

    /**
     * Set campaignTime
     *
     * @param \DateTime $campaignTime
     *
     * @return AttackLog
     */
    public function setCampaignTime($campaignTime)
    {
        $this->campaignTime = $campaignTime;

        return $this;
    }

    /**
     * Get campaignTime
     *
     * @return \DateTime
     */
    public function getCampaignTime()
    {
        return $this->campaignTime;
    }

    /**
     * Set skeleton
     *
     * @param integer $skeleton
     *
     * @return AttackLog
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
     * Set war_skeleton
     *
     * @param integer $war_skeleton
     *
     * @return AttackLog
     */
    public function setWarSkeleton($war_skeleton)
    {
        $this->war_skeleton = $war_skeleton;

        return $this;
    }

    /**
     * Get war_skeleton
     *
     * @return int
     */
    public function getWarSkeleton()
    {
        return $this->war_skeleton;
    }

    /**
     * Set mage_skeleton
     *
     * @param integer $mage_skeleton
     *
     * @return AttackLog
     */
    public function setMageSkeleton($mage_skeleton)
    {
        $this->mage_skeleton = $mage_skeleton;

        return $this;
    }

    /**
     * Get mage_skeleton
     *
     * @return int
     */
    public function getMageSkeleton()
    {
        return $this->mage_skeleton;
    }

    /**
     * Set attacker
     *
     * @param \AppBundle\Entity\Characters $attacker
     *
     * @return AttackLog
     */
    public function setAttacker(\AppBundle\Entity\Characters $attacker = null)
    {
        $this->attacker = $attacker;

        return $this;
    }

    /**
     * Get attacker
     *
     * @return \AppBundle\Entity\Characters
     */
    public function getAttacker()
    {
        return $this->attacker;
    }

    /**
     * Set defender
     *
     * @param \AppBundle\Entity\Characters $defender
     *
     * @return AttackLog
     */
    public function setDefender(\AppBundle\Entity\Characters $defender = null)
    {
        $this->defender = $defender;

        return $this;
    }

    /**
     * Get defender
     *
     * @return \AppBundle\Entity\Characters
     */
    public function getDefender()
    {
        return $this->defender;
    }
}
