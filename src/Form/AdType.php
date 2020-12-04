<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{
    /**
     * Undocumented function
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options = []){
         return array_merge ([
             'label' => $label,
             'attr' => [
                 'placeholder' => $placeholder
               ]
           ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre","Tapez un super titre pour votre annonce"))
            ->add('slug',
                   TextType::class,
                   $this->getConfiguration("Adresse web", "Tapez l'adresse web",[
                   'required' => false
                   ])
            )
            ->add('price')
            ->add('introduction')
            ->add('content', null,[
                'required'   => false,
            ])
            ->add('coverImage')
            ->add('rooms')
            ->add(
                'images',
                CollectionType::class,[
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}