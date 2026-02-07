<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\Business;
use Customize\Entity\Report;
use Customize\Entity\Staff;
use Customize\Entity\Master\CancelKind;
use Customize\Form\Type\Admin\BusinessType;
use Customize\Form\Type\Master\BusinessStatusType;
use Customize\Form\Type\Master\CancelKindType;
use Customize\Form\Type\Master\ReportStatusType;
use Customize\Form\Type\Master\SafetyConfirmationType;
use Customize\Form\Type\Master\WorkDetailsType;
use Customize\Repository\BusinessRepository;
use Customize\Repository\Master\BusinessStatusRepository;
use Customize\Repository\Master\ReportStatusRepository;
use Customize\Repository\Master\SafetyConfirmationRepository;
use Customize\Repository\Master\WorkDetailsRepository;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Customer;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Eccube\Form\DataTransformer;
use Eccube\Form\DataTransformer\EntityToIdTransformer;
use Symfony\Component\Form\CallbackTransformer;

class ReportCancelType extends AbstractType
{
  /**
   * @var EccubeConfig
   */
  protected $eccubeConfig;

  /**
   * @var BusinessRepository
   */
  protected $businessRepository;

  /**
   * @var BusinessStatusRepository
   */
  protected $businessStatusRepository;

  /**
   * @var ReportStatusRepository
   */
  protected $reportStatusRepository;

  /**
   * @var SafetyConfirmationRepository
   */
  protected $safetyConfirmationRepository;

  /**
   * @var WorkDetailsRepository
   */
  protected $workDetailsRepository;

  /**
   * constructor.
   *
   * @param EccubeConfig $eccubeConfig
   * @param BusinessRepository $businessRepository
   * @param BusinessStatusRepository $businessStatusRepository
   * @param ReportStatusRepository $reportStatusRepository
   * @param SafetyConfirmationRepository $safetyConfirmationRepository
   * @param WorkDetailsRepository $workDetailsRepository
   */
  public function __construct(
      EccubeConfig $eccubeConfig,
      BusinessRepository $businessRepository,
      BusinessStatusRepository $businessStatusRepository,
      ReportStatusRepository $reportStatusRepository,
      SafetyConfirmationRepository $safetyConfirmationRepository,
      WorkDetailsRepository $workDetailsRepository
  )
  {
      $this->eccubeConfig = $eccubeConfig;
      $this->businessRepository = $businessRepository;
      $this->businessStatusRepository = $businessStatusRepository;
      $this->reportStatusRepository = $reportStatusRepository;
      $this->safetyConfirmationRepository = $safetyConfirmationRepository;
      $this->workDetailsRepository = $workDetailsRepository;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      // 配列→ハッシュ値変換のTransformer作成
      $transformer = new CallbackTransformer(
          function ($string) {
              $decode = unserialize($string);

              // 配列以外が入った場合は空配列をセット
              if (!is_array($decode)) {
                  $decode = array();
              }
              return $decode;
          },
          function ($array) {
              $encode = serialize($array);
              if (is_bool($encode)) {
                  $encode = '';
              }
              return $encode;
          }
      );

      $builder
/*
          ->add('id', TextType::class, [
              'label' => 'admin.report.id',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
*/
          ->add('Staff', EntityType::class, [
              'label' => 'admin.business.staff',
              'class' => Staff::class,
              'choice_label' => function ($data) {
                    return $data->getName01(). ' '. $data->getName02();
              },
              "placeholder" => 'admin.common.select.placeholder',
              'eccube_form_options' => [
                  'auto_render' => true
              ]
          ])
          ->add('Customer', EntityType::class, [
              'label' => 'admin.business.customer',
              'class' => Customer::class,
              'choice_label' => function ($data) {
                    return $data->getName01(). ' '. $data->getName02();
              },
              "placeholder" => 'admin.common.select.placeholder',
              'eccube_form_options' => [
                  'auto_render' => true
              ]
          ])
          ->add('Business', EntityType::class, [
              'label' => 'admin.report.business',
              'class' => Business::class,
              'choice_label' => 'name',
              "placeholder" => 'admin.common.select.placeholder',
              'eccube_form_options' => [
                  'auto_render' => true
              ]
          ])
          ->add('CancelKind', EntityType::class, [
              'label' => 'admin.report.cancel.kind',
              'class' => CancelKind::class,
              'required' => false,
              "placeholder" => 'admin.common.select.placeholder',
              'eccube_form_options' => [
                  'auto_render' => true
              ]
          ])
          ->add('working_day', TextType::class, [
              'label' => 'admin.report.working_day',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('working_time_start', TextType::class, [
              'label' => 'admin.report.working_time_start',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('working_time_end', TextType::class, [
              'label' => 'admin.report.working_time_end',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('working_time_break', TextType::class, [
              'label' => 'admin.report.working_time_break',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('working_time_total', TextType::class, [
              'label' => 'admin.report.working_time_total',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('remark', TextareaType::class, [
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('status', ReportStatusType::class, [
              'label' => 'admin.report.status',
//              'required' => true,
//              'constraints' => [
//                  new Assert\NotBlank(),
//              ],
          ])
          ;
    }
    public function upload(UploadedFile $file)
    {
      $img_file = date('mdHis') . uniqid('_') . '.' . $file->guessExtension();

      $file->move(
          $this->eccubeConfig["eccube_save_image_dir"],
          $img_file
      );

      return $img_file;
    }

    /**
    * {@inheritdoc}
    */
    public function getBlockPrefix()
    {
      return 'admin_search_report';
    }
}
