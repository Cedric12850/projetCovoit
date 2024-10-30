<?php

namespace App\Form;

use App\Entity\Town;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Routing\Attribute\Route;


use function PHPSTORM_META\type;

class RegistrationFormType extends AbstractType
{
    #[Route('/register', name: 'app_register')]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo', FileType::class, [
                'label' => 'Photo : ',
                'required' => false,
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
                ])
            ->add('town', HiddenType::class, [
                'mapped' => false,
            ])
            // ->add('town', EntityType::class, [
            //     'class' => Town::class,
            //     'choice_label' => 'name',
            //     'label' => 'Ville : ',
            //     'placeholder' => 'Sélectionnez une ville',
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control',
            //         'id' => 'town-select',
            //         'style' => 'display: none;',
            //     ],
            //     ])
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
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques',
                'mapped' => false,
                'required'=> true,
                'first_options' => ['label'=> 'Mot de passe',
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre mot de passe : ',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre mot de passe doit être au moins de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                ],
                'second_options' => [
                    'label'=> 'Répétez votre mot de passe',
                    'attr' => ['autocomplete' => 'new-password'],
                ],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                
            ])
            
            ->add ('enregistrer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
