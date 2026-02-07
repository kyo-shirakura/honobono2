<?php

namespace Customize\Form\Type\Admin;

use Customize\Entity\BusinessStaff;
use Customize\Entity\BusinessStaffWorkingTime;
use Customize\Entity\Staff;
use Customize\Entity\Master\BusinessConfig;
use Customize\Entity\Master\BusinessStatus;
use Customize\Form\Type\Admin\StaffType;
use Customize\Form\Type\Master\BusinessStatusType;
use Customize\Repository\BusinessStaffRepository;
use Customize\Repository\BusinessStaffWorkingTimeRepository;
use Customize\Repository\StaffRepository;
use Customize\Repository\Master\BusinessStatusRepository;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Customer;
use Eccube\Form\Type\Admin\CustomerType;
use Eccube\Repository\CustomerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class BusinessStaffWorkingTimeType extends AbstractType
{
  /**
   * {@inheritdoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder
          ->add('day_of_week', ChoiceType::class, [
              'label' => 'admin.business.day_of_week',
              'required' => true,
              'expanded' => true,
              'multiple' => true,
              'choices' => array_flip(BusinessConfig::BUSINESS_WEEKLY),
          ])
          ->add('working_time_start', TextType::class, [
              'label' => 'admin.report.working_time_start',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('working_time_end', TextType::class, [
              'label' => 'admin.report.working_time_end',
              'required' => false,
              //'attr' => ['readonly' => 'readonly'],
          ])
          ->add('working_time_break', TextType::class, [
              'label' => 'admin.report.working_time_break',
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
      return 'business_staff_working_time';
  }
}
