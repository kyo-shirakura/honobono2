<?php

namespace Customize\Form\Type\Master;

use Eccube\Form\Type\MasterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ServicesType
 */
class ServicesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => 'Customize\Entity\Master\Services',
            'placeholder' => 'admin.hospital.select_services',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockServicesix()
    {
        return 'services';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return MasterType::class;
    }
}
