<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * Services
 *
 * @ORM\Table(name="mtb_services")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\ServicesRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Services extends \Eccube\Entity\Master\AbstractMasterEntity
{
}
