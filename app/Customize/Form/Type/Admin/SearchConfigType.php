<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\Config;
use Eccube\Common\EccubeConfig;
use Customize\Form\Type\ConfigType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchConfigType extends AbstractType
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
          ->add('period_start', DateType::class, [
              'label' => 'admin.config.period_start',
              'required' => false,
              'input' => 'datetime',
              'widget' => 'single_text',
              'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
              'constraints' => [
                  new Assert\Range([
                      'min'=> '0003-01-01',
                      'minMessage' => 'form_error.out_of_range',
                  ]),
              ],
              'attr' => [
                  'class' => 'datetimepicker-input',
                  'data-target' => '#'.$this->getBlockPrefix().'_period_start',
                  'data-toggle' => 'datetimepicker',
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
      return 'admin_search_payslip';
  }
}
