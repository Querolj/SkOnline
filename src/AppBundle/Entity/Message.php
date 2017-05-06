<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 */
class Message
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
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var bool
     *
     * @ORM\Column(name="vu", type="boolean")
     */
    private $vu;

    /**
     * @var string
     *
     * @ORM\Column(name="sender", type="string")
     */
    private $sender;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_send", type="datetime")
     */
    private $date_send;

    /**
     * @var string
     *
     * @ORM\Column(name="mess_type", type="string", length=255)
     */
    private $mess_type;

    /**
     * @var int
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="messages")
     * 
     */
    private $player;

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
     * Set message
     *
     * @param string $message
     *
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set sender
     *
     * @param string $sender
     *
     * @return Message
     */
    public function setSender($sender)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set vu
     *
     * @param boolean $vu
     *
     * @return Message
     */
    public function setVu($vu)
    {
        $this->vu = $vu;

        return $this;
    }

    /**
     * Get vu
     *
     * @return bool
     */
    public function getVu()
    {
        return $this->vu;
    }

    /**
     * Set date_send
     *
     * @param \DateTime $date_send
     *
     * @return Message
     */
    public function setDateSend($date_send)
    {
        $this->date_send = $date_send;

        return $this;
    }

    /**
     * Get date_send
     *
     * @return \DateTime
     */
    public function getDateSend()
    {
        return $this->date_send;
    }

    /**
     * Set mess_type
     *
     * @param string $mess_type
     *
     * @return Message
     */
    public function setMessType($mess_type)
    {
        $this->mess_type = $mess_type;

        return $this;
    }

    /**
     * Get mess_type
     *
     * @return string
     */
    public function getMessType()
    {
        return $this->mess_type;
    }

    /**
     * Set player
     *
     * @param \AppBundle\Entity\Player $player
     *
     * @return Message
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

}

