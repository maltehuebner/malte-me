<?php declare(strict_types=1);

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CommentAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'dateTime',
    ];

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('message')
            ->add(
                'photo',
                EntityType::class,
                [
                    'class' => 'AppBundle:Photo',
                    'choice_label' => 'title'
                ])
            ->add(
                'user',
                EntityType::class,
                [
                    'class' => 'AppBundle:User',
                    'choice_label' => 'username'
                ])
            ->add(
                'dateTime',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd HH:mm:ss'
                ]
            )
            ->add('enabled', CheckboxType::class, ['required' => false])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);

        $listMapper
            ->addIdentifier('message')
            ->add('photo.title')
            ->add('user.username')
            ->add('dateTime')
            ->add('enabled')
        ;
    }
}
