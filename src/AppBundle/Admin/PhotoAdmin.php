<?php

namespace AppBundle\Admin;

use AppBundle\Entity\City;
use AppBundle\Entity\Photo;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\CoreBundle\Model\Metadata;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PhotoAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'displayDateTime',
    ];

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Fotoinformationen', ['class' => 'col-xs-6'])
            ->add('title', TextType::class)
            ->add('description', TextareaType::class, ['required' => false])
            ->add('source', TextType::class, ['required' => false])
            ->end()

            ->with('Metainformationen', ['class' => 'col-xs-6'])
            ->add('cities', ModelType::class, [
                'by_reference' => true,
                'property' => 'name',
                'multiple' => true,
                'class' => City::class,
                'attr' => [
                    'data-sonata-select2' => 'false',
                ]
            ])
            ->add('slug', TextType::class)
            ->add('enabled', CheckboxType::class, ['required' => false])
            ->add('highlighted', CheckboxType::class, ['required' => false])
            ->add('sponsored', CheckboxType::class, ['required' => false])
            ->add('affiliated', CheckboxType::class, ['required' => false])
            ->end()

            ->with('Ortsdaten', ['class' => 'col-xs-6'])
            ->add('location', TextType::class, ['required' => false])
            ->add('latitude', TextType::class, ['required' => false])
            ->add('longitude', TextType::class, ['required' => false])
            ->end()

            ->with('Daten', ['class' => 'col-xs-6'])
            ->add(
                'dateTime',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd HH:mm:ss'
                ]
            )
            ->add(
                'displayDateTime',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd HH:mm:ss'
                ]
            )
            ->add(
                'updatedAt',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd HH:mm:ss'
                ]
            )
            ->add(
                'createdAt',
                DateTimeType::class,
                [
                    'widget' => 'single_text',
                    'format' => 'yyyy-MM-dd HH:mm:ss'
                ]
            )
            ->end()

            ->with('Datei', ['class' => 'col-xs-6'])
            ->add('imageFile', VichFileType::class, ['required' => false])
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('enabled')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('user')
            ->add('location')
            ->add('cities')
            ->add('dateTime')
            ->add('displayDateTime')
            ->add('enabled')
        ;
    }

    /**
     * @param Photo $photo
     * @return Metadata
     */
    public function getObjectMetadata($photo): Metadata
    {
        $filename = sprintf('/photos/%s', $photo->getImageName());

        $imageUrl = $this->getLiipImagineCacheManager()->getBrowserPath($filename, 'thumb');

        return new Metadata($photo->getTitle(), $photo->getDescription(), $imageUrl);
    }

    protected function getLiipImagineCacheManager(): CacheManager
    {
        return $this->getConfigurationPool()->getContainer()->get('liip_imagine.cache.manager');
    }
}
