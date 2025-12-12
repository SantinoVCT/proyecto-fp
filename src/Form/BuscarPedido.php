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

class BuscarPedido extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $textInputCss = 'form-control my-3';
        $builder
            ->add('CodigoPedido', NumberType::class, [
                'label' => 'Codigo Pedido',
                'required' => false,
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
