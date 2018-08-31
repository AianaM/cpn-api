<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 31.08.2018
 * Time: 15:38
 */

namespace App\EventListener;


use App\Entity\Stream;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

                $snapshot = $uow->getOriginalEntityData($entity);
                if (in_array('password', $snapshot)) {
                    $snapshot['password'] = "***";
                }
                $stream->setSnapshot($snapshot);
                $stream->setItemId($entity->getId());
                $stream->setItem($em->getClassMetadata(get_class($entity))->getName());


                $this->stream[] = $stream;
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (!$entity instanceof Stream && !$entity instanceof RefreshToken) {

                $stream = new Stream();

                $stream->setCreatedUser($this->getUser());
                $stream->setAction('insert');
                $stream->setSnapshot($uow->getEntityChangeSet($entity));
                $stream->setItemId($entity->getId());
                $stream->setItem($em->getClassMetadata(get_class($entity))->getName());


                $this->stream[] = $stream;
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {

        }

        foreach ($uow->getScheduledCollectionDeletions() as $col) {

        }

        foreach ($uow->getScheduledCollectionUpdates() as $col) {

        }

        $uow->computeChangeSets();
    }

    private function getUser()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        return $user instanceof User ? $user : null;
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

}