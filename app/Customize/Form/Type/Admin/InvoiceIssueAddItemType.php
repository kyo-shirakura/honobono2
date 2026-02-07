<?php

namespace Customize\Form\Type\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Common\EccubeConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class InvoiceIssueAddItemType.
 */
class InvoiceIssueAddItemType extends AbstractType
{
    /** @var EccubeConfig */
    private $eccubeConfig;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * OrderPdfType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EccubeConfig $eccubeConfig, EntityManagerInterface $entityManager)
    {
        $this->eccubeConfig = $eccubeConfig;
        $this->entityManager = $entityManager;
    }

    /**
     * Build config type form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $config = $this->eccubeConfig;
        $builder
            ->add('issue_ym', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'required' => true,
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'data' => new \DateTime(),
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Range([
                        'min'=> '0003-01-01',
                        'minMessage' => 'form_error.out_of_range',
                    ]),
                ],
                'attr' => [
                    'data-target' => '#'.$this->getBlockPrefix().'_issue_date',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            //
            ->add('item_title', TextType::class, [
                'required' => false,
                'attr' => ['maxlength' => $config['eccube_stext_len']],
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_stext_len']]),
                ],
            ])
            ->add('item_amout', TextType::class, [
                'required' => false,
                //'attr' => ['readonly' => 'readonly'],
            ])
            ->add('item_remark', TextType::class, [
                'required' => false,
                'attr' => ['maxlength' => $config['eccube_stext_len']],
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_stext_len']]),
                ],
            ])
            // メッセージ
            ->add('message', TextType::class, [
                'required' => false,
                'attr' => ['maxlength' => $config['eccube_order_pdf_message_len']],
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_order_pdf_message_len']]),
                ],
                'trim' => false,
            ])
            // 備考
            ->add('remark', TextType::class, [
                'required' => false,
                'attr' => ['maxlength' => $config['eccube_stext_len']],
                'constraints' => [
                    new Assert\Length(['max' => $config['eccube_stext_len']]),
                ],
            ])
/*
            ->add('default', CheckboxType::class, [
                'label' => 'admin.order.delivery_note_save_input',
                'required' => false,
            ])

            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $form->getData();
                if (!isset($data['ids']) || !is_string($data['ids'])) {
                    return;
                }
                $ids = explode(',', $data['ids']);

                $qb = $this->entityManager->createQueryBuilder();
                $qb->select('count(s.id)')
                    ->from('Eccube\\Entity\\Shipping', 's')
                    ->where($qb->expr()->in('s.id', ':ids'))
                    ->setParameter('ids', $ids);
                $actual = $qb->getQuery()->getSingleScalarResult();
                $expected = count($ids);
                if ($actual != $expected) {
                    $form['ids']->addError(
                        new FormError(trans('admin.order.delivery_note_parameter_error'))
                    );
                }
            })
*/
            ;
    }

    /**
     * Get name method (form factory name).
     *
     * @return string
     */
    public function getName()
    {
        return 'admin_order_pdf';
    }
}
