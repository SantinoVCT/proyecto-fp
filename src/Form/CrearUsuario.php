<?php

namespace App\Form;

use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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

class CrearUsuario extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $textInputCss = 'form-control my-3';
        $defaultRole = 'ROLE_USER';

        $data = $builder->getData();

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'class' => $textInputCss,
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
                'required' => false,
                'mapped' => false,
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

        // $builder->get('roles')
        //     ->addModelTransformer(new CallbackTransformer(
        //         // model -> view
        //         function ($rolesArray) {
        //             if (is_array($rolesArray) && count($rolesArray) > 0) {
        //                 $priority = ['ROLE_ADMIN', 'ROLE_GESTOR', 'ROLE_USER'];
        //                 foreach ($priority as $r) {
        //                     if (in_array($r, $rolesArray, true)) {
        //                         return $r;
        //                     }
        //                 }
        //                 return $rolesArray[0];
        //             }
        //             // Default to ROLE_USER when there are no roles yet
        //             return 'ROLE_USER';
        //         },
        //         // view -> model
        //         function ($roleString) {
        //             if (null === $roleString || '' === $roleString) {
        //                 return [];
        //             }
        //             switch ($roleString) {
        //                 case 'ROLE_ADMIN':
        //                     return ['ROLE_ADMIN', 'ROLE_GESTOR', 'ROLE_USER'];
        //                 case 'ROLE_GESTOR':
        //                     return ['ROLE_GESTOR', 'ROLE_USER'];
        //                 case 'ROLE_USER':
        //                 default:
        //                     return ['ROLE_USER'];
        //             }
        //         }
        //     ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuario::class,
        ]);
    }
}
