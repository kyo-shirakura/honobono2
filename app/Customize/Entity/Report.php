<?php

namespace Customize\Entity;

use Customize\Entity\Business;
use Customize\Entity\Staff;
use Customize\Entity\Master\CancelKind;
use Customize\Entity\Master\ReportStatus;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Eccube\Entity\Customer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Report
 *
 * @ORM\Table(name="dtb_report")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\ReportRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Report extends AbstractEntity
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
     * @var \Customize\Entity\Staff
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Staff")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="staff_id", referencedColumnName="id")
     * })
     */
    private $Staff;

    /**
     * @var \Eccube\Entity\Customer
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * })
     */
    private $Customer;

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
     * @var \Customize\Entity\Master\ReportStatus
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\ReportStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $Status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="working_day", type="string")
     */
    private $working_day;

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
     * @var string|null
     *
     * @ORM\Column(name="working_time_total", type="string", length=16, nullable=true)
     */
    private $working_time_total;

    /**
     * @var string|null
     *
     * @ORM\Column(name="work_details", type="string", length=1024, nullable=true)
     */
    private $work_details;

    /**
     * @var string|null
     *
     * @ORM\Column(name="safety_confirmation", type="string", length=1024, nullable=true)
     */
    private $safety_confirmation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="work_other_check", type="boolean", options={"default":false}, nullable=true)
     */
     private $work_other_check;

    /**
     * @var string|null
     *
     * @ORM\Column(name="work_other_text", type="string", length=256, nullable=true)
     */
    private $work_other_text;

    /**
     * @var int|null
     *
     * @ORM\Column(name="payment_deposit", type="integer", nullable=true)
     */
    private $payment_deposit;

    /**
     * @var int|null
     *
     * @ORM\Column(name="payment_advance", type="integer", nullable=true)
     */
    private $payment_advance;

    /**
     * @var int|null
     *
     * @ORM\Column(name="payment_change", type="integer", nullable=true)
     */
    private $payment_change;

    /**
     * @var string|null
     *
     * @ORM\Column(name="payment_receipt", type="string", length=256, nullable=true)
     */
    private $payment_receipt;

    /**
     * @var int|null
     *
     * @ORM\Column(name="fare", type="integer", nullable=true)
     */
    private $fare;

    /**
     * @var string|null
     *
     * @ORM\Column(name="remark", type="string", length=1024, nullable=true)
     */
    private $remark;

    /**
     * @var int|null
     *
     * @ORM\Column(name="issue_status", type="integer", nullable=true)
     */
    private $issue_status;

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
     * @var \Customize\Entity\Master\CancelKind
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\CancelKind")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cancelkind_id", referencedColumnName="id")
     * })
     */
    private $CancelKind;


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
     * Set remark.
     *
     * @param string $remark
     *
     * @return Report
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
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->Customer;
    }

    /**
     * @param Customer $Customer
     *
     * @return Report
     */
    public function setCustomer(Customer $Customer = null)
    {
        $this->Customer = $Customer;

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
     * @return Report
     */
    public function setStaff(Staff $Staff = null)
    {
        $this->Staff = $Staff;

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
     * @return Report
     */
    public function setBusiness(Business $Business = null)
    {
        $this->Business = $Business;

        return $this;
    }

    /**
     * Get status.
     *
     * @return ReportStatus|null
     */
    public function getStatus(): ?ReportStatus
    {
        return $this->Status;
    }

    /**
     * Set status.
     *
     * @param Status|null $status
     *
     * @return Report
     */
    public function setStatus(ReportStatus $status = null)
    {
        $this->Status = $status;

        return $this;
    }

    /**
     * @return CancelKind|null
     */
    public function getCancelKind(): ?CancelKind
    {
        return $this->CancelKind;
    }

    /**
     * @param CancelKind $CancelKind
     *
     * @return Report
     */
    public function setCancelKind(CancelKind $CancelKind = null)
    {
        $this->CancelKind = $CancelKind;

        return $this;
    }

    /**
     * Set working_day.
     *
     * @param string $working_day
     *
     * @return Report
     */
    public function setWorkingDay($working_day)
    {
        $this->working_day = $working_day;

        return $this;
    }

    /**
     * Get working_day.
     *
     * @return string
     */
    public function getWorkingDay()
    {
        return $this->working_day;
    }

    /**
     * Set working_time_start.
     *
     * @param string $working_time_start
     *
     * @return Report
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
     * @return Report
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
     * @return Report
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
     * Set working_time_total.
     *
     * @param string $working_time_total
     *
     * @return Report
     */
    public function setWorkingTimeTotal($working_time_total)
    {
        $this->working_time_total = $working_time_total;

        return $this;
    }

    /**
     * Get working_time_total.
     *
     * @return string
     */
    public function getWorkingTimeTotal()
    {
        return $this->working_time_total;
    }

    /**
     * Set work_details.
     *
     * @param string $work_details
     *
     * @return Report
     */
    public function setWorkDetails($work_details)
    {
        $this->work_details = $work_details;

        return $this;
    }

    /**
     * Get work_details.
     *
     * @return string
     */
    public function getWorkDetails()
    {
        return $this->work_details;
    }

    /**
     * Set work_other_check.
     *
     * @param string $work_other_check
     *
     * @return Report
     */
    public function setWorkOtherCheck($work_other_check)
    {
        $this->work_other_check = $work_other_check;

        return $this;
    }

    /**
     * Get work_other_text.
     *
     * @return string
     */
    public function getWorkOtherCheck()
    {
        return $this->work_other_check;
    }


    /**
     * Set work_other_text.
     *
     * @param string $work_other_text
     *
     * @return Report
     */
    public function setWorkOtherText($work_other_text)
    {
        $this->work_other_text = $work_other_text;

        return $this;
    }

    /**
     * Get work_other_text.
     *
     * @return string
     */
    public function getWorkOtherText()
    {
        return $this->work_other_text;
    }


    /**
     * Set safety_confirmation.
     *
     * @param string $safety_confirmation
     *
     * @return Report
     */
    public function setSafetyConfirmation($safety_confirmation)
    {
        $this->safety_confirmation = $safety_confirmation;

        return $this;
    }

    /**
     * Get safety_confirmation.
     *
     * @return string
     */
    public function getSafetyConfirmation()
    {
        return $this->safety_confirmation;
    }

    /**
     * Set payment_deposit.
     *
     * @param string $payment_deposit
     *
     * @return Report
     */
    public function setPaymentDeposit($payment_deposit)
    {
        $this->payment_deposit = $payment_deposit;

        return $this;
    }

    /**
     * Get payment_deposit.
     *
     * @return string
     */
    public function getPaymentDeposit()
    {
        return $this->payment_deposit;
    }

    /**
     * Set payment_advance.
     *
     * @param string $payment_advance
     *
     * @return Report
     */
    public function setPaymentAdvance($payment_advance)
    {
        $this->payment_advance = $payment_advance;

        return $this;
    }

    /**
     * Get payment_advance.
     *
     * @return string
     */
    public function getPaymentAdvance()
    {
        return $this->payment_advance;
    }

    /**
     * Set payment_change.
     *
     * @param string $payment_change
     *
     * @return Report
     */
    public function setPaymentChange($payment_change)
    {
        $this->payment_change = $payment_change;

        return $this;
    }

    /**
     * Get payment_change.
     *
     * @return string
     */
    public function getPaymentChange()
    {
        return $this->payment_change;
    }

    /**
     * Set payment_receipt.
     *
     * @param string $payment_receipt
     *
     * @return Report
     */
    public function setPaymentReceipt($payment_receipt)
    {
        $this->payment_receipt = $payment_receipt;

        return $this;
    }

    /**
     * Get payment_receipt.
     *
     * @return string
     */
    public function getPaymentReceipt()
    {
        return $this->payment_receipt;
    }

    /**
     * Set fare.
     *
     * @param int $fare
     *
     * @return Report
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
     * Set issue_status.
     *
     * @param int $issue_status
     *
     * @return Report
     */
    public function setIssueStatus($issue_status)
    {
        $this->issue_status = $issue_status;

        return $this;
    }

    /**
     * Get issue_status.
     *
     * @return int
     */
    public function getIssueStatus()
    {
        return $this->issue_status;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Report
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
     * @return Report
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
     * @return Report
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
        // CustomerRepository::loadUserByUsername() で Status をチェックしているため、ここでは不要
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
