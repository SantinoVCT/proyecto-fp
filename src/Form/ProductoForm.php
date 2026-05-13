<?php

namespace App\Form;

use App\Entity\Categoria;
use App\Entity\Producto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductoForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $textInputCss = 'form-control my-3';
        $checkInputCss = 'block m-1 size-6';
        $builder
            ->add('Nombre', TextType::class, [
                'label' => 'Nombre',
                'required' => true,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('Descripcion', TextType::class, [
                'label' => 'Descripcion',
                'required' => false,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('imagen', FileType::class, [
                'label' => 'Imagen (JPG, PNG)',
                'mapped' => false, // No mapeado directamente a la entidad
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '3M',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Por favor sube una imagen válida',
                    ])
                ],
            ])
            ->add('Caracteristicas', TextType::class, [
                'label' => 'Caracteristicas',
                'required' => false,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('Requisitos', TextType::class, [
                'label' => 'Requisitos',
                'required' => false,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('Disponible',null,['attr' => [
                'class' => $checkInputCss,
            ]])
            ->add('Precio', NumberType::class, [
                'label' => 'Precio',
                'required' => true,
                'attr' => [
                    'class' => $textInputCss,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'El precio no puede estar vacío.',
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]+(\.[0-9]+)?$/',
                        'message' => 'El precio debe ser un número válido con hasta dos decimales.',
                    ]),
                ],
            ])
            ->add('Descuento', NumberType::class, [
                'label' => 'Descuento',
                'required' => false,
                'attr' => [
                    'class' => $textInputCss,
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[1-9][0-9]?$/',
                        'message' => 'El Descuento debe ser un número válido.',
                    ]),
                ],
            ])
            ->add('Stock', NumberType::class, [
                'label' => 'Stock',
                'required' => true,
                'attr' => [
                    'class' => $textInputCss,
                ],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9][0-9]?$/',
                        'message' => 'El Stock debe ser un número válido.',
                    ]),
                ],
            ])
            ->add('Categoria', EntityType::class, [
                'class' => Categoria::class,
                'choice_label' => 'Nombre',
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Producto::class,
        ]);
    }
}
