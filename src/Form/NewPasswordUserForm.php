<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NewPasswordUserForm
 * @package App\Form
 */
class NewPasswordUserForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plain_password', RepeatedType::class, array(
               'type' => PasswordType::class,
                'first_options' => array(
                    'label' => 'Password',
                    'attr' => [
                        'maxlength' => 8,
                        'minlength' => 64,
                    ],
                ),
                'second_options' => array(
                    'label' => 'Repeat password',
                    'attr' => [
                        'maxlength' => 8,
                        'minlength' => 64,
                    ],
                )
            ))
            ->add('save', SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'csrf.protection_new_password.build;',
        ));
    }
}
