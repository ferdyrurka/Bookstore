<?php


namespace App\Form;

use App\Form\Type\DeliveryMethodFormType;
use App\Form\Type\PriceMethodFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CreateOrderForm
 * @package App\Form
 */
class CreateOrderForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', TextType::class, array(
                'trim' => true
            ))
            ->add('surname', TextType::class, array(
                'trim' => true
            ))
            ->add('city', TextType::class)
            ->add('street', TextType::class)
            ->add('house_number', TextType::class, array(
                'trim' => true
            ))
            ->add('post_code', TextType::class, array(
                'trim' => true
            ))
            ->add('phone', TextType::class, array(
                'trim' => true
            ))
            ->add('email', EmailType::class)
            ->add('price_methods', CollectionType::class, array(
                'entry_type'=> PriceMethodFormType::class,
                'entry_options' => array('label'=>false),
                'label' => false,
            ))
            ->add('delivery_methods', CollectionType::class, array(
                'entry_type'=> DeliveryMethodFormType::class,
                'entry_options' => array('label'=>false),
                'label' => false,
            ))
            ->add('other_information', TextareaType::class, array(
                'required' => false,
            ))
            ->add('buy', SubmitType::class)
        ;

        $builder->get('other_information')->addModelTransformer(new CallbackTransformer(
            function (?string $otherInformation) {
                return $otherInformation;
            },
            function (?string $otherInformation) {
                return htmlspecialchars($otherInformation);
            }
        ));

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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'order.cart.buy.products_CSRF_TOKEN_PROTECTION',
        ));
    }
}
