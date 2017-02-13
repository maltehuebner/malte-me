<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PhotoAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Fotoinformationen', ['class' => 'col-xs-6'])
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->end()

            ->with('Metainformationen', ['class' => 'col-xs-6'])
            ->add('slug', TextType::class)
            ->add('enabled', CheckboxType::class)
            ->add('highlighted', CheckboxType::class)
            ->add('sponsored', CheckboxType::class)
            ->add('affiliated', CheckboxType::class)
            ->end()

            ->with('Daten', ['class' => 'col-xs-6'])
            ->add('dateTime', DateTimeType::class)
            ->add('displayDateTime', DateTimeType::class)
            ->add('updatedAt', DateTimeType::class)
            ->add('createdAt', DateTimeType::class)
            ->end()

            ->with('Datei', ['class' => 'col-xs-6'])
            ->add('imageFile', VichFileType::class)
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title');
    }
}
