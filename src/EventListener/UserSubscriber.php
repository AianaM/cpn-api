<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 26.08.2018
 * Time: 20:27
 */

namespace App\EventListener;

use App\Entity\MediaObject;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserSubscriber implements EventSubscriber
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getSubscribedEvents()
    {
        return array(
            'prePersist',
            'preUpdate',
            'postLoad'
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
        } elseif ($entity instanceof MediaObject) {
            $entity->setCreatedAt(new \DateTimeImmutable());
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
}