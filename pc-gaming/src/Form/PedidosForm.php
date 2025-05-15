<?php

namespace App\Form;

use App\Entity\Pedidos;
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

class PedidosForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $textInputCss = 'block w-full px-4 py-2 mt-2 mb-2 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm';
        $builder
            ->add('Cantidad', NumberType::class, [
                'label' => 'Cantidad',
                'required' => false,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('Estado', ChoiceType::class, [
                'label' => 'Estado',
                'required' => true,
                'choices'  => [
                    'Preparando' => 0,
                    'En Reparto' => 1,
                    'Enviado' => 2,
                ],
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('FechaPedido', null, [
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('Usuario', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'Nombre',
                'attr' => [
                    'class' => $textInputCss,
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
            'data_class' => Pedidos::class,
        ]);
    }
}
