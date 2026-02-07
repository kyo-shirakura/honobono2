<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * PayslipStatus
 *
 * @ORM\Table(name="mtb_payslip_status")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\PayslipStatusRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class PayslipStatus extends \Eccube\Entity\Master\AbstractMasterEntity
{
}
