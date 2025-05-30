<?php

namespace App\Form;

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

class UsuarioForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $textInputCss = 'block w-full px-4 py-2 mt-1 text-gray-900 placeholder-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm';
        $builder
            ->add('email', TextType::class, [
                'label' => 'email',
                'required' => false,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'roles',
                'required' => false,
                'multiple' => true,
                'choices'  => [
                    'Administrador' => "ROLE_ADMIN",
                ],
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'roles',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'choices'  => [
                    'Administrador' => "ROLE_ADMIN",
                    'Cliente' => [],
                ],
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('password', TextType::class, [
                'label' => 'password',
                'required' => false,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('Nombre', TextType::class, [
                'label' => 'Nombre',
                'required' => false,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('Apellidos', TextType::class, [
                'label' => 'Apellidos',
                'required' => false,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('Direccion', TextType::class, [
                'label' => 'Direccion',
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
            'data_class' => Usuario::class,
        ]);
    }
}
