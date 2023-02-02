<?php

namespace App\Form;

use App\Entity\Ressource;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RessourceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('lien', FileType::class, [
                'label' => 'Lien',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
//                        'mimeTypes' => [
//                            'image/jpg',
//                            'image/png'
//                        ],
                        'mimeTypesMessage' => 'Sélectionner une ressource valide',
                    ])
                ],
            ])
            ->add('is_valid')
            ->add('user')
            ->add('action')
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $ressource = $event->getData();
            $form = $event->getForm();

            if ($ressource->isAddition) {
                $form->remove('is_valid')
                    ->remove('user')
                    ->remove('action')
                ;
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ressource::class,
        ]);
    }
}
