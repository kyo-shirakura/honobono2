<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * SafetyConfirmation
 *
 * @ORM\Table(name="mtb_safety_confirmation")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\SafetyConfirmationRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class SafetyConfirmation extends \Eccube\Entity\Master\AbstractMasterEntity
{
}
