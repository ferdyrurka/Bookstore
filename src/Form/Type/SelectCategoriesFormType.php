<?php


namespace App\Form\Type;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Request\SelectCategoriesRequest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SelectCategoriesFormType
 * @package App\Form\Type
 */
class SelectCategoriesFormType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categories_id', EntityType::class, array(
                'class' => Category::class,
                'multiple' => true,
                'expanded' => true,
                'label' => 'Categories',
                'empty_data' => null,
                'required' => false,
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SelectCategoriesRequest::class
        ));
    }
}
