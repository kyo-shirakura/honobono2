<?php
namespace Customize\Form\Type\Front;

use Customize\Entity\Business;
use Customize\Entity\Report;
use Customize\Entity\Staff;
use Customize\Form\Type\Admin\BusinessType;
use Customize\Form\Type\Master\BusinessStatusType;
use Customize\Form\Type\Master\ReportStatusType;
use Customize\Form\Type\Master\SafetyConfirmationType;
use Customize\Form\Type\Master\WorkDetailsType;
use Customize\Repository\StaffRepository;
use Customize\Repository\BusinessRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Customer;
use Eccube\Form\DataTransformer;
use Eccube\Form\DataTransformer\EntityToIdTransformer;
use Eccube\Form\Validator\Email;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Repository\CustomerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\CallbackTransformer;

class StaffReportType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var BusinessRepository
     */
    protected $businessRepository;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var StaffRepository
     */
    protected $staffRepository;
    /**
     * @var AuthenticationUtils
     */
    protected $authenticationUtils;

    public function __construct(
        AuthenticationUtils $authenticationUtils,
        EccubeConfig $eccubeConfig,
        BusinessRepository $businessRepository,
        CustomerRepository $customerRepository,
        StaffRepository $staffRepository
    )
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->eccubeConfig = $eccubeConfig;
        $this->businessRepository = $businessRepository;
        $this->customerRepository = $customerRepository;
        $this->staffRepository = $staffRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $staff_id = $options['data']['staff_id'];
        // 配列→ハッシュ値変換のTransformer作成
        $transformer = new CallbackTransformer(
            function ($string) {
                $decode = unserialize($string);

                // 配列以外が入った場合は空配列をセット
                if (!is_array($decode)) {
                    $decode = array();
                }
                return $decode;
            },
            function ($array) {
                $encode = serialize($array);
                if (is_bool($encode)) {
                    $encode = '';
                }
                return $encode;
            }
        );

        $builder
            ->add('id', TextType::class, [
                'label' => 'admin.report.id',
                'required' => false,
                //'attr' => ['readonly' => 'readonly'],
            ])
            ->add('Staff', HiddenType::class)
//            ->add('Staff', EntityType::class, [
//                'label' => 'admin.business.staff',
//                'class' => Staff::class,
//                'choice_label' => 'name01',
//                "placeholder" => 'admin.common.select.placeholder',
//                'eccube_form_options' => [
//                    'auto_render' => true
//                ]
//            ])
            ->add('Customer', EntityType::class, [
                'label' => 'admin.business.customer',
                'class' => Customer::class,
                'choice_label' => function ($data) {
                      return $data->getName01(). ' '. $data->getName02();
                },
                'required' => true,
                "placeholder" => 'admin.common.select.placeholder',
                'eccube_form_options' => [
                    'auto_render' => true
                ]
            ])
            ->add('Business', EntityType::class, [
                'label' => 'admin.report.business',
                'class' => Business::class,
                'choice_label' => function ($data) {
                    return $data->getCustomer()->getName01(). ' '. $data->getCustomer()->getName02();
                },
                "placeholder" => 'admin.common.select.placeholder',
                'query_builder' => function (EntityRepository $er) use ($staff_id) {
                    $Staff = $this->staffRepository->find($staff_id);
                    return $er->createQueryBuilder('b')
                        ->orderBy('b.id', 'ASC')
                        ->where('b.Staff = :Staff')
                        ->andWhere('b.Status = 3 OR b.Status = 4')
                        ->setParameter('Staff', $Staff);
                },
                'eccube_form_options' => [
                    'auto_render' => true
                ]
            ])
            ->add('working_day', DateType::class, [
                'label' => 'admin.report.working_day',
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
                'data' => new \DateTime('now')
            ])
            ->add('working_time_start_hour', ChoiceType::class, [
                'label' => 'admin.report.working_time_start',
                'required' => true,
                'choices' => array_combine(range(7,20), range(7,20)),
            ])
            ->add('working_time_start_minute', ChoiceType::class, [
                'label' => 'admin.report.working_time_start',
                'choices' => [
                    '00' => '00',
                    '15' => '15',
                    '30' => '30',
                    '45' => '45',
                ],
                'required' => true,
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('working_time_end_hour', ChoiceType::class, [
                'label' => 'admin.report.working_time_end',
                'required' => true,
                'choices' => array_combine(range(7,20), range(7,20)),
            ])
            ->add('working_time_end_minute', ChoiceType::class, [
                'label' => 'admin.report.working_time_end',
                'choices' => [
                    '00' => '00',
                    '15' => '15',
                    '30' => '30',
                    '45' => '45',
                ],
                'required' => true,
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('working_time_break', ChoiceType::class, [
                'label' => 'admin.report.working_time_break',
                'choices' => [
                    '0' => '0',
                    '15' => '15',
                    '30' => '30',
                    '45' => '45',
                    '60' => '60',
                    '75' => '75',
                ],
                'required' => true,
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('working_time_break_', TextType::class, [
                'label' => 'admin.report.working_time_break',
                'required' => false,
                //'attr' => ['readonly' => 'readonly'],
            ])
            ->add('working_time_total', HiddenType::class)
            ->add('work_details', WorkDetailsType::class, [
                'required' => false,
                'multiple' => true,
                'expanded' => true,
//                'choice_value' => function ($data) {
//                      return $data->getName();
//                },
//                'mapped' => false,
            ])
            ->add('work_other_check', CheckboxType::class, [
                'label' => 'admin.report.work_other',
                'required' => false,
                //'attr' => ['readonly' => 'readonly'],
            ])
            ->add('work_other_text', TextType::class, [
//                'label' => 'admin.report.work_other',
                'required' => false,
                //'attr' => ['readonly' => 'readonly'],
            ])
//            ->add('work_details', TextType::class, [
//                'label' => 'admin.report.work_details',
//                'required' => false,
//                //'attr' => ['readonly' => 'readonly'],
//            ])
            ->add('safety_confirmation', SafetyConfirmationType::class, [
                'required' => false,
                'multiple' => true,
                'expanded' => true,
//                'mapped' => false,
            ])
//            ->add('safety_confirmation', TextType::class, [
//                'label' => 'admin.report.safety_confirmation',
//                'required' => false,
                //'attr' => ['readonly' => 'readonly'],
//            ])
            ->add('payment', CheckboxType::class, [
                'label' => 'front.staff.report.payment.check',
                'required' => false,
            ])
            ->add('payment_deposit', NumberType::class, [
                'label' => 'admin.report.payment_deposit',
                'required' => false,
                //'attr' => ['readonly' => 'readonly'],
            ])
            ->add('payment_advance', NumberType::class, [
                'label' => 'admin.report.payment_advance',
                'required' => false,
                //'attr' => ['readonly' => 'readonly'],
            ])
            ->add('payment_change', NumberType::class, [
                'label' => 'admin.report.payment_change',
                'required' => false,
                //'attr' => ['readonly' => 'readonly'],
            ])
            // ファイルアップロード用の項目
            ->add('file_receipt', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'mimeTypes' => [
                            'jpg/*',
                            'jpeg/*',
                            'png/*',
                        ]
                    ])
                ]
            ])
            ->add('payment_receipt', HiddenType::class)
            ->add('remark', TextareaType::class, [
                'required' => false,
                //'attr' => ['readonly' => 'readonly'],
            ])
            ;

            $builder
                ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event){
                    $form = $event->getForm();
                    $StaffReport = $event->getData();

                    if($StaffReport instanceof StaffReport) {

                        $file = $form["file_receipt"]->getData();

                        if($file) {
                            // ファイルアップロード
                            $filename = $this->fileUploader->upload($file);
                            $StaffReport->setPaymentReceipt($filename);
                        }

                        $filename = $form["payment_receipt"]->getData();

                    }
                })
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'staff_login';
    }
}
