<?php
namespace Customize\Form\Type\Front;

use Eccube\Common\EccubeConfig;
use Eccube\Form\Validator\Email;
use Eccube\Form\Type\PhoneNumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints as Assert;

class MyLoginType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var AuthenticationUtils
     */
    protected $authenticationUtils;

    public function __construct(AuthenticationUtils $authenticationUtils, EccubeConfig $eccubeConfig)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('login_email2', EmailType::class, [
            'required' => true,
            'constraints' => [
                new Assert\NotBlank(),
                new Email(null, null, $this->eccubeConfig['eccube_rfc_email_check'] ? 'strict' : null),
                new Assert\Length([
                    'max' => $this->eccubeConfig['eccube_email_len'],
                ]),
            ],
            'attr' => [
                'placeholder' => 'common.mail_address_sample',
            ],
        ]);
        $builder->add('login_email', PhoneNumberType::class, [
            'attr' => [
                'max' => $this->eccubeConfig['eccube_tel_len_max'],
            ],
            'constraints' => [
                new Assert\NotBlank(),
            ],
//            'data' => $this->authenticationUtils->getLastUsername(),
        ]);
        $builder->add('login_phone_number', PhoneNumberType::class, [
            'attr' => [
                'max' => $this->eccubeConfig['eccube_tel_len_max'],
            ],
            'constraints' => [
                new Assert\NotBlank(),
            ],
//            'data' => $this->authenticationUtils->getLastUsername(),
        ]);
        $builder->add('login_memory', CheckboxType::class, [
            'required' => false,
        ]);

        $builder->add('login_email1', EmailType::class, [
            'attr' => [
                'maxlength' => $this->eccubeConfig['eccube_stext_len'],
            ],
            'constraints' => [
                new Assert\NotBlank(),
                new Email(null, null, $this->eccubeConfig['eccube_rfc_email_check'] ? 'strict' : null),
            ],
        ]);
/*
        $builder->add('login_pass', PasswordType::class, [
            'attr' => [
                'maxlength' => $this->eccubeConfig['eccube_stext_len'],
            ],
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ]);
*/
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'staff_login';
    }
}
