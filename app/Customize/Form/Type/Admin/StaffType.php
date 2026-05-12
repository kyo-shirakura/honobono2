<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\Staff;
use Customize\Form\Type\Master\StaffStatusType;
use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Eccube\Form\Type\RepeatedPasswordType;
use Eccube\Form\Type\Master\SexType;
use Eccube\Form\Validator\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class StaffType extends AbstractType
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
/*
          ->add('id', TextType::class, [
              'label' => 'admin.staff.multi_search_label',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
*/
//          ->add('staff_id', TextType::class, [
//              'label' => 'admin.staff.staff_id',
//              'required' => false,
//              //'attr' => ['readonly' => 'readonly'],
//          ])
          ->add('status', TextType::class, [
              'label' => 'admin.staff.status',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('name', NameType::class, [
              'required' => true,
          ])
          ->add('kana', KanaType::class, [
              'required' => false,
          ])
          ->add('postal_code', PostalType::class, [
              'required' => true,
          ])
          ->add('address', AddressType::class, [
              'required' => true,
          ])
          ->add('phone_number', PhoneNumberType::class, [
              'required' => true,
          ])
          ->add('email', EmailType::class, [
              'required' => true,
              'constraints' => [
                  new Assert\NotBlank(),
                  new Email(null, null, $this->eccubeConfig['eccube_rfc_email_check'] ? 'strict' : null),
                  new Assert\Length([
                      'max' => $this->eccubeConfig['eccube_email_len'],
                  ]),
              ],
              'attr' => [
                  'placeholder' => 'common.mail_address_sample',
              ],
          ])
          ->add('sex', SexType::class, [
              'required' => false,
          ])
          ->add('birth', BirthdayType::class, [
              'required' => false,
              'input' => 'datetime',
              'years' => range(date('Y'), date('Y') - $this->eccubeConfig['eccube_birth_max']),
              'widget' => 'single_text',
              'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
              'constraints' => [
                  new Assert\LessThanOrEqual([
                      'value' => date('Y-m-d', strtotime('-1 day')),
                      'message' => 'form_error.select_is_future_or_now_date',
                  ]),
                  new Assert\Range([
                      'min'=> '0003-01-01',
                      'minMessage' => 'form_error.out_of_range',
                  ]),
              ],
          ])
          ->add('status', StaffStatusType::class, [
              'required' => true,
              'constraints' => [
                  new Assert\NotBlank(),
              ],
          ])
          ->add('access', TextType::class, [
              'label' => 'admin.staff.access',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('bank_name', TextType::class, [
              'label' => 'common.bank.name',
              'required' => false,
          ])
          ->add('bank_code', TextType::class, [
              'label' => 'common.bank.code',
              'required' => false,
          ])
          ->add('bank_branch_name', TextType::class, [
              'label' => 'common.bank.branch.name',
              'required' => false,
          ])
          ->add('bank_branch_code', TextType::class, [
              'label' => 'common.bank.branch.code',
              'required' => false,
          ])
          ->add('bank_account_type', ChoiceType::class, [
              'label' => 'common.bank.account.type',
              'choices' => [
                  '普通' => '1',
                  '当座' => '2',
              ],
              'expanded' => true,
              'multiple' => false,
          ])
          ->add('bank_account_number', TextType::class, [
              'label' => 'common.bank.account.number',
              'required' => false,
          ])
          ->add('bank_account_name', TextType::class, [
              'label' => 'common.bank.account.name',
              'required' => false,
          ])
          ->add('bank_account_name_kana', TextType::class, [
              'label' => 'common.bank.account.name.kana',
              'required' => false,
          ])
          ->add('transferee_account', TextType::class, [
              'label' => 'admin.config.transferee_account',
              'required' => false,
          ])
          ->add('note', TextareaType::class, [
              'required' => false,
              'constraints' => [
                  new Assert\Length([
                      'max' => $this->eccubeConfig['eccube_ltext_len'],
                  ]),
              ],
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
      return 'admin_search_staff';
  }
}
