<?php

namespace Customize\Entity\Master;

class BusinessConfig
{
    const BUSINESS_CONTRACTOR = [
      1 => '法人',
      2 => '個人',
    ];

    const BUSINESS_KIND = [
      1 => '定期',
      2 => '単発',
    ];

    const BUSINESS_REGULAR_TYPE = [
      1 => '毎週',
      2 => '隔週',
      3 => '毎月',
    ];

    const BUSINESS_WEEKLY = [
      1 => '月',
      2 => '火',
      3 => '水',
      4 => '木',
      5 => '金',
      6 => '土',
      7 => '日',
    ];

    const BUSINESS_PAYMENT = [
      1 => '引落し',
      2 => 'クレカ',
      3 => '振込',
    ];

    const INVOICE_SENDER_TYPE = [
      0 => '登録先住所と同じ',
      1 => '異なる住所に送る',
    ];

}
