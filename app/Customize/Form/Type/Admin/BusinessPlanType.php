<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\BusinessPlan;
use Customize\Entity\Master\BusinessConfig;
use Customize\Repository\BusinessPlanRepository;
use Eccube\Common\EccubeConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class BusinessPlanType extends AbstractType
{
  /**
   * @var EccubeConfig
   */
  protected $eccubeConfig;

  /**
   * constructor.
   *
   * @param EccubeConfig $eccubeConfig
   */
  public function __construct(
      EccubeConfig $eccubeConfig
  )
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
              'label' => 'admin.business.multi_search_label',
              'required' => false,
              'attr' => ['readonly' => 'readonly'],
          ])
*/
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
          ->add('basic_time_start', ChoiceType::class, [
              'label' => 'admin.config.basic_time_start',
              'required' => false,
              'choices' => array_combine(range(0,24), range(0,24) ),
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('basic_time_end', ChoiceType::class, [
              'label' => 'admin.config.basic_time_end',
              'required' => false,
              'choices' => array_combine(range(0,24), range(0,24) ),
          ])
          ->add('basic_time_bill', TextType::class, [
              'label' => 'admin.config.basic_time_bill',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('bill_outside', TextType::class, [
              'label' => 'admin.config.bill_outside',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('basic_time_pay', TextType::class, [
              'label' => 'admin.config.basic_time_pay',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('pay_outside', TextType::class, [
              'label' => 'admin.config.pay_outside',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('commission', TextType::class, [
              'label' => 'admin.config.commission',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('tax', TextType::class, [
              'label' => 'admin.config.tax',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('transfer_date', TextType::class, [
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
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
      return 'admin_search_config';
  }
}
