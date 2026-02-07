<?php

namespace Customize\Entity;

use Eccube\Entity\Customer;
use Customize\Entity\Business;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Customize\Entity\Staff;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Invoice
 *
 * @ORM\Table(name="dtb_invoice_issued")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\InvoiceIssuedRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class InvoiceIssued extends AbstractEntity
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
     * @var \Eccube\Entity\Customer
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     * })
     */
    private $Customer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="issue_ym", type="datetimetz")
     */
    private $issue_ym;

    /**
    * @var int
     *
     * @ORM\Column(name="kind_id", type="integer", nullable=true)
     */
    private $kind_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="payment_type", type="integer", nullable=true)
     */
    private $payment_type;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount_total", type="integer", nullable=true)
     */
    private $amount_total;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount_tax", type="integer", nullable=true)
     */
    private $amount_tax;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount_basic", type="integer", nullable=true)
     */
    private $amount_basic;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount_overtime", type="integer", nullable=true)
     */
    private $amount_overtime;

    /**
     * @var int|null
     *
     * @ORM\Column(name="amount_advance", type="integer", nullable=true)
     */
    private $amount_advance;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount_fare", type="integer", nullable=true)
     */
    private $amount_fare;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", nullable=true)
     */
    private $remark;

    /**
     * @var int|null
     *
     * @ORM\Column(name="sendmail", type="integer", nullable=true)
     */
    private $sendmail;

    /**
     * @var int|null
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var int|null
     *
     * @ORM\Column(name="revision", type="integer", nullable=false, options={"default":1})
     */
    private $revision;

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
     * @param Customer $Customer
     *
     * @return Customer
     */
    public function setCustomer(Customer $Customer = null)
    {
        $this->Customer = $Customer;

        return $this;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->Customer;
    }

    /**
     * @param Business $Business
     *
     * @return Business
     */
    public function setBusiness(Business $Business = null)
    {
        $this->Business = $Business;

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
     * Set issue_ym.
     *
     * @param datetimetz $issue_ym
     *
     * @return datetimetz
     */
    public function setIssueYm($issue_ym)
    {
        $this->issue_ym = $issue_ym;

        return $this;
    }

    /**
     * Get issue_ym.
     *
     * @return datetimetz
     */
    public function getIssueYm()
    {
        return $this->issue_ym;
    }

    /**
     * Set kind_id.
     *
     * @param integer $kind_id
     *
     * @return integer
     */
    public function setKindId($kind_id)
    {
        $this->kind_id = $kind_id;

        return $this;
    }

    /**
     * Get kind_id.
     *
     * @return integer
     */
    public function getKindId()
    {
        return $this->kind_id;
    }

    /**
     * Set payment_type.
     *
     * @param integer|null $payment_type
     *
     * @return integer
     */
    public function setPaymentType($payment_type = null)
    {
        $this->payment_type = $payment_type;

        return $this;
    }

    /**
     * Get payment_type.
     *
     * @return integer|null
     */
    public function getPaymentType()
    {
        return $this->payment_type;
    }

    /**
     * Set amount_total.
     *
     * @param integer|null $amount_total
     *
     * @return integer
     */
    public function setAmountTotal($amount_total = null)
    {
        $this->amount_total = $amount_total;

        return $this;
    }

    /**
     * Get amount_total.
     *
     * @return integer|null
     */
    public function getAmountTotal()
    {
        return $this->amount_total;
    }

    /**
     * Set amount_tax.
     *
     * @param integer|null $amount_tax
     *
     * @return integer
     */
    public function setAmountTax($amount_tax = null)
    {
        $this->amount_tax = $amount_tax;

        return $this;
    }

    /**
     * Get amount_tax.
     *
     * @return integer|null
     */
    public function getAmountTax()
    {
        return $this->amount_tax;
    }

    /**
     * Set amount_basic.
     *
     * @param integer|null $amount_basic
     *
     * @return integer
     */
    public function setAmountBasic($amount_basic = null)
    {
        $this->amount_basic = $amount_basic;

        return $this;
    }

    /**
     * Get amount_basic.
     *
     * @return integer|null
     */
    public function getAmountBasic()
    {
        return $this->amount_basic;
    }

    /**
     * Set amount_overtime.
     *
     * @param integer|null $amount_overtime
     *
     * @return integer
     */
    public function setAmountOvertime($amount_overtime = null)
    {
        $this->amount_overtime = $amount_overtime;

        return $this;
    }

    /**
     * Get amount_overtime.
     *
     * @return integer|null
     */
    public function getAmountOvertime()
    {
        return $this->amount_overtime;
    }

    /**
     * Set amount_advance.
     *
     * @param integer|null $amount_advance
     *
     * @return integer
     */
    public function setAmountAdvance($amount_advance = null)
    {
        $this->amount_advance = $amount_advance;

        return $this;
    }

    /**
     * Get amount_advance.
     *
     * @return integer|null
     */
    public function getAmountAdvance()
    {
        return $this->amount_advance;
    }

    /**
     * Set amount_fare.
     *
     * @param integer|null $amount_fare
     *
     * @return integer
     */
    public function setAmountFare($amount_fare = null)
    {
        $this->amount_fare = $amount_fare;

        return $this;
    }

    /**
     * Get amount_fare.
     *
     * @return integer|null
     */
    public function getAmountFare()
    {
        return $this->amount_fare;
    }

    /**
     * Set message.
     *
     * @param string $message
     *
     * @return string
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set remark.
     *
     * @param string $remark
     *
     * @return string
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Set revision.
     *
     * @param integer $revision
     *
     * @return integer
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;

        return $this;
    }

    /**
     * Set status.
     *
     * @param integer $status
     *
     * @return integer
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get revision.
     *
     * @return integer
     */
    public function getRevision()
    {
        return $this->revision;
    }

    /**
     * Set createDate.
     *
     * @param \DateTime $createDate
     *
     * @return DateTime
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
     * @return DateTime
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
