<?php
namespace Customize\Form\Type\Master;

use Customize\Entity\Master\InvoiceStatus;
use Eccube\Form\Type\MasterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceStatusType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $options['sex_options']['required'] = $options['required'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => 'Customize\Entity\Master\InvoiceStatus',
            'expanded' => false,
        ]);
    }

    public function getParent()
    {
        return MasterType::class;
    }

    public function getBlockPrefix()
    {
        return 'invoice_status';
    }
}
