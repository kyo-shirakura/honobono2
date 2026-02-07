<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\Payslip;
use Customize\Form\Type\Master\PayslipStatusType;
use Eccube\Common\EccubeConfig;
use Customize\Entity\Staff;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Customize\Form\Type\MonthType;
use Customize\Form\Type\Admin\StaffType;
use Customize\Repository\StaffRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PayslipType extends AbstractType
{
  /**
   * @var EccubeConfig
   */
  protected $eccubeConfig;

  /**
   * @var StaffRepository
   */
  protected $staffRepository;

  /**
   * constructor.
   *
   * @param EccubeConfig $eccubeConfig
   * @param StaffRepository $staffRepository
   */
  public function __construct(
      EccubeConfig $eccubeConfig,
      StaffRepository $staffRepository
  )
  {
      $this->eccubeConfig = $eccubeConfig;
      $this->staffRepository = $staffRepository;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder
/*
          ->add('id', TextType::class, [
              'label' => 'admin.payslip.id',
              'required' => false,
              'attr' => ['readonly' => 'readonly'],
          ])
*/
          ->add('Staff', EntityType::class, [
              'label' => 'admin.payslip.staff',
              'required' => true,
              'class' => Staff::class,
              'choice_label' => function ($data) {
                    return $data->getName01(). ' '. $data->getName02();
              },
              "placeholder" => 'admin.common.select.placeholder',
              'eccube_form_options' => [
                  'auto_render' => true
              ]
          ])
          ->add('issue_ym', MonthType::class, [
              'label' => 'admin.payslip.issue_ym',
              'required' => true,
              'widget' => 'single_text',
              'html5' => false,
              'data' => new \DateTime('now -1 month')
  //            //'attr' => ['readonly' => 'readonly'],
          ])
          // ファイルアップロード用の項目
          ->add('file', FileType::class, [
              'mapped' => false,
              'required' => true,
              'constraints' => [
                  new Assert\NotBlank(),
                  new Assert\File([
                      'mimeTypes' => ['application/pdf'],
                      'mimeTypesMessage' => 'admin.store.template.invalid_upload_file',
                  ]),
              ],
          ])
          ->add('pdf_file', HiddenType::class)
          ->add('sendmail', CheckboxType::class, [
              'label' => 'admin.payslip.sendmail.check',
              'required' => false,
              'data' => true
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
