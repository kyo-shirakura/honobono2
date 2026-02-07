<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * Kind
 *
 * @ORM\Table(name="mtb_kind")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\KindRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Kind extends \Eccube\Entity\Master\AbstractMasterEntity
{
}
