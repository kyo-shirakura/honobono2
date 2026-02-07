<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * PayslipAddItems
 *
 * @ORM\Table(name="mtb_payslip_add_items")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\PayslipAddItemsRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class PayslipAddItems extends \Eccube\Entity\Master\AbstractMasterEntity
{
}
