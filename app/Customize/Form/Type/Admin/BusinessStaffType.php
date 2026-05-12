<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\BusinessStaff;
use Customize\Entity\BusinessStaffWorkingTime;
use Customize\Entity\Staff;
use Customize\Entity\Master\BusinessConfig;
use Customize\Entity\Master\BusinessStatus;
use Customize\Form\Type\Admin\StaffType;
use Customize\Form\Type\Admin\BusinessStaffWorkingTimeType;
use Customize\Form\Type\Master\BusinessStatusType;
use Customize\Repository\BusinessStaffRepository;
use Customize\Repository\BusinessStaffWorkingTimeRepository;
use Customize\Repository\StaffRepository;
use Customize\Repository\Master\BusinessStatusRepository;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Customer;
use Eccube\Form\Type\Admin\CustomerType;
use Eccube\Repository\CustomerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class BusinessStaffType extends AbstractType
{
  /**
   * @var EccubeConfig
   */
  protected $eccubeConfig;

  /**
   * @var BusinessStatusRepository
   */
  protected $businessStatusRepository;

  /**
   * @var CustomerRepository
   */
  protected $customerRepository;

  /**
   * @var StaffRepository
   */
  protected $staffRepository;

  /**
   * constructor.
   *
   * @param EccubeConfig $eccubeConfig
   * @param BusinessStatusRepository $businessStatusRepository
   * @param CustomerRepository $customerRepository
   * @param StaffRepository $staffRepository
   */
  public function __construct(
      EccubeConfig $eccubeConfig,
      BusinessStatusRepository $businessStatusRepository,
      CustomerRepository $customerRepository,
      StaffRepository $staffRepository
  )
  {
      $this->eccubeConfig = $eccubeConfig;
      $this->businessStatusRepository = $businessStatusRepository;
      $this->customerRepository = $customerRepository;
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
              'label' => 'admin.business.multi_search_label',
              'required' => false,
              'attr' => ['readonly' => 'readonly'],
          ])
*/
          ->add('status', BusinessStatusType::class, [
              'label' => 'admin.business.status',
              "placeholder" => 'admin.common.select.placeholder',
//              'required' => true,
//              'constraints' => [
//                  new Assert\NotBlank(),
//              ],
          ])
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
          ->add('period_start', TextType::class, [
              'label' => 'admin.config.period_start',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('period_end', TextType::class, [
              'label' => 'admin.config.period_end',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('regular_type', ChoiceType::class, [
              'label' => 'admin.business.kind',
              'required' => true,
              'expanded' => true,
              'multiple' => false,
              'choices' => array_flip(BusinessConfig::BUSINESS_REGULAR_TYPE),
          ])
          ->add('week_no', ChoiceType::class, [
              'label' => 'admin.config.week_no',
              'required' => false,
              'choices' => array_combine(range(1,4), range(1,4) ),
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('fare', TextType::class, [
              'label' => 'admin.business.fare',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('access', TextareaType::class, [
              'label' => 'admin.business.access',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('working_time_other', TextareaType::class, [
              'label' => '',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('remark', TextareaType::class, [
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
      ;
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix()
  {
      return 'admin_search_business';
  }
}
