<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * InvoiceStatus
 *
 * @ORM\Table(name="mtb_invoice_status")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\InvoiceStatusRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class InvoiceStatus extends \Eccube\Entity\Master\AbstractMasterEntity
{
}
