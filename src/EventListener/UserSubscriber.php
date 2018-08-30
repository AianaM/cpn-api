<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 26.08.2018
 * Time: 20:27
 */

namespace App\EventListener;

use App\Entity\MediaObject;
use App\Entity\Stream;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserSubscriber implements EventSubscriber
{
    private $passwordEncoder;
    private $tokenStorage;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, TokenStorageInterface $tokenStorage)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->tokenStorage = $tokenStorage;
    }

    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postLoad',
            'onFlush'
        );
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof User && $entity->getPhoto() == null) {
            $mediaObject = $args->getEntityManager()->getRepository(MediaObject::class)->find(1);
            $entity->setPhoto($mediaObject);
        }
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();
        if ($entity instanceof User && $eventArgs->hasChangedField('password')) {
            $eventArgs->setNewValue('password', $this->encodePassword($entity, $eventArgs->getNewValue('password')));
        }
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof User) {
            $entity->setPassword($this->encodePassword($entity, $entity->getPassword()));
            $entity->setRoles(['ROLE_USER']);
        }
    }

    public function index(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof User) {
            $password = $this->passwordEncoder->encodePassword($entity, $entity->getPassword());
            $entity->setPassword($password);
        }
    }

    public function encodePassword(User $user, string $password)
    {
        return $this->passwordEncoder->encodePassword($user, $password);
    }

    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (!$entity instanceof Stream) {

                $item = $em->getClassMetadata(get_class($entity))->getName();
                $user = $this->tokenStorage->getToken()->getUser();
                $user = $user instanceof User ? $user : null;

                $stream = new Stream();
                $stream->setAction('insert');
                $stream->setItem($item);
                $stream->setItemId($entity->getId());
                $stream->setSnapshot($uow->getOriginalEntityData($entity));
                $stream->setCreatedUser($user);
                $em->persist($stream);
                $classMetadata = $em->getClassMetadata('App\Entity\Stream');
                $uow->computeChangeSet($classMetadata, $stream);

            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {

            if (!$entity instanceof Stream) {

                $item = $em->getClassMetadata(get_class($entity))->getName();
                $user = $this->tokenStorage->getToken()->getUser();
                $user = $user instanceof User ? $user : null;

                $stream = new Stream();
                $stream->setAction('update');
                $stream->setItem($item);
                $stream->setItemId($entity->getId());
                $stream->setSnapshot($uow->getEntityChangeSet($entity));
                $stream->setCreatedUser($user);
                $em->persist($stream);
                $classMetadata = $em->getClassMetadata('App\Entity\Stream');
                $uow->computeChangeSet($classMetadata, $stream);

            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if (!$entity instanceof Stream) {

                $item = $em->getClassMetadata(get_class($entity))->getName();
                $user = $this->tokenStorage->getToken()->getUser();
                $user = $user instanceof User ? $user : null;

                $stream = new Stream();
                $stream->setAction('delete');
                $stream->setItem($item);
                $stream->setItemId($entity->getId());
                $stream->setSnapshot(null);
                $stream->setCreatedUser($user);
                $em->persist($stream);
                $classMetadata = $em->getClassMetadata('App\Entity\Stream');
                $uow->computeChangeSet($classMetadata, $stream);

            }
        }

        foreach ($uow->getScheduledCollectionDeletions() as $col) {

        }

        foreach ($uow->getScheduledCollectionUpdates() as $col) {

        }
    }

}