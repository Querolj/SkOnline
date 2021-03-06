<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * tasks
 *
 * @ORM\Table(name="tasks")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\tasksRepository")
 */
class tasks
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
     * @var string
     *
     * @ORM\Column(name="task_building", type="string", length=255, nullable=true)
     */
    private $taskBuilding;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_building", type="datetime", nullable=true)
     */
    private $timeBuilding;

    /**
     * @var string
     *
     * @ORM\Column(name="task_unit1", type="string", length=255, nullable=true)
     */
    private $taskUnit1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_unit1", type="datetime", nullable=true)
     */
    private $timeUnit1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="task_unit2", type="datetime", nullable=true)
     */
    private $taskUnit2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_unit2", type="datetime", nullable=true)
     */
    private $timeUnit2;

    /**
     * @var string
     *
     * @ORM\Column(name="task_unit3", type="string", length=255, nullable=true)
     */
    private $taskUnit3;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_unit3", type="datetime", nullable=true)
     */
    private $timeUnit3;

    /**
     * @var string
     *
     * @ORM\Column(name="task_tech", type="string", length=255, nullable=true)
     */
    private $taskTech;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time_tech", type="datetime", nullable=true)
     */
    private $timeTech;


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
     * @return tasks
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
     * Set taskBuilding
     *
     * @param string $taskBuilding
     *
     * @return tasks
     */
    public function setTaskBuilding($taskBuilding)
    {
        $this->taskBuilding = $taskBuilding;

        return $this;
    }

    /**
     * Get taskBuilding
     *
     * @return string
     */
    public function getTaskBuilding()
    {
        return $this->taskBuilding;
    }

    /**
     * Set timeBuilding
     *
     * @param \DateTime $timeBuilding
     *
     * @return tasks
     */
    public function setTimeBuilding($timeBuilding)
    {
        $this->timeBuilding = $timeBuilding;

        return $this;
    }

    /**
     * Get timeBuilding
     *
     * @return \DateTime
     */
    public function getTimeBuilding()
    {
        return $this->timeBuilding;
    }

    /**
     * Set taskUnit1
     *
     * @param string $taskUnit1
     *
     * @return tasks
     */
    public function setTaskUnit1($taskUnit1)
    {
        $this->taskUnit1 = $taskUnit1;

        return $this;
    }

    /**
     * Get taskUnit1
     *
     * @return string
     */
    public function getTaskUnit1()
    {
        return $this->taskUnit1;
    }

    /**
     * Set timeUnit1
     *
     * @param \DateTime $timeUnit1
     *
     * @return tasks
     */
    public function setTimeUnit1($timeUnit1)
    {
        $this->timeUnit1 = $timeUnit1;

        return $this;
    }

    /**
     * Get timeUnit1
     *
     * @return \DateTime
     */
    public function getTimeUnit1()
    {
        return $this->timeUnit1;
    }

    /**
     * Set taskUnit2
     *
     * @param \DateTime $taskUnit2
     *
     * @return tasks
     */
    public function setTaskUnit2($taskUnit2)
    {
        $this->taskUnit2 = $taskUnit2;

        return $this;
    }

    /**
     * Get taskUnit2
     *
     * @return \DateTime
     */
    public function getTaskUnit2()
    {
        return $this->taskUnit2;
    }

    /**
     * Set timeUnit2
     *
     * @param \DateTime $timeUnit2
     *
     * @return tasks
     */
    public function setTimeUnit2($timeUnit2)
    {
        $this->timeUnit2 = $timeUnit2;

        return $this;
    }

    /**
     * Get timeUnit2
     *
     * @return \DateTime
     */
    public function getTimeUnit2()
    {
        return $this->timeUnit2;
    }

    /**
     * Set taskUnit3
     *
     * @param string $taskUnit3
     *
     * @return tasks
     */
    public function setTaskUnit3($taskUnit3)
    {
        $this->taskUnit3 = $taskUnit3;

        return $this;
    }

    /**
     * Get taskUnit3
     *
     * @return string
     */
    public function getTaskUnit3()
    {
        return $this->taskUnit3;
    }

    /**
     * Set timeUnit3
     *
     * @param \DateTime $timeUnit3
     *
     * @return tasks
     */
    public function setTimeUnit3($timeUnit3)
    {
        $this->timeUnit3 = $timeUnit3;

        return $this;
    }

    /**
     * Get timeUnit3
     *
     * @return \DateTime
     */
    public function getTimeUnit3()
    {
        return $this->timeUnit3;
    }

    /**
     * Set taskTech
     *
     * @param string $taskTech
     *
     * @return tasks
     */
    public function setTaskTech($taskTech)
    {
        $this->taskTech = $taskTech;

        return $this;
    }

    /**
     * Get taskTech
     *
     * @return string
     */
    public function getTaskTech()
    {
        return $this->taskTech;
    }

    /**
     * Set timeTech
     *
     * @param \DateTime $timeTech
     *
     * @return tasks
     */
    public function setTimeTech($timeTech)
    {
        $this->timeTech = $timeTech;

        return $this;
    }

    /**
     * Get timeTech
     *
     * @return \DateTime
     */
    public function getTimeTech()
    {
        return $this->timeTech;
    }
}
