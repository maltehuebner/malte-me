<?php

namespace AppBundle\Admin;

use AppBundle\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InvitationAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'displayDateTime',
    ];

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Einladungsdaten', ['class' => 'col-xs-6'])
            ->add('code', TextType::class)
            ->add('inviteeName', TextType::class)
            ->add('topic', TextareaType::class)
            ->add('intro', TextareaType::class)
            ->end()

            ->with('Fotoinformationen', ['class' => 'col-xs-6'])
            ->add('proposedTitle', TextType::class, ['required' => false])
            ->add('proposedDescription', TextareaType::class, ['required' => false])
            ->end()

            ->with('Benutzerdaten', ['class' => 'col-xs-6'])
            ->add('createdBy', EntityType::class, ['class' => User::class, 'required' => false])
            ->add('acceptedBy', EntityType::class, ['class' => User::class, 'required' => false])
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('code')
            ->add('topic')
            ->add('inviteeName')
            ->add('createdBy')
            ->add('acceptedBy')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('code')
            ->add('topic')
            ->add('inviteeName')
            ->add('createdBy')
            ->add('acceptedBy')
        ;
    }
}
