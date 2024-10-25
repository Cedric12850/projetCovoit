<?php

namespace App\Form;

use App\Entity\Town;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationFormType extends AbstractType
{
    #[Route('/register', name: 'app_register')]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo', FileType::class, [
                'label' => 'Photo : ',
                'required' => false, ])           
            ->add('email',TextType::class,[
                'label' => 'Adresse courriel : '])
            ->add('pseudo', TextType::class,[
                    'label' => 'Pseudo : '])
            ->add('firstname', TextType::class,[
                'label' => 'Prénom : '])
            ->add('lastname', TextType::class,[
                'label' => 'Nom : '])
            ->add('phone', TextType::class,[
                'label' => 'Téléphone : '])
            ->add('address', TextType::class,[
                'label' => 'Adresse : '])
            ->add('zip_code', TextType::class,[
                'label' => 'Code postal : '])
            ->add('town', EntityType::class,[
                'class' => Town::class,
                'choice_label' => 'name',
                'label' => 'Ville : '])
            ->add('driving_license', CheckboxType::class,[
                'label' => 'Permis de conduire : '])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les CGU : ',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les Conditons Générales d\'Utilsations.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'label' => 'Mot de passe : ',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre mot de passe : ',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit être au moins de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'mapped' => false, // Champ non mappé
                'label' => 'Confirmer le mot de passe : ',
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez confirmer votre mot de passe',
                    ]),
                    new EqualTo([
                        'value' => '',
                        'message' => 'Les mots de passe doivent correspondre.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
