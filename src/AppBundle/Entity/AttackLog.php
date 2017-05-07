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
     * @ORM\Column(name="unit_number", type="integer")
     */
    private $unitNumber;

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
     * Set unitNumber
     *
     * @param integer $unitNumber
     *
     * @return AttackLog
     */
    public function setUnitNumber($unitNumber)
    {
        $this->unitNumber = $unitNumber;

        return $this;
    }

    /**
     * Get unitNumber
     *
     * @return int
     */
    public function getUnitNumber()
    {
        return $this->unitNumber;
    }

    /**
     * Set attacker
     *
     * @param \AppBundle\Entity\Player $attacker
     *
     * @return AttackLog
     */
    public function setAttacker(\AppBundle\Entity\Player $attacker = null)
    {
        $this->attacker = $attacker;

        return $this;
    }

    /**
     * Get attacker
     *
     * @return \AppBundle\Entity\Player
     */
    public function getAttacker()
    {
        return $this->attacker;
    }

    /**
     * Set defender
     *
     * @param \AppBundle\Entity\Player $defender
     *
     * @return AttackLog
     */
    public function setDefender(\AppBundle\Entity\Player $defender = null)
    {
        $this->defender = $defender;

        return $this;
    }

    /**
     * Get defender
     *
     * @return \AppBundle\Entity\Player
     */
    public function getDefender()
    {
        return $this->defender;
    }
}

