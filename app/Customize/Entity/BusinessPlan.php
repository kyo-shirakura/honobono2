<?php

namespace Customize\Entity;

use Customize\Entity\BusinessPlan;
use Customize\Entity\Staff;
use Customize\Entity\Master\ConfigStatus;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Eccube\Entity\Customer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BusinessPlan
 *
 * @ORM\Table(name="dtb_business_plan")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\CBusinessPlanRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class BusinessPlan extends AbstractEntity
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
    * @var int
     *
     * @ORM\Column(name="contractor_id", type="integer", nullable=true)
     */
    private $contractor_id;

    /**
    * @var int
     *
     * @ORM\Column(name="kind_id", type="integer", nullable=true)
     */
    private $kind_id;

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
     * @var string|null
     *
     * @ORM\Column(name="basic_time_start", type="string", length=16, nullable=true)
     */
    private $basic_time_start;

    /**
     * @var string|null
     *
     * @ORM\Column(name="basic_time_end", type="string", length=16, nullable=true)
     */
    private $basic_time_end;

    /**
     * @var int|null
     *
     * @ORM\Column(name="basic_time_bill", type="integer", nullable=false)
     */
    private $basic_time_bill;

    /**
     * @var int|null
     *
     * @ORM\Column(name="bill_outside", type="integer", nullable=true)
     */
    private $bill_outside;

    /**
     * @var int|null
     *
     * @ORM\Column(name="basic_time_pay", type="integer", nullable=false)
     */
    private $basic_time_pay;

    /**
     * @var int|null
     *
     * @ORM\Column(name="pay_outside", type="integer", nullable=true)
     */
    private $pay_outside;

    /**
     * @var int|null
     *
     * @ORM\Column(name="commission", type="integer", nullable=true)
     */
    private $commission;

    /**
     * @var int|null
     *
     * @ORM\Column(name="tax", type="integer", nullable=true)
     */
    private $tax;

    /**
     * @var string|null
     *
     * @ORM\Column(name="transfer_date", type="string", length=128, nullable=true)
     */
    private $transfer_date;

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
     * Set contractor_id.
     *
     * @param int $contractor_id
     *
     * @return Business
     */
    public function setContractorId($contractor_id)
    {
        $this->contractor_id = $contractor_id;

        return $this;
    }

    /**
     * Get contractor_id.
     *
     * @return int
     */
    public function getContractorId()
    {
        return $this->contractor_id;
    }

    /**
     * Set setKindId.
     *
     * @param int $kind_id
     *
     * @return Business
     */
    public function setKindId($kind_id)
    {
        $this->kind_id = $kind_id;

        return $this;
    }

    /**
     * Get getKindId.
     *
     * @return int
     */
    public function getKindId()
    {
        return $this->kind_id;
    }

    /**
     * Set period.
     *
     * @param date $period
     *
     * @return BusinessPlan
     */
    public function setPeriodStart($period_start)
    {
        $this->period_start = $period_start;

        return $this;
    }

    /**
     * Get period.
     *
     * @return date
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
     * @return BusinessPlan
     */
    public function setPeriodEnd($period_end)
    {
        $this->period_end = $period_end;

        return $this;
    }

    /**
     * Get period_end.
     *
     * @return date
     */
    public function getPeriodEnd()
    {
        return $this->period_end;
    }

    /**
     * Set basic_time_start.
     *
     * @param int $basic_time_start
     *
     * @return BusinessPlan
     */
    public function setBasicTimeStart($basic_time_start)
    {
        $this->basic_time_start = $basic_time_start;

        return $this;
    }

    /**
     * Get basic_time_start.
     *
     * @return int
     */
    public function getBasicTimeStart()
    {
        return $this->basic_time_start;
    }

    /**
     * Set basic_time_end.
     *
     * @param int $basic_time_end
     *
     * @return BusinessPlan
     */
    public function setBasicTimeEnd($basic_time_end)
    {
        $this->basic_time_end = $basic_time_end;

        return $this;
    }

    /**
     * Get basic_time_end.
     *
     * @return int
     */
    public function getBasicTimeEnd()
    {
        return $this->basic_time_end;
    }

    /**
     * Set basic_time_bill.
     *
     * @param int $basic_time_bill
     *
     * @return BusinessPlan
     */
    public function setBasicTimeBill($basic_time_bill)
    {
        $this->basic_time_bill = $basic_time_bill;

        return $this;
    }

    /**
     * Get basic_time_bill.
     *
     * @return int
     */
    public function getBasicTimeBill()
    {
        return $this->basic_time_bill;
    }

    /**
     * Set bill_outside.
     *
     * @param int $bill_outside
     *
     * @return BusinessPlan
     */
    public function setBillOutside($bill_outside)
    {
        $this->bill_outside = $bill_outside;

        return $this;
    }

    /**
     * Get bill_outside.
     *
     * @return int
     */
    public function getBillOutside()
    {
        return $this->bill_outside;
    }

    /**
     * Set basic_time_pay.
     *
     * @param int $basic_time_pay
     *
     * @return BusinessPlan
     */
    public function setBasicTimePay($basic_time_pay)
    {
        $this->basic_time_pay = $basic_time_pay;

        return $this;
    }

    /**
     * Get basic_time_pay.
     *
     * @return int
     */
    public function getBasicTimePay()
    {
        return $this->basic_time_pay;
    }

    /**
     * Set pay_outside.
     *
     * @param int $pay_outside
     *
     * @return BusinessPlan
     */
    public function setPayOutside($pay_outside)
    {
        $this->pay_outside = $pay_outside;

        return $this;
    }

    /**
     * Get pay_outside.
     *
     * @return int
     */
    public function getPayOutside()
    {
        return $this->pay_outside;
    }

    /**
     * Set commission.
     *
     * @param int $commission
     *
     * @return BusinessPlan
     */
    public function setCommission($commission)
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Get commission.
     *
     * @return int
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * Set tax.
     *
     * @param int $tax
     *
     * @return BusinessPlan
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax.
     *
     * @return int
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set transfer_date.
     *
     * @param string $transfer_date
     *
     * @return BusinessPlan
     */
    public function setTransferDate($transfer_date)
    {
        $this->transfer_date = $transfer_date;

        return $this;
    }

    /**
     * Get transfer_date.
     *
     * @return string
     */
    public function getTransferDate()
    {
        return $this->transfer_date;
    }

    /**
     * Set remark.
     *
     * @param string $remark
     *
     * @return BusinessPlan
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
     * Set visible
     *
     * @param boolean $visible
     *
     * @return BusinessPlan
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
     * @return BusinessPlan
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
     * @return BusinessPlan
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
        // ConfigRepository::loadUserByUsername() で Status をチェックしているため、ここでは不要
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
