<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PhotoEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['required' => false, 'attr' => ['placeholder' => 'Gib deinem Foto einen Titel']])
            ->add('description', TextareaType::class, ['required' => false, 'attr' => ['rows' => 5]])
            ->add('submit', SubmitType::class, ['label' => 'Speichern'])
        ;
    }
}
