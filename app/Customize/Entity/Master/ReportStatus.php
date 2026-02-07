<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * ReportStatus
 *
 * @ORM\Table(name="mtb_report_status")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\ReportStatusRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class ReportStatus extends \Eccube\Entity\Master\AbstractMasterEntity
{
}
