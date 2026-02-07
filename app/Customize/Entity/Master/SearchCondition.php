<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Eccube\Entity\AbstractEntity;

/**
 * SearchCondition
 *
 * @ORM\Table(name="mtb_search_condition")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\SearchConditionRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class SearchCondition extends \Eccube\Entity\Master\AbstractMasterEntity
{
    const SPECIAL_TREATMENT_GROUPS = [
      1 => '2000万円以上も可',
      2 => '院長職',
      3 => '当直なし',
      4 => '週4日勤務も可',
      5 => '土日・祝日休みも可',
      6 => 'クリニック',
      7 => '研修可',
    ];

    const PARTTIME_STYLE_GROUPS = [
      1 => '日勤（終日）',
      2 => '日勤（午前）',
      3 => '日勤（午後）',
      4 => '夜診',
      5 => '当直',
      6 => '日当直',
    ];

    const PARTTIME_WEEKDAY_GROUPS = [
      1 => '月曜日',
      2 => '火曜日',
      3 => '水曜日',
      4 => '木曜日',
      5 => '金曜日',
      6 => '土曜日',
      7 => '日曜日',
    ];

}
