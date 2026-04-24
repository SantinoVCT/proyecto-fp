<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InfoUsuarioForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $textInputCss = 'form-control my-3';
        $builder
            ->add('Nombre', TextType::class, [
                'label' => 'Nombre',
                'required' => true,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('Apellidos', TextType::class, [
                'label' => 'Apellidos',
                'required' => true,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('Direccion', TextType::class, [
                'label' => 'Direccion',
                'required' => true,
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
