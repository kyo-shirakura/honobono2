<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\Payslip;
use Customize\Entity\Staff;
use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Eccube\Form\Type\Master\SexType;
use Eccube\Form\Type\Master\PrefType;
use Customize\Form\Type\PayslipType;
use Customize\Form\Type\MonthType;
use Customize\Form\Type\Master\ServicesType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchPayslipType extends AbstractType
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
          ->add('Staff', EntityType::class, [
              'label' => 'admin.business.staff',
              'class' => Staff::class,
              'choice_label' => function ($data) {
                    return $data->getName01(). ' '. $data->getName02();
              },
              "placeholder" => 'admin.common.select.placeholder',
              'required' => false,
              'eccube_form_options' => [
                  'auto_render' => true
              ]
          ])
          ->add('issue_ym', MonthType::class, [
              'label' => 'admin.payslip.issue_ym',
              'required' => false,
              'widget' => 'single_text',
              'html5' => false,
              'data' => new \DateTime('now')
  //            //'attr' => ['readonly' => 'readonly'],
          ])
      ;
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix()
  {
      return 'admin_search_payslip';
  }
}
