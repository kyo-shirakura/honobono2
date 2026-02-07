<?php

namespace Customize\Entity;

use Customize\Entity\Business;
use Customize\Entity\Staff;
use Customize\Entity\Master\BusinessStatus;
use Customize\Entity\Master\BusinessConfig;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Eccube\Entity\Customer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Business
 *
 * @ORM\Table(name="dtb_business")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\BusinessRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Business extends AbstractEntity
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

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
     * @var string|null
     *
     * @ORM\Column(name="remark", type="string", length=1024, nullable=true)
     */
    private $remark;

    /**
     * @var int
     *
     * @ORM\Column(name="invoice_issued", type="integer", nullable=true)
     */
    private $invoice_issued;

    /**
     * @var int
     *
     * @ORM\Column(name="payslip_issued", type="integer", nullable=true)
     */
    private $payslip_issued;

    /**
     * @var integer
     *
     * @ORM\Column(name="invoice_sender_type", type="integer", nullable=true)
     */
    private $invoice_sender_type;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sender_name", type="string", length=255, nullable=true)
     */
    private $sender_name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="company_name", type="string", length=255, nullable=true)
     */
    private $company_name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sender_postal_code", type="string", length=8, nullable=true)
     */
    private $sender_postal_code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sender_addr01", type="string", length=255, nullable=true)
     */
    private $sender_addr01;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sender_addr02", type="string", length=255, nullable=true)
     */
    private $sender_addr02;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sender_phone_number", type="string", length=14, nullable=true)
     */
    private $sender_phone_number;

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
     * @return Business
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set remark.
     *
     * @param string $remark
     *
     * @return Business
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
     * @return Business
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
     * @return Business
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
     * @return Business
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
     * @return Business
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
     * Set kind_id.
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
     * Get kind_id.
     *
     * @return int
     */
    public function getKindId()
    {
        return $this->kind_id;
    }

    /**
     * Set fare_ex.
     *
     * @param int $fare_ex
     *
     * @return Business
     */
    public function setFareEx($fare_ex)
    {
        $this->fare_ex = $fare_ex;

        return $this;
    }

    /**
     * Get fare_ex.
     *
     * @return int
     */
    public function getFareEx()
    {
        return $this->fare_ex;
    }

    /**
     * Get status.
     *
     * @return Business
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * @param Status $Status
     *
     * @return Business
     */
    public function setStatus(BusinessStatus $Status = null)
    {
        $this->Status = $Status;

        return $this;
    }

    /**
     * Set invoice_issued.
     *
     * @param string $invoice_issued
     *
     * @return Business
     */
    public function setInvoiceIssued($invoice_issued)
    {
        $this->invoice_issued = $invoice_issued;

        return $this;
    }

    /**
     * Get invoice_issued.
     *
     * @return string
     */
    public function getInvoiceIssued()
    {
        return $this->invoice_issued;
    }

    /**
     * Set payslip_issued.
     *
     * @param string $payslip_issued
     *
     * @return Business
     */
    public function setPayslipIssued($payslip_issued)
    {
        $this->payslip_issued = $payslip_issued;

        return $this;
    }

    /**
     * Get payslip_issued.
     *
     * @return string
     */
    public function getPayslipIssued()
    {
        return $this->payslip_issued;
    }

    /**
     * Set invoice_sender_type.
     *
     * @param integer|null $purchasePrice
     *
     * @return integer
     */
    public function setInvoiceSenderType($invoice_sender_type = null)
    {
        $this->invoice_sender_type = $invoice_sender_type;

        return $this;
    }

    /**
     * Get invoice_sender_type.
     *
     * @return integer|null
     */
    public function getInvoiceSenderType()
    {
        return $this->invoice_sender_type;
    }

    /**
     * Set sender_name.
     *
     * @param string|null $sender_name
     *
     * @return Business
     */
    public function setSenderName($sender_name = null)
    {
        $this->sender_name = $sender_name;

        return $this;
    }

    /**
     * Get sender_name.
     *
     * @return string|null
     */
    public function getSenderName()
    {
        return $this->sender_name;
    }

    /**
     * Set sender_postal_code.
     *
     * @param string|null $sender_postal_code
     *
     * @return Business
     */
    public function setSenderPostalCode($sender_postal_code = null)
    {
        $this->sender_postal_code = $sender_postal_code;

        return $this;
    }

    /**
     * Get sender_postal_code.
     *
     * @return string|null
     */
    public function getSenderPostalCode()
    {
        return $this->sender_postal_code;
    }

    /**
     * Set sender_addr01.
     *
     * @param string|null $sender_addr01
     *
     * @return Business
     */
    public function setSenderAddr01($sender_addr01 = null)
    {
        $this->sender_addr01 = $sender_addr01;

        return $this;
    }

    /**
     * Get sender_addr01.
     *
     * @return string|null
     */
    public function getSenderAddr01()
    {
        return $this->sender_addr01;
    }

    /**
     * Set sender_addr02.
     *
     * @param string|null $sender_addr02
     *
     * @return Business
     */
    public function setSenderAddr02($sender_addr02 = null)
    {
        $this->sender_addr02 = $sender_addr02;

        return $this;
    }

    /**
     * Get sender_addr02.
     *
     * @return string|null
     */
    public function getSenderAddr02()
    {
        return $this->sender_addr02;
    }

    /**
     * Set sender_phone_number.
     *
     * @param string|null $sender_phone_number
     *
     * @return Business
     */
    public function setSenderPhoneNumber($sender_phone_number = null)
    {
        $this->sender_phone_number = $sender_phone_number;

        return $this;
    }

    /**
     * Get sender_phone_number.
     *
     * @return string|null
     */
    public function getSenderPhoneNumber()
    {
        return $this->sender_phone_number;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Business
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
     * @return Business
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
     * @return Business
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
