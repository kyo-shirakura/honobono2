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

}
