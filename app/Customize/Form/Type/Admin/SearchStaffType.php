<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\Staff;
use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Eccube\Form\Type\Master\SexType;
use Eccube\Form\Type\Master\PrefType;
use Customize\Form\Type\StaffType;
use Customize\Form\Type\Master\ServicesType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchStaffType extends AbstractType
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
              'label' => 'admin.staff.multi_search_label',
              'required' => false,
              'constraints' => [
                  new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
              ],
          ])
          ->add('name', TextType::class, [
              'label' => 'admin.staff.name',
              'required' => false,
          ])
          ->add('status', ChoiceType::class, [
              'label' => 'admin.staff.status',
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
/*
          ->add('sex', ChoiceType::class, [
              'label' => 'admin.staff.sex',
              'choices' => ['男性' => '男性', '女性' => '女性'],
              'expanded' => true,
              'multiple' => true,
              'required' => false,
          ])
          ->add('birthday', TextType::class, [
              'label' => 'admin.staff.birthday',
              'required' => false,
              'attr' => [
                  'placeholder' => '例）YYYY-MM-DD',
              ],
          ])
          ->add('pref', PrefType::class, [
              'label' => 'admin.staff.pref',
              'required' => false,
          ])
          ->add('email', TextType::class, [
              'label' => 'admin.staff.email',
              'required' => false,
          ])
          ->add('special_subject', ServicesType::class, [
              'label' => 'admin.staff.special_subject',
              'required' => false,
          ])
          ->add('blacklist', ChoiceType::class, [
              'label' => 'admin.staff.blacklist',
              'choices' => ['登録済み' => '1'],
              'expanded' => true,
              'multiple' => true,
              'required' => false,
          ])
*/
      ;
  }

  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix()
  {
      return 'admin_search_staff';
  }
}
