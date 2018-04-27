<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', null, [
                "label" => "Your first name",
                "attr" => ["class" => "form-control"]
            ])
            ->add('name', null, [
                "label" => "Your name",
                "attr" => ["class" => "form-control"]
            ])
            ->add('username', null, [
                "label" => "Your Username",
                "attr" => ["class" => "form-control"]
            ])
            ->add('mail', EmailType::class, [
                "label" => "Your Email",
                "attr" => ["class" => "form-control"]
            ])
            ->add('profilePicture', FileType::class, [
                'label' => 'Image',
                "attr" => ["class" => "form-control"]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => ['label' => 'Password', "attr" => ["class" => "form-control"]],
                'second_options' => ['label' => 'Repeat Password', "attr" => ["class" => "form-control"]],

            ])
            ->add('submit', SubmitType::class, [
                "label" => "Create my account!",
                "attr" => ["class" => "btn btn-primary"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
