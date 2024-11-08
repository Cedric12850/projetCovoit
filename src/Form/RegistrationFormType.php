<?php

namespace App\Form;

use App\Entity\Town;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
                'required' => false,
                'mapped' => false,      // for edit profile 
                'data_class' => null ])           
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
                'label' => 'Code postal : ',
                'attr' => ['class' => 'js-zip-code',],
                'mapped' => false,
                'required' => false,
                ])
            ->add('town', HiddenType::class, [
                'mapped' => false,
                ])
            ->add('driving_license', CheckboxType::class,[
                'label' => 'Permis de conduire : ',
                'help' => 'En cochant cette case vous déclarez sur l\'honneur que vous êtes en possession d\'un permis valide.',
                'required' => false,
                'data_class' => null 
                ])  
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les CGU : ',
                'help' => 'En cochant cette case vous déclarez avoir pris connaissance et accepter les Conditions Générales d\'Utilisations visible à <a href="conditions_generales_utilisations">cette page</a>.',
                'help_html' => true,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les Conditons Générales d\'Utilsations.',
                    ]),
                ],
            ]);
            
            $builder->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques',
                'mapped' => false,
                'required'=> !$options['is_edit'], // Obligatoire uniquement si ce n'est pas une édition
                'first_options' => ['label'=> 'Mot de passe',
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => !$options['is_edit'] ?  [
                    new NotBlank([
                        'message' => 'Entrez votre mot de passe : ',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre mot de passe doit être au moins de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]: [],
                ],
                'second_options' => [
                    'label'=> 'Répétez votre mot de passe',
                    'attr' => ['autocomplete' => 'new-password'],
                ],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                
            ])
            
/*             ->add ('enregistrer', SubmitType::class) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false, // Ajoutez l'option 'is_edit' par défaut à false
        ]);
    }
}
