<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\Staff;
use Eccube\Common\EccubeConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class MainBankType extends AbstractType
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
            ->add('payslip_reg_code', TextType::class, [
              'label' => 'common.reg.code.payslip',
              'required' => false,
            ])
            ->add('payslip_bank_name', TextType::class, [
              'label' => 'admin.bank.name',
              'required' => false,
            ])
            ->add('payslip_bank_code', TextType::class, [
                'label' => 'admin.bank.code',
                'required' => false,
            ])
            ->add('payslip_bank_branch_name', TextType::class, [
                'label' => 'admin.bank.branch.name',
                'required' => false,
            ])
            ->add('payslip_bank_branch_code', TextType::class, [
                'label' => 'admin.bank.branch.code',
                'required' => false,
            ])
            ->add('payslip_bank_account_type', TextType::class, [
                'label' => 'admin.bank.account.type',
                'required' => false,
            ])
            ->add('payslip_bank_account_number', TextType::class, [
                'label' => 'admin.bank.account.number',
                'required' => false,
            ])
            ->add('payslip_bank_account_name', TextType::class, [
                'label' => 'admin.bank.account.name',
                'required' => false,
            ])
            ->add('payslip_bank_account_name_kana', TextType::class, [
                'label' => 'admin.bank.account.name.kana',
                'required' => false,
            ])
            ->add('invoice_bank_name', TextType::class, [
                'label' => 'admin.bank.name',
                'required' => false,
            ])
            ->add('invoice_reg_code', TextType::class, [
              'label' => 'common.reg.code.invoice',
              'required' => false,
            ])
            ->add('invoice_bank_code', TextType::class, [
                'label' => 'admin.bank.code',
                'required' => false,
            ])
            ->add('invoice_bank_branch_name', TextType::class, [
                'label' => 'admin.bank.branch.name',
                'required' => false,
            ])
            ->add('invoice_bank_branch_code', TextType::class, [
                'label' => 'admin.bank.branch.code',
                'required' => false,
            ])
            ->add('invoice_bank_account_type', TextType::class, [
                'label' => 'admin.bank.account.type',
                'required' => false,
            ])
            ->add('invoice_bank_account_number', TextType::class, [
                'label' => 'admin.bank.account.number',
                'required' => false,
            ])
            ->add('invoice_bank_account_name', TextType::class, [
                'label' => 'admin.bank.account.name',
                'required' => false,
            ])
            ->add('invoice_bank_account_name_kana', TextType::class, [
                'label' => 'admin.bank.account.name.kana',
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
