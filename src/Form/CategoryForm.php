<?php


namespace App\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CategoryForm
 * @package App\Form
 */
class CategoryForm extends AbstractType
{
    /**
     * @var array
     */
    private $base;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', CKEditorType::class, array(
                'config' => array(
                    'uiColor'=>'#ffffff',
                ),
                'required' => false
            ))
            ->add('save', SubmitType::class)
        ;

        $this->base = array('<script>','</script>');

        $builder
            ->get('description')->addModelTransformer(new CallbackTransformer(
                function ($description) {
                    if (!empty($description)) {
                        return str_replace($this->base, '', $description);
                    }
                    return $description;
                },
                function ($description) {
                    return str_replace($this->base, '', $description);
                }
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id'  => 'create.category.CSRF.token',
        ));
    }
}