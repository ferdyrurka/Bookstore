<?php


namespace App\Form\Type;

use App\Entity\PriceMethod;
use App\Request\PriceMethodRequest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PriceMethodFormType
 * @package App\Form\Type
 */
class PriceMethodFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price_method_id', EntityType::class, array(
                'class' => 'App\Entity\PriceMethod',
                'label'=> 'Price method',
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
            'data_class' => PriceMethodRequest::class
        ));
    }
}
