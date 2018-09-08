<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 08.09.2018
 * Time: 21:22
 */

namespace App\EventListener;


use App\Entity\MediaObject;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class MediaObjectSubscriber implements EventSubscriber
{
    private $imagineCacheManager;
    private $vichUploaderHelper;

    public function __construct(CacheManager $imagineCacheManager, UploaderHelper $vichUploaderHelper)
    {
        $this->imagineCacheManager = $imagineCacheManager;
        $this->vichUploaderHelper = $vichUploaderHelper;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postLoad',
        );
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof MediaObject) {
            $path = $this->vichUploaderHelper->asset($entity, 'file');
            $entity->links = [
                'squared_thumbnail' => $this->imagineCacheManager->getBrowserPath($path, 'squared_thumbnail')
            ];
        }
    }

}