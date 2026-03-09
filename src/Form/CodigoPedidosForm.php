<?php

namespace App\Form;

use App\Entity\CodigoPedido;
use App\Entity\Producto;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;

class CodigoPedidosForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $textInputCss = 'form-control my-3';
        $builder
            ->add('Estado', ChoiceType::class, [
                'label' => 'Estado',
                'required' => false,
                'choices'  => [
                    'Preparando' => 0,
                    'En Reparto' => 1,
                    'Enviado' => 2,
                ],
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('Fecha', null, [
                'required' => true,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('codigo', IntegerType::class, [
                'label' => 'Código',
                'required' => false,
                'attr' => [
                    'class' => $textInputCss,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'El código no puede estar vacío.',
                    ]),
                    new Regex([
                        'pattern' => '/^[0-9]{4}$/',
                        'message' => 'El código debe ser un número entero positivo y menor o igual a 9999999999.',
                    ]),
                ],
            ])
            ->add('Cliente', EntityType::class, [
                'class' => Usuario::class,
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
            'data_class' => CodigoPedido::class,
        ]);
    }
}
