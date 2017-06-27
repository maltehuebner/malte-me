<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
            ->add('username', TextType::class)
            ->add('email', TextType::class)
            ->add('firstname', TextType::class, ['required' => false])
            ->add('lastname', TextType::class, ['required' => false])
            ->end()
            ->with('Access rights', ['class' => 'col-md-6'])
            ->add('moderated', CheckboxType::class, ['required' => false])
            ->add('enabled', CheckboxType::class, ['required' => false])
            ->end()
            ->with('Strava data', ['class' => 'col-md-6'])
            ->add('stravaId', TextType::class, ['required' => false])
            ->add('stravaAccessToken', TextType::class, ['required' => false])
            ->end()
            ->with('twitter data', ['class' => 'col-md-6'])
            ->add('twitterId', TextType::class, ['required' => false])
            ->add('twitterAccessToken', TextType::class, ['required' => false])
            ->end()
            ->with('facebook data', ['class' => 'col-md-6'])
            ->add('facebookId', TextType::class, ['required' => false])
            ->add('facebookAccessToken', TextType::class, ['required' => false])
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
