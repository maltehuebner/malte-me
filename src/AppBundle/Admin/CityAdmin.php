<?php declare(strict_types=1);

namespace AppBundle\Admin;

use AppBundle\Entity\City;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CityAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Stadt', ['class' => 'col-xs-6'])
            ->add('name', TextType::class)
            ->add('title', TextType::class)
            ->add('slogan', TextType::class)
            ->add('hostname', TextType::class)
            ->end()

            ->with('Logo', ['class' => 'col-xs-6'])
            ->add('imageFile', VichFileType::class, ['required' => false])
            ->end()

            ->with('SEO', ['class' => 'col-xs-6'])
            ->add('seoDescription', TextAreaType::class, ['required' => false])
            ->add('seoKeywords', TextType::class, ['required' => false])
            ->add('missionText', TextAreaType::class, ['required' => false])
            ->add('archiveIntroText', TextAreaType::class, ['required' => false])
            ->add('piwikTrackingCode', TextAreaType::class, ['required' => false])
            ->end()

            ->with('Menu', ['class' => 'col-xs-6'])
            ->add('showMenuMission', CheckboxType::class, ['required' => false])
            ->add('showMenuUpload', CheckboxType::class, ['required' => false])
            ->add('publicVisible', CheckboxType::class, ['required' => false])
            ->end()

            ->with('Call to action', ['class' => 'col-xs-6'])
            ->add('callToActionTitle', TextType::class, ['required' => false])
            ->add('callToActionText', TextAreaType::class, ['required' => false])
            ->end()

            ->with('Critical Mass', ['class' => 'col-xs-6'])
            ->add('criticalmassTitle', TextType::class, ['required' => false])
            ->add('criticalmassCitySlug', TextType::class, ['required' => false])
            ->end()

            ->with('Coord', ['class' => 'col-xs-6'])
            ->add('latitude', NumberType::class, ['required' => false])
            ->add('longitude', NumberType::class, ['required' => false])
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('title')
            ->add('slug')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        unset($this->listModes['mosaic']);

        $listMapper
            ->addIdentifier('title')
            ->add('name')
            ->add('hostname')
        ;
    }
}
