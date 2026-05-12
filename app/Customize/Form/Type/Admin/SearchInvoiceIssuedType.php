<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\Invoice;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Customer;
use Eccube\Form\Type\Admin\CustomerType;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Eccube\Form\Type\Master\SexType;
use Eccube\Form\Type\Master\PrefType;
use Customize\Form\Type\InvoiceType;
use Customize\Form\Type\MonthType;
use Customize\Form\Type\Master\ServicesType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchInvoiceIssuedType extends AbstractType
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
          ->add('issue_ym', MonthType::class, [
              'label' => 'admin.invoice.issue_ym',
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
      return 'admin_search_invoice';
  }
}
