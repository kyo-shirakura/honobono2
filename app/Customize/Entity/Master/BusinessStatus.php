<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * BusinessStatus
 *
 * @ORM\Table(name="mtb_business_status")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\BusinessStatusRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class BusinessStatus extends \Eccube\Entity\Master\AbstractMasterEntity
{
}
