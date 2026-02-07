<?php
namespace Customize\Form\Type\Master;

use Customize\Entity\Master\InvoiceAddItems;
use Eccube\Form\Type\MasterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceAddItemsType extends AbstractType
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
            'class' => 'Customize\Entity\Master\InvoiceAddItems',
            'expanded' => false,
        ]);
    }

    public function getParent()
    {
        return MasterType::class;
    }

    public function getBlockPrefix()
    {
        return 'invoice_add_items';
    }
}
