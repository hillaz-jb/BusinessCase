<?php

namespace App\Form;

use App\Entity\User;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
            ->add('email', TextType::class, [
                'label' => 'common.userForm.email',
            ])
            ->add('birthDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'common.userForm.birthDate',
                'attr' => array(
                    'min' => date('1900-01-01'),
                    'max' => mktime(0, 0, 0, date("m")-1, date("d"),   date("Y")),
                )
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
            ->add('plainPassword', PasswordType::class, [
                'label' => 'common.userForm.password',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'register.constraints.password.message',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => "register.constraints.password.minMessage",
                        'max' => 255,
                        'maxMessage' => "register.constraints.password.maxMessage",
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
                    'class' => 'mb-3 text-center'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'messages',
        ]);
    }
}
