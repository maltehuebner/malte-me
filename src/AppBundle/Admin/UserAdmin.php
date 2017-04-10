<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    ];

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('User data', ['class' => 'col-md-6'])
            ->add('username')
            ->add('email')
            ->add('firstname')
            ->add('lastname')
            ->end()
            ->with('Access rights', ['class' => 'col-md-6'])
            ->add('moderated', CheckboxType::class)
            ->add('enabled', CheckboxType::class)
            ->end()
            ->with('Strava data', ['class' => 'col-md-6'])
            ->add('stravaId')
            ->add('stravaAccessToken')
            ->end()
            ->with('twitter data', ['class' => 'col-md-6'])
            ->add('twitterId')
            ->add('twitterAccessToken')
            ->end()
            ->with('facebook data', ['class' => 'col-md-6'])
            ->add('facebookId')
            ->add('facebookAccessToken')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('username');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('createdAt')
        ;
    }
}
