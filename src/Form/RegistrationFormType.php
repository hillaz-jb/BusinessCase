<?php

namespace App\Form;

use App\Entity\User;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'common.userForm.firstName',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'common.userForm.lastName',
            ])
            /*            ->add('email', TextType::class, [
                            'label' => 'common.userForm.email',
                        ])*/
            ->add('email', RepeatedType::class, [
                'type' => TextType::class,
                'invalid_message' => 'register.constraints.invalidRepeatMessage',
                'required' => true,
                'first_options' => [
                    'label' => 'common.userForm.email',
                    'row_attr' => [
                        'class' => 'col-6'
                    ],
                ],
                'second_options' => [
                    'label' => 'common.userForm.repeatEmail',
                    'row_attr' => [
                        'class' => 'col-6'
                    ],
                ],
            ])
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'common.userForm.birthDate',
                'attr' => array(
                    'min' => date('1900-01-01'),
                    'max' => date('2100-01-01'),
                )
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'register.constraints.invalidRepeatMessage',
                'required' => true,
                'mapped' => false,
                'first_options' => [
                    'label' => 'common.userForm.password',
                    'row_attr' => [
                        'class' => 'col-6'
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'register.constraints.not_blank',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => "register.constraints.password.minMessage",
                            'max' => 255,
                            'maxMessage' => "register.constraints.password.maxMessage",
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'common.userForm.repeatPassword',
                    'row_attr' => [
                        'class' => 'col-6'
                    ],
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'common.terms.title',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'register.constraints.terms.message',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'common.button.submit',
                'attr' => [
                    'mapped' => false,
                    'class' => 'btn-success'
                ],
                'row_attr' => [
                    'class' => 'mt-5 text-center'
                ]
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'messages',
            //Remove html5 validator :
            //'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
