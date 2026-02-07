<?php

namespace Customize\Entity;

use Customize\Entity\Business;
use Customize\Entity\BusinessStaffWorkingTime;
use Customize\Entity\Staff;
use Customize\Entity\Master\BusinessStatus;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BusinessStaffWorkingTime
 *
 * @ORM\Table(name="dtb_business_staff_working_time")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\BusinessStaffWorkingTimeRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class BusinessStaffWorkingTime extends AbstractEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Customize\Entity\Business
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Business")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="business_id", referencedColumnName="id")
     * })
     */
    private $Business;

    /**
     * @var \Customize\Entity\Staff
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Staff")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="staff_id", referencedColumnName="id")
     * })
     */
    private $Staff;

    /**
    * @var integer
     *
     * @ORM\Column(name="day_of_week", type="integer", nullable=false)
     */
    private $day_of_week;

    /**
     * @var string|null
     *
     * @ORM\Column(name="working_time_start", type="string", length=16, nullable=true)
     */
    private $working_time_start;

    /**
     * @var string|null
     *
     * @ORM\Column(name="working_time_end", type="string", length=16, nullable=true)
     */
    private $working_time_end;

    /**
     * @var string|null
     *
     * @ORM\Column(name="working_time_break", type="string", length=16, nullable=true)
     */
    private $working_time_break;

    /**
     * @var boolean
     *
     * @ORM\Column(name="visible", type="boolean", options={"default":true})
     */
    private $visible = true;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetimetz")
     */
    private $create_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetimetz")
     */
    private $update_date;



    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function __toString()
    {
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return BusinessStaffWorkingTime
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Business|null
     */
    public function getBusiness(): ?Business
    {
        return $this->Business;
    }

    /**
     * @param Business $Business
     *
     * @return BusinessStaffWorkingTime
     */
    public function setBusiness(Business $Business = null)
    {
        $this->Business = $Business;

        return $this;
    }

    /**
     * @return Staff|null
     */
    public function getStaff(): ?Staff
    {
        return $this->Staff;
    }

    /**
     * @param Staff $Staff
     *
     * @return BusinessStaffWorkingTime
     */
    public function setStaff(Staff $Staff = null)
    {
        $this->Staff = $Staff;

        return $this;
    }

    /**
     * Set day_of_week.
     *
     * @param integer $day_of_week
     *
     * @return BusinessStaffWorkingTime
     */
    public function setDayOfWeek($day_of_week)
    {
        $this->day_of_week = $day_of_week;

        return $this;
    }

    /**
     * Get day_of_week.
     *
     * @return integer
     */
    public function getDayOfWeek()
    {
        return $this->day_of_week;
    }

    /**
     * Set working_time_start.
     *
     * @param string $working_time_start
     *
     * @return BusinessStaffWorkingTime
     */
    public function setWorkingTimeStart($working_time_start)
    {
        $this->working_time_start = $working_time_start;

        return $this;
    }

    /**
     * Get working_time_start.
     *
     * @return string
     */
    public function getWorkingTimeStart()
    {
        return $this->working_time_start;
    }

    /**
     * Set working_time_end.
     *
     * @param string $working_time_end
     *
     * @return BusinessStaffWorkingTime
     */
    public function setWorkingTimeEnd($working_time_end)
    {
        $this->working_time_end = $working_time_end;

        return $this;
    }

    /**
     * Get working_time_end.
     *
     * @return string
     */
    public function getWorkingTimeEnd()
    {
        return $this->working_time_end;
    }

    /**
     * Set working_time_break.
     *
     * @param string $working_time_break
     *
     * @return BusinessStaffWorkingTime
     */
    public function setWorkingTimeBreak($working_time_break)
    {
        $this->working_time_break = $working_time_break;

        return $this;
    }

    /**
     * Get working_time_break.
     *
     * @return string
     */
    public function getWorkingTimeBreak()
    {
        return $this->working_time_break;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return BusinessStaffWorkingTime
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Is the visibility visible?
     *
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return BusinessStaffWorkingTime
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get createDate.
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set updateDate.
     *
     * @param \DateTime $updateDate
     *
     * @return BusinessStaffWorkingTime
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get updateDate.
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * String representation of object
     *
     * @see http://php.net/manual/en/serializable.serialize.php
     *
     * @return string the string representation of the object or null
     *
     * @since 5.1.0
     */
    public function serialize()
    {
        // see https://symfony.com/doc/2.7/security/entity_provider.html#create-your-user-entity
        // BusinessRepository::loadUserByUsername() で Status をチェックしているため、ここでは不要
        return serialize([
            $this->id,
            $this->email,
            $this->password,
            $this->salt,
        ]);
    }

    /**
     * Constructs the object
     *
     * @see http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     *
     * @return void
     *
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            $this->salt) = unserialize($serialized);
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
