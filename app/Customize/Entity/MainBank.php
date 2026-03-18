<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * MainBank
 *
 * @ORM\Table(name="dtb_main_bank")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\MainBankRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class MainBank extends AbstractEntity
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
     * @ORM\Column(name="payslip_bank_name", type="string", length=128, nullable=true)
     */
    private $payslip_bank_name;

    /**
     * @var string
     *
     * @ORM\Column(name="payslip_bank_code", type="string", length=16, nullable=true)
     */
    private $payslip_bank_code;

    /**
     * @var string
     *
     * @ORM\Column(name="payslip_bank_branch_name", type="string", length=128, nullable=true)
     */
    private $payslip_bank_branch_name;

    /**
     * @var string
     *
     * @ORM\Column(name="payslip_bank_branch_code", type="string", length=16, nullable=true)
     */
    private $payslip_bank_branch_code;

    /**
     * @var integer
     *
     * @ORM\Column(name="payslip_bank_account_type", type="integer", nullable=true)
     */
    private $payslip_bank_account_type;

    /**
     * @var string
     *
     * @ORM\Column(name="payslip_bank_account_number", type="string", length=16, nullable=true)
     */
    private $payslip_bank_account_number;

    /**
     * @var string
     *
     * @ORM\Column(name="payslip_bank_account_name", type="string", length=128, nullable=true)
     */
    private $payslip_bank_account_name;

    /**
     * @var string
     *
     * @ORM\Column(name="payslip_bank_account_name_kana", type="string", length=128, nullable=true)
     */
    private $payslip_bank_account_name_kana;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_bank_name", type="string", length=128, nullable=true)
     */
    private $invoice_bank_name;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_bank_code", type="string", length=16, nullable=true)
     */
    private $invoice_bank_code;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_bank_branch_name", type="string", length=128, nullable=true)
     */
    private $invoice_bank_branch_name;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_bank_branch_code", type="string", length=16, nullable=true)
     */
    private $invoice_bank_branch_code;

    /**
     * @var integer
     *
     * @ORM\Column(name="invoice_bank_account_type", type="integer", nullable=true)
     */
    private $invoice_bank_account_type;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_bank_account_number", type="string", length=16, nullable=true)
     */
    private $invoice_bank_account_number;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_bank_account_name", type="string", length=128, nullable=true)
     */
    private $invoice_bank_account_name;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_bank_account_name_kana", type="string", length=128, nullable=true)
     */
    private $invoice_bank_account_name_kana;

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
     * Set payslip_bank_name.
     *
     * @param string $payslip_bank_name
     *
     * @return string
     */
    public function setPayslipBankName($payslip_bank_name)
    {
        $this->payslip_bank_name = $payslip_bank_name;

        return $this;
    }

    /**
     * Get payslip_bank_name.
     *
     * @return string
     */
    public function getPayslipBankName()
    {
        return $this->payslip_bank_name;
    }

    /**
     * Set payslip_bank_code.
     *
     * @param string $payslip_bank_code
     *
     * @return string
     */
    public function setPayslipBankCode($payslip_bank_code)
    {
        $this->payslip_bank_code = $payslip_bank_code;

        return $this;
    }

    /**
     * Get payslip_bank_code.
     *
     * @return string
     */
    public function getPayslipBankCode()
    {
        return $this->payslip_bank_code;
    }

    /**
     * Set payslip_bank_branch_name.
     *
     * @param string $payslip_bank_branch_name
     *
     * @return string
     */
    public function setPayslipBankBranchName($payslip_bank_branch_name)
    {
        $this->payslip_bank_branch_name = $payslip_bank_branch_name;

        return $this;
    }

    /**
     * Get payslip_bank_branch_name.
     *
     * @return string
     */
    public function getPayslipBankBranchName()
    {
        return $this->payslip_bank_branch_name;
    }

    /**
     * Set payslip_bank_branch_code.
     *
     * @param string $payslip_bank_branch_code
     *
     * @return string
     */
    public function setPayslipBankBranchCode($payslip_bank_branch_code)
    {
        $this->payslip_bank_branch_code = $payslip_bank_branch_code;

        return $this;
    }

    /**
     * Get payslip_bank_branch_code.
     *
     * @return string
     */
    public function getPayslipBankBranchCode()
    {
        return $this->payslip_bank_branch_code;
    }

    /**
     * Set payslip_bank_account_type.
     *
     * @param integer|null $purchasePrice
     *
     * @return integer
     */
    public function setPayslipBankAccountType($payslip_bank_account_type = null)
    {
        $this->payslip_bank_account_type = $payslip_bank_account_type;

        return $this;
    }

    /**
     * Get payslip_bank_account_type.
     *
     * @return integer
     */
    public function getPayslipBankAccountType()
    {
        return $this->payslip_bank_account_type;
    }

    /**
     * Set payslip_bank_account_number.
     *
     * @param string $payslip_bank_account_number
     *
     * @return string
     */
    public function setPayslipBankAccountNumber($payslip_bank_account_number)
    {
        $this->payslip_bank_account_number = $payslip_bank_account_number;

        return $this;
    }

    /**
     * Get payslip_bank_account_number.
     *
     * @return string
     */
    public function getPayslipBankAccountNumber()
    {
        return $this->payslip_bank_account_number;
    }

    /**
     * Set payslip_bank_account_name.
     *
     * @param string $payslip_bank_account_name
     *
     * @return string
     */
    public function setPayslipBankAccountName($payslip_bank_account_name)
    {
        $this->payslip_bank_account_name = $payslip_bank_account_name;

        return $this;
    }

    /**
     * Get payslip_bank_account_name.
     *
     * @return string
     */
    public function getPayslipBankAccountName()
    {
        return $this->payslip_bank_account_name;
    }

    /**
     * Set payslip_bank_account_name_kana.
     *
     * @param string $payslip_bank_account_name_kana
     *
     * @return string
     */
    public function setPayslipBankAccountNameKana($payslip_bank_account_name_kana)
    {
        $this->payslip_bank_account_name_kana = $payslip_bank_account_name_kana;

        return $this;
    }

    /**
     * Get payslip_bank_account_name_kana.
     *
     * @return string
     */
    public function getPayslipBankAccountNameKana()
    {
        return $this->payslip_bank_account_name_kana;
    }

    /**
     * Set payslip_bank_name.
     *
     * @param string $payslip_bank_name
     *
     * @return string
     */
    public function setInvoiceBankName($payslip_bank_name)
    {
        $this->payslip_bank_name = $payslip_bank_name;

        return $this;
    }

    /**
     * Get payslip_bank_name.
     *
     * @return string
     */
    public function getInvoiceBankName()
    {
        return $this->payslip_bank_name;
    }

    /**
     * Set payslip_bank_code.
     *
     * @param string $payslip_bank_code
     *
     * @return string
     */
    public function setInvoiceBankCode($payslip_bank_code)
    {
        $this->payslip_bank_code = $payslip_bank_code;

        return $this;
    }

    /**
     * Get payslip_bank_code.
     *
     * @return string
     */
    public function getInvoiceBankCode()
    {
        return $this->payslip_bank_code;
    }

    /**
     * Set payslip_bank_branch_name.
     *
     * @param string $payslip_bank_branch_name
     *
     * @return string
     */
    public function setInvoiceBankBranchName($payslip_bank_branch_name)
    {
        $this->payslip_bank_branch_name = $payslip_bank_branch_name;

        return $this;
    }

    /**
     * Get payslip_bank_branch_name.
     *
     * @return string
     */
    public function getInvoiceBankBranchName()
    {
        return $this->payslip_bank_branch_name;
    }

    /**
     * Set payslip_bank_branch_code.
     *
     * @param string $payslip_bank_branch_code
     *
     * @return string
     */
    public function setInvoiceBankBranchCode($payslip_bank_branch_code)
    {
        $this->payslip_bank_branch_code = $payslip_bank_branch_code;

        return $this;
    }

    /**
     * Get payslip_bank_branch_code.
     *
     * @return string
     */
    public function getInvoiceBankBranchCode()
    {
        return $this->payslip_bank_branch_code;
    }

    /**
     * Set payslip_bank_account_type.
     *
     * @param integer|null $purchasePrice
     *
     * @return integer
     */
    public function setInvoiceBankAccountType($payslip_bank_account_type = null)
    {
        $this->payslip_bank_account_type = $payslip_bank_account_type;

        return $this;
    }

    /**
     * Get payslip_bank_account_type.
     *
     * @return integer
     */
    public function getInvoiceBankAccountType()
    {
        return $this->payslip_bank_account_type;
    }

    /**
     * Set payslip_bank_account_number.
     *
     * @param string $payslip_bank_account_number
     *
     * @return string
     */
    public function setInvoiceBankAccountNumber($payslip_bank_account_number)
    {
        $this->payslip_bank_account_number = $payslip_bank_account_number;

        return $this;
    }

    /**
     * Get payslip_bank_account_number.
     *
     * @return string
     */
    public function getInvoiceBankAccountNumber()
    {
        return $this->payslip_bank_account_number;
    }

    /**
     * Set payslip_bank_account_name.
     *
     * @param string $payslip_bank_account_name
     *
     * @return string
     */
    public function setInvoiceBankAccountName($payslip_bank_account_name)
    {
        $this->payslip_bank_account_name = $payslip_bank_account_name;

        return $this;
    }

    /**
     * Get payslip_bank_account_name.
     *
     * @return string
     */
    public function getInvoiceBankAccountName()
    {
        return $this->payslip_bank_account_name;
    }

    /**
     * Set payslip_bank_account_name_kana.
     *
     * @param string $payslip_bank_account_name_kana
     *
     * @return string
     */
    public function setInvoiceBankAccountNameKana($payslip_bank_account_name_kana)
    {
        $this->payslip_bank_account_name_kana = $payslip_bank_account_name_kana;

        return $this;
    }

    /**
     * Get payslip_bank_account_name_kana.
     *
     * @return string
     */
    public function getInvoiceBankAccountNameKana()
    {
        return $this->payslip_bank_account_name_kana;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return MainBank
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
     * @return MainBank
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
     * @return MainBank
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
        // MainBankRepository::loadUserByUsername() で Status をチェックしているため、ここでは不要
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
