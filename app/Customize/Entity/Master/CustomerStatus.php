<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

class CustomerStatus extends \Eccube\Entity\Master\AbstractMasterEntity
{
    /**
     * 仮会員.
     *
     * @deprecated
     */
    public const NONACTIVE = 1;

    /**
     * 本会員.
     *
     * @deprecated
     */
    public const ACTIVE = 5;

    /**
     * 仮会員.
     */
    public const PROVISIONAL = 1;

    /**
     * 本会員
     */
//    public const REGULAR = 5;

    /**
     * 退会
     */
    public const WITHDRAWING = 9;

    /** 至急初回連絡 */
    public const ARRANGE_CALL = 1;

    /** 至急スタッフ手配 */
    public const ARRANGE_STAFF = 2;

    /** スタッフ手配できず（辞退） */
    public const NOT_ARRANGE_STAFF = 3;

    /** お試し日時確定 */
    public const TRIAL = 4;

    /** 定期契約中 */
    public const REGULAR = 5;

    /** 単発のみ */
    public const SPOT = 6;

}
