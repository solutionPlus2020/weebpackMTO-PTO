<?php

namespace App\Form;

use App\Entity\Descriptions;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DescriptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('titre')
            ->add('opption')
            ->add('contenu',CKEditorType::class,array(
                'config'=>array(
                    'height'=> 260,
                    'width'=> 1000,
                     'uiColor'=>'#3c8dbc',

                ),

             ))
            ->add('Jours')
            ->add('produits')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Descriptions::class,
        ]);
    }
}
