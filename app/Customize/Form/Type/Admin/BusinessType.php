<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\Business;
use Customize\Entity\Staff;
use Customize\Entity\Master\BusinessStatus;
use Customize\Entity\Master\BusinessConfig;
use Customize\Form\Type\Admin\StaffType;
use Customize\Form\Type\Master\BusinessStatusType;
use Customize\Repository\BusinessRepository;
use Customize\Repository\StaffRepository;
use Customize\Repository\Master\BusinessStatusRepository;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Customer;
use Eccube\Form\Type\Admin\CustomerType;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Eccube\Repository\CustomerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class BusinessType extends AbstractType
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
          ->add('name', TextType::class, [
              'label' => 'admin.business.name',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('status', BusinessStatusType::class, [
              'label' => 'admin.business.status',
              "placeholder" => 'admin.common.select.placeholder',
//              'required' => true,
//              'constraints' => [
//                  new Assert\NotBlank(),
//              ],
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
          ->add('contractor_id', TextType::class, [
              'label' => 'admin.config.contractor',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('contractor_id', ChoiceType::class, [
              'label' => 'admin.business.contractor',
              'required' => true,
              'expanded' => true,
              'multiple' => false,
              'choices' => array_flip(BusinessConfig::BUSINESS_CONTRACTOR),
          ])
          ->add('kind_id', ChoiceType::class, [
              'label' => 'admin.business.kind',
              'required' => true,
              'expanded' => true,
              'multiple' => false,
              'choices' => array_flip(BusinessConfig::BUSINESS_KIND),
          ])
          ->add('invoice_sender_type', ChoiceType::class, [
              'label' => 'admin.business.invoice.sender.type',
              'required' => true,
              'expanded' => true,
              'multiple' => false,
              'choices' => array_flip(BusinessConfig::INVOICE_SENDER_TYPE),
          ])
          ->add('sender_name', TextType::class, [
              'required' => false,
          ])
          ->add('sender_postal_code', PostalType::class, [
              'required' => false,
          ])
          ->add('sender_addr01', TextType::class, [
              'required' => false,
          ])
          ->add('sender_addr02', TextType::class, [
              'required' => false,
          ])
          ->add('sender_phone_number', PhoneNumberType::class, [
              'required' => false,
          ])
          ->add('remark', TextType::class, [
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
