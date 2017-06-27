<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['required' => true, 'attr' => ['placeholder' => 'Gib deinem Foto einen Titel']])
            ->add('description', TextareaType::class, ['required' => false, 'attr' => ['placeholder' => 'Hier kannst du eine kurze Beschreibung zu deiner Aufnahme ergänzen', 'rows' => 5]])
            ->add('imageFile', VichFileType::class, ['required' => true])
            ->add('submit', SubmitType::class, ['label' => 'Hochladen'])
        ;
    }
}
