<?php
namespace Customize\Form\Type\Master;

use Eccube\Form\Type\MasterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class KindType
 */
class KindType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => 'Customize\Entity\Master\Kind',
            'placeholder' => 'common.select__kind',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockKindix()
    {
        return 'kind';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return MasterType::class;
    }
}
