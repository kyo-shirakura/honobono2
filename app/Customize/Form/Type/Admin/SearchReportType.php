<?php

namespace Customize\Form\Type\Admin;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\Customer;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Eccube\Form\Type\Master\SexType;
use Eccube\Form\Type\Master\PrefType;
use Customize\Entity\Report;
use Customize\Entity\Staff;
use Customize\Form\Type\MonthType;
use Customize\Form\Type\Admin\ReportType;
use Customize\Form\Type\Master\ServicesType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchReportType extends AbstractType
{
  /**
   * @var EccubeConfig
   */
  protected $eccubeConfig;

  public function __construct(EccubeConfig $eccubeConfig)
  {
      $this->eccubeConfig = $eccubeConfig;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder
        ->add('multi', TextType::class, [
            'label' => 'admin.report.multi_search_label',
            'required' => false,
            'constraints' => [
              new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
            ],
        ])
        ->add('status', ChoiceType::class, [
            'label' => 'admin.report.status',
            'choices' => [
                '承認済み' => '1',
                '未承認' => '0'
            ],
            'expanded' => true,
            'multiple' => true,
        ])
        ->add('business_name', TextType::class, [
            'label' => 'admin.report.business_name',
            'required' => false,
            //'attr' => ['readonly' => 'readonly'],
        ])
        ->add('Staff', EntityType::class, [
            'label' => 'admin.business.staff',
            'required' => false,
            'class' => Staff::class,
            'choice_label' => function ($data) {
                  return $data->getName01(). ' '. $data->getName02();
            },
            "placeholder" => 'admin.common.select.placeholder',
//            'eccube_form_options' => [
//                'auto_render' => true
//            ]
        ])
        ->add('Customer', EntityType::class, [
            'label' => 'admin.business.customer',
            'required' => false,
            'class' => Customer::class,
            'choice_label' => function ($data) {
                  return $data->getName01(). ' '. $data->getName02();
            },
            "placeholder" => 'admin.common.select.placeholder',
//            'eccube_form_options' => [
//                'auto_render' => true
//            ]
        ])
        ->add('working_ym', MonthType::class, [
            'label' => 'admin.report.working_ym',
            'required' => false,
            'widget' => 'single_text',
            'html5' => false
//            //'attr' => ['readonly' => 'readonly'],
        ])
        // ソート用
        ->add('sortkey', HiddenType::class, [
            'label' => 'admin.list.sort.key',
            'required' => false,
        ])
        ->add('sorttype', HiddenType::class, [
            'label' => 'admin.list.sort.type',
            'required' => false,
        ])
        ;
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix()
  {
      return 'admin_search_report';
  }
}
