<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * CancelKind
 *
 * @ORM\Table(name="mtb_cancel_kind")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\CancelKindRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class CancelKind extends \Eccube\Entity\Master\AbstractMasterEntity
{
    /**
    * @var int
     *
     * @ORM\Column(name="customer_rate", type="integer", nullable=true)
     */
    private $customer_rate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="customer_fare", type="boolean", options={"default":false})
     */
    private $customer_fare = true;

    /**
    * @var int
     *
     * @ORM\Column(name="staff_rate", type="integer", nullable=true)
     */
    private $staff_rate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="staff_fare", type="boolean", options={"default":false})
     */
    private $staff_fare = true;


    /**
     * Set customer_rate.
     *
     * @param int $customer_rate
     *
     * @return CancelKind
     */
    public function setCustomerRate($customer_rate)
    {
        $this->customer_rate = $customer_rate;

        return $this;
    }

    /**
     * Get customer_rate.
     *
     * @return int
     */
    public function getCustomerRate()
    {
        return $this->customer_rate;
    }

    /**
     * Set customer_fare.
     *
     * @param boolean $customer_fare
     *
     * @return CancelKind
     */
    public function setCustomerFare($customer_fare)
    {
        $this->customer_fare = $customer_fare;

        return $this;
    }

    /**
     * Get customer_fare.
     *
     * @return boolean
     */
    public function getCustomerFare()
    {
        return $this->customer_fare;
    }

    /**
     * Set staff_rate.
     *
     * @param int $staff_rate
     *
     * @return CancelKind
     */
    public function setStaffRate($staff_rate)
    {
        $this->staff_rate = $staff_rate;

        return $this;
    }

    /**
     * Get staff_rate.
     *
     * @return int
     */
    public function getStaffRate()
    {
        return $this->staff_rate;
    }

    /**
     * Set staff_fare.
     *
     * @param boolean $staff_fare
     *
     * @return CancelKind
     */
    public function setStaffFare($staff_fare)
    {
        $this->staff_fare = $staff_fare;

        return $this;
    }

    /**
     * Get staff_rate.
     *
     * @return boolean
     */
    public function getStaffFare()
    {
        return $this->staff_fare;
    }

}
