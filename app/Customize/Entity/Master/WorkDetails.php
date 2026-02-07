<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * WorkDetails
 *
 * @ORM\Table(name="mtb_work_details")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\WorkDetailsRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @ORM\Embeddable
 */
class WorkDetails extends \Eccube\Entity\Master\AbstractMasterEntity
{
}
