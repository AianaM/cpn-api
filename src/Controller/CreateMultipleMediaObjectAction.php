<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 08.09.2018
 * Time: 18:59
 */

namespace App\Controller;

use App\Entity\MediaObject;
use App\Form\MultiMediaObjectsType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CreateMultipleMediaObjectAction
{
    private $doctrine;
    private $factory;
    private $authChecker;

    public function __construct(RegistryInterface $doctrine, FormFactoryInterface $factory, AuthorizationCheckerInterface $authChecker)
    {
        $this->doctrine = $doctrine;
        $this->factory = $factory;
        $this->authChecker = $authChecker;
    }

    public function __invoke(Request $request)
    {
        $form = $this->factory->create(MultiMediaObjectsType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $data = $form->getData();
            $mediaObjects = $data['mediaObjects'];

            foreach ($mediaObjects as $mediaObject) {
                if ($mediaObject instanceof MediaObject) {
                    if (false === $this->authChecker->isGranted('ROLE_ADMIN') && false === $this->authChecker->isGranted('ROLE_MANAGER') &&
                        !$mediaObject->getRealties()->isEmpty() && !$mediaObject->getAddresses()->isEmpty()) {
                        $em->clear();
                        throw new AccessDeniedException('Только менеджеры могут добавлять фотографии к объектам');
                    }
                    $em->persist($mediaObject);
                }
            }
            $em->flush();

            return $mediaObjects;
        }
        throw new \Error('Form not valid!');
    }
}