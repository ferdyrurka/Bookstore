<?php


namespace App\Form\Type;

use App\Request\DeliveryMethodRequest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DeliveryMethodFormType
 * @package App\Form\Type
 */
class DeliveryMethodFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('delivery_method_id', EntityType::class, array(
                'class' => 'App\Entity\DeliveryMethod',
                'label' => 'Delivery method',
                'choice_label' => 'name',
                'choice_value' => 'id',
                'empty_data' => null,
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => DeliveryMethodRequest::class,
        ));
    }
}
