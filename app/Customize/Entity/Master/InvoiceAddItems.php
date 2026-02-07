<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * InvoiceAddItems
 *
 * @ORM\Table(name="mtb_invoice_add_items")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\InvoiceAddItemsRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class InvoiceAddItems extends \Eccube\Entity\Master\AbstractMasterEntity
{
}
