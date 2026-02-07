<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\Business;
use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Eccube\Form\Type\Master\SexType;
use Eccube\Form\Type\Master\PrefType;
use Customize\Form\Type\BusinessType;
use Customize\Form\Type\Master\ServicesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchBusinessType extends AbstractType
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
              'label' => 'admin.business.multi_search_label',
              'required' => false,
              'constraints' => [
                  new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
              ],
          ])
          ->add('name', TextType::class, [
              'label' => 'admin.business.name',
              'required' => false,
          ])
          ->add('status', ChoiceType::class, [
              'label' => 'admin.business.status',
              'choices' => [
                  '仮契約' => '1',
                  '本契約' => '2',
                  '稼働中' => '3',
                  '問題あり' => '8',
                  '連絡取れず未契約' => '9'
              ],
              'expanded' => true,
              'multiple' => true,
          ])
          ->add('customer_id', TextType::class, [
              'label' => 'admin.invoice.customer_id',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('customer_name', TextType::class, [
              'label' => 'admin.invoice.customer_name',
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
