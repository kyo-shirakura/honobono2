<?php

namespace Customize\Entity;

use Customize\Entity\Master\CustomerType;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;
use Eccube\Common\Constant;

/**
* @EntityExtension("Eccube\Entity\Customer")
*/
trait CustomerTrait
{
    /**
     * @var integer
     *
     * @ORM\Column(name="payment_type", type="integer", nullable=true)
     */
    private $payment_type;

    /**
     * @var string
     *
     * @ORM\Column(name="transferee_account", type="string", length=255, nullable=true)
     */
    private $transferee_account;


    /**
     * @var string
     *
     * @ORM\Column(name="bank_name", type="string", length=128, nullable=true)
     */
    private $bank_name;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_code", type="string", length=16, nullable=true)
     */
    private $bank_code;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_branch_name", type="string", length=128, nullable=true)
     */
    private $bank_branch_name;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_branch_code", type="string", length=16, nullable=true)
     */
    private $bank_branch_code;

    /**
     * @var integer
     *
     * @ORM\Column(name="bank_account_type", type="integer", nullable=true)
     */
    private $bank_account_type;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_account_number", type="string", length=16, nullable=true)
     */
    private $bank_account_number;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_account_name", type="string", length=128, nullable=true)
     */
    private $bank_account_name;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_account_name_kana", type="string", length=128, nullable=true)
     */
    private $bank_account_name_kana;

    /**
     * Set payment_type.
     *
     * @param integer|null $purchasePrice
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
     * Set transferee_account.
     *
     * @param string $transferee_account
     *
     * @return string
     */
    public function setTransfereeAccount($transferee_account)
    {
        $this->transferee_account = $transferee_account;

        return $this;
    }

    /**
     * Get transferee_account.
     *
     * @return string
     */
    public function getTransfereeAccount()
    {
        return $this->transferee_account;
    }

    /**
     * Set bank_name.
     *
     * @param string $bank_name
     *
     * @return string
     */
    public function setBankName($bank_name)
    {
        $this->bank_name = $bank_name;

        return $this;
    }

    /**
     * Get bank_name.
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bank_name;
    }

    /**
     * Set bank_code.
     *
     * @param string $bank_code
     *
     * @return string
     */
    public function setBankCode($bank_code)
    {
        $this->bank_code = $bank_code;

        return $this;
    }

    /**
     * Get bank_code.
     *
     * @return string
     */
    public function getBankCode()
    {
        return $this->bank_code;
    }

    /**
     * Set bank_branch_name.
     *
     * @param string $bank_branch_name
     *
     * @return string
     */
    public function setBankBranchName($bank_branch_name)
    {
        $this->bank_branch_name = $bank_branch_name;

        return $this;
    }

    /**
     * Get bank_branch_name.
     *
     * @return string
     */
    public function getBankBranchName()
    {
        return $this->bank_branch_name;
    }

    /**
     * Set bank_branch_code.
     *
     * @param string $bank_branch_code
     *
     * @return string
     */
    public function setBankBranchCode($bank_branch_code)
    {
        $this->bank_branch_code = $bank_branch_code;

        return $this;
    }

    /**
     * Get bank_branch_code.
     *
     * @return string
     */
    public function getBankBranchCode()
    {
        return $this->bank_branch_code;
    }

    /**
     * Set bank_account_type.
     *
     * @param integer|null $purchasePrice
     *
     * @return integer
     */
    public function setBankAccountType($bank_account_type = null)
    {
        $this->bank_account_type = $bank_account_type;

        return $this;
    }

    /**
     * Get bank_account_type.
     *
     * @return integer
     */
    public function getBankAccountType()
    {
        return $this->bank_account_type;
    }

    /**
     * Set bank_account_number.
     *
     * @param string $bank_account_number
     *
     * @return string
     */
    public function setBankAccountNumber($bank_account_number)
    {
        $this->bank_account_number = $bank_account_number;

        return $this;
    }

    /**
     * Get bank_account_number.
     *
     * @return string
     */
    public function getBankAccountNumber()
    {
        return $this->bank_account_number;
    }

    /**
     * Set bank_account_name.
     *
     * @param string $bank_account_name
     *
     * @return string
     */
    public function setBankAccountName($bank_account_name)
    {
        $this->bank_account_name = $bank_account_name;

        return $this;
    }

    /**
     * Get bank_account_name.
     *
     * @return string
     */
    public function getBankAccountName()
    {
        return $this->bank_account_name;
    }

    /**
     * Set bank_account_name_kana.
     *
     * @param string $bank_account_name_kana
     *
     * @return string
     */
    public function setBankAccountNameKana($bank_account_name_kana)
    {
        $this->bank_account_name_kana = $bank_account_name_kana;

        return $this;
    }

    /**
     * Get bank_account_name_kana.
     *
     * @return string
     */
    public function getBankAccountNameKana()
    {
        return $this->bank_account_name_kana;
    }

}
