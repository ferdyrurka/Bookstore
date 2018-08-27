<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateUserForm
 * @package App\Form
 */
class CreateAdminForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class)
            ->add('plain_password', RepeatedType::class, array(
                'type'=>PasswordType::class,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('email', EmailType::class)
            ->add('first_name', TextType::class, array(
                'trim' => true
            ))
            ->add('surname', TextType::class, array(
                'trim' => true
            ))
            ->add('admin_password', PasswordType::class)
            ->add('save', SubmitType::class)
        ;

        $builder->get('first_name')->addModelTransformer(new CallbackTransformer(
            function (?string $firstName) {
                return $firstName;
            },
            function (?string $firstName) {
                return htmlspecialchars($firstName);
            }
        ));

        $builder->get('surname')->addModelTransformer(new CallbackTransformer(
            function (?string $surname) {
                return $surname;
            },
            function (?string $surname) {
                return htmlspecialchars($surname);
            }
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'create_new_admin_CSRF_protection',
        ));
    }
}
