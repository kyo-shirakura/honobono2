<?php

namespace Customize\Entity;

use Customize\Entity\Business;
use Customize\Entity\BusinessStaff;
use Customize\Entity\Staff;
use Customize\Entity\Master\BusinessStatus;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BusinessStaff
 *
 * @ORM\Table(name="dtb_business_staff")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\BusinessStaffRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class BusinessStaff extends AbstractEntity
{
    /**
     * @var int
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
     * @var \Customize\Entity\Master\BusinessStatus
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\BusinessStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     * })
     */
    private $Status;

    /**
     * @var string
     *
     * @ORM\Column(name="period_start", type="string", nullable=true)
     */
    private $period_start;

    /**
    * @var string
     *
     * @ORM\Column(name="period_end", type="string", nullable=true)
     */
    private $period_end;

    /**
    * @var integer
     *
     * @ORM\Column(name="regular_type", type="integer", nullable=true)
     */
    private $regular_type;

    /**
    * @var integer
     *
     * @ORM\Column(name="week_no", type="integer", nullable=true)
     */
    private $week_no;

    /**
     * @var int|null
     *
     * @ORM\Column(name="fare", type="integer", nullable=true)
     */
    private $fare;

    /**
     * @var string|null
     *
     * @ORM\Column(name="access", type="string", length=1024, nullable=true)
     */
    private $access;

    /**
     * @var string|null
     *
     * @ORM\Column(name="working_time_other", type="string", length=1024, nullable=true)
     */
    private $working_time_other;

    /**
     * @var string|null
     *
     * @ORM\Column(name="remark", type="string", length=1024, nullable=true)
     */
    private $remark;

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
     * @return BusinessStaff
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
     * @return BusinessStaff
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
     * @return BusinessStaff
     */
    public function setStaff(Staff $Staff = null)
    {
        $this->Staff = $Staff;

        return $this;
    }

    /**
     * Set period.
     *
     * @param date $period
     *
     * @return BusinessStaff
     */
    public function setPeriodStart($period_start)
    {
        $this->period_start = $period_start;

        return $this;
    }

    /**
     * Get period.
     *
     * @return String
     */
    public function getPeriodStart()
    {
        return $this->period_start;
    }

    /**
     * Set period_end.
     *
     * @param date $period_end
     *
     * @return BusinessStaff
     */
    public function setPeriodEnd($period_end)
    {
        $this->period_end = $period_end;

        return $this;
    }

    /**
     * Get period_end.
     *
     * @return string
     */
    public function getPeriodEnd()
    {
        return $this->period_end;
    }

    /**
     * Set regular_type.
     *
     * @param integer $regular_type
     *
     * @return BusinessStaff
     */
    public function setRegularType($regular_type)
    {
        $this->regular_type = $regular_type;

        return $this;
    }

    /**
     * Get regular_type.
     *
     * @return integer
     */
    public function getRegularType()
    {
        return $this->regular_type;
    }

    /**
     * Set week_no.
     *
     * @param integer $week_no
     *
     * @return BusinessStaff
     */
    public function setWeekNo($week_no)
    {
        $this->week_no = $week_no;

        return $this;
    }

    /**
     * Get week_no.
     *
     * @return integer
     */
    public function getWeekNo()
    {
        return $this->week_no;
    }

    /**
     * Set fare.
     *
     * @param int $fare
     *
     * @return BusinessStaff
     */
    public function setFare($fare)
    {
        $this->fare = $fare;

        return $this;
    }

    /**
     * Get fare.
     *
     * @return int
     */
    public function getFare()
    {
        return $this->fare;
    }

    /**
     * Set access.
     *
     * @param string $access
     *
     * @return BusinessStaff
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access.
     *
     * @return string
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set working_time_other.
     *
     * @param string $working_time_other
     *
     * @return BusinessStaff
     */
    public function setWorkingTimeOther($working_time_other)
    {
        $this->working_time_other = $working_time_other;

        return $this;
    }

    /**
     * Get working_time_other.
     *
     * @return string
     */
    public function getWorkingTimeOther()
    {
        return $this->working_time_other;
    }

    /**
     * Set remark.
     *
     * @param string $remark
     *
     * @return BusinessStaff
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get remark.
     *
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Get status.
     *
     * @return BusinessStaff
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * @param Status $Status
     *
     * @return BusinessStaff
     */
    public function setStatus(BusinessStatus $Status = null)
    {
        $this->Status = $Status;

        return $this;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return BusinessStaff
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
     * @return BusinessStaff
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
     * @return BusinessStaff
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
