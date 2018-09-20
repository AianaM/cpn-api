<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 31.08.2018
 * Time: 15:38
 */

namespace App\EventListener;

use App\Entity\MediaObject;
use App\Entity\Realty;
use App\Entity\Stream;
use App\Entity\User;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class StreamSubscriber implements EventSubscriber
{
    private $tokenStorage;
    private $stream = [];

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function getSubscribedEvents()
    {
        return array(
            'onFlush',
            'postFlush'
        );
    }

    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$entity instanceof Stream && !$entity instanceof RefreshToken) {

                $stream = new Stream();

                $stream->setCreatedUser($this->getUser());
                $stream->setAction('insert');

                $snapshot = $this->convert($entity);

                $stream->setSnapshot($snapshot);
                $stream->setItemId($entity->getId());
                $item = new \ReflectionClass(get_class($entity));
                $stream->setItem($item->getShortName());

                $this->stream[] = $stream;
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof Stream && !$entity instanceof RefreshToken && !empty($uow->getEntityChangeSet($entity))) {

                $stream = new Stream();

                $stream->setCreatedUser($this->getUser());
                $stream->setAction('update');

                $snapshot = $this->convert($entity);

                $stream->setSnapshot($snapshot);
                $stream->setItemId($entity->getId());

                $item = new \ReflectionClass(get_class($entity));
                $stream->setItem($item->getShortName());

                $this->stream[] = $stream;
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if (!$entity instanceof Stream && !$entity instanceof RefreshToken) {

                $stream = new Stream();

                $stream->setCreatedUser($this->getUser());
                $stream->setAction('delete');

                $snapshot = $this->convert($entity);

                $stream->setSnapshot($snapshot);
                $stream->setItemId($entity->getId());
                $item = new \ReflectionClass(get_class($entity));
                $stream->setItem($item->getShortName());

                $this->stream[] = $stream;
            }
        }

        foreach ($uow->getScheduledCollectionDeletions() as $col) {

        }

        foreach ($uow->getScheduledCollectionUpdates() as $col) {

        }


        $uow->computeChangeSets();
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        if (!empty($this->stream)) {
            $em = $args->getEntityManager();
            foreach ($this->stream as $stream) {
                $em->persist($stream);
            }

            $this->stream = [];
            $em->flush();
        }
    }

    private function getUser()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        return $user instanceof User ? $user : null;
    }

    private function dateTime($dateTime)
    {
        if ($dateTime instanceof \DateTimeImmutable) {
            return $dateTime->format(\DateTime::ISO8601);
        }
        if ($dateTime instanceof \DateTime) {
            return $dateTime->format(\DateTime::ISO8601);
        }
    }

    private function convert($entity)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $callback = function ($dateTime) {
            return $this->dateTime($dateTime);
        };

        $normalizer->setCallbacks(array('createdAt' => $callback, 'updatedAt' => $callback));

        $serializer = new Serializer(array($normalizer));
        $normalizer->setIgnoredAttributes(array('file', 'password'));
        if ($entity instanceof Realty) {
            return $serializer->normalize($entity, null, ['groups' => ['realty:output']]);
        }
        if ($entity instanceof MediaObject) {
            return $serializer->normalize($entity, null, ['groups' => ['media']]);
        }
        return $serializer->normalize($entity);
    }

}