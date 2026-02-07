<?php

namespace Customize\Form\Type\Master;

use Customize\Entity\Master\CancelKind;
use Customize\Entity\Master\BusinessConfig;
use Customize\Repository\CancelKindRepository;
use Eccube\Common\EccubeConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CancelKindType extends AbstractType
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
//      $transformer = new IntegerToBooleanTransformer();

      $builder
          ->add('name', TextType::class, [
              'label' => 'admin.cancel.kind.name',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('customer_rate', TextType::class, [
              'label' => 'admin.cancel.kind.rate',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('customer_fare', CheckboxType::class, [
              'label' => 'admin.business_plan.cancel.customer_fare',
              'required' => false,
          ])
          ->add('staff_rate', TextType::class, [
              'label' => 'admin.staff.kind.rate',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('staff_fare', CheckboxType::class, [
              'label' => 'admin.business_plan.cancel.staff_fare',
              'required' => false,
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
