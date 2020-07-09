<?php


namespace App\Request\Security;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class EnterNewPasswordRequest extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, ['label' => 'Enter new password'])
            ->add('repeatedPassword', PasswordType::class, ['mapped' => false, 'label' => 'Repeat new password'])
            ->add('save', SubmitType::class);
    }
}