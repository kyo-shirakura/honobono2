<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\Invoice;
use Customize\Form\Type\Master\InvoiceStatusType;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Customer;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Customize\Form\Type\MonthType;
use Eccube\Form\Type\Admin\CustomerType;
use Eccube\Repository\CustomerRepository;
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

class InvoiceType extends AbstractType
{
  /**
   * @var EccubeConfig
   */
  protected $eccubeConfig;

  /**
   * @var CustomerRepository
   */
  protected $customerRepository;

  /**
   * constructor.
   *
   * @param EccubeConfig $eccubeConfig
   * @param CustomerRepository $customerRepository
   */
  public function __construct(
      EccubeConfig $eccubeConfig,
      CustomerRepository $customerRepository
  )
  {
      $this->eccubeConfig = $eccubeConfig;
      $this->customerRepository = $customerRepository;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder
/*
          ->add('id', TextType::class, [
              'label' => 'admin.invoice.id',
              'required' => false,
              'attr' => ['readonly' => 'readonly'],
          ])
*/
          ->add('Customer', EntityType::class, [
              'label' => 'admin.invoice.customer',
              'required' => true,
              'class' => Customer::class,
              'choice_label' => function ($data) {
                    return $data->getName01(). ' '. $data->getName02();
              },
              "placeholder" => 'admin.common.select.placeholder',
              'eccube_form_options' => [
                  'auto_render' => true
              ]
          ])
          ->add('issue_ym', MonthType::class, [
              'label' => 'admin.invoice.issue_ym',
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
              'label' => 'admin.invoice.sendmail.check',
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
      return 'admin_search_invoice';
  }
}
