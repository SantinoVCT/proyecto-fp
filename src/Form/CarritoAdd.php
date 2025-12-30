<?php

namespace App\Form;

use App\Entity\Carrito;
use App\Entity\Producto;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;

class CarritoAdd extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $textInputCss = 'form-control my-3';
        $builder
            ->add('Cantidad', NumberType::class, [
                'label' => 'Cantidad',
                'required' => false,
                'attr' => [
                    'class' => $textInputCss,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'La cantidad no puede estar vacía.',
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]{1,2}$/',
                        'message' => 'La cantidad debe ser un número entero positivo y menor o igual a 99.',
                    ]),
                ],
            ])
            ->add('Producto', EntityType::class, [
                'class' => Producto::class,
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
            'data_class' => Carrito::class,
        ]);
    }
}
