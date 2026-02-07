<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Eccube\Entity\AbstractEntity;

/**
 * StaffStatus
 *
 * @ORM\Table(name="mtb_staff_status")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\StaffStatusRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class StaffStatus extends \Eccube\Entity\Master\AbstractMasterEntity
{
    const STATE = [
      1 => '仮契約',
      2 => '本契約',
      3 => '稼働中',
      8 => '問題あり',
      9 => '連絡取れず未契約',
    ];

}
