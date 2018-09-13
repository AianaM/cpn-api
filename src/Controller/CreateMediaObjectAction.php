<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 27.08.2018
 * Time: 15:10
 */

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use App\Entity\MediaObject;
use App\Form\MediaObjectType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateMediaObjectAction
{
    private $validator;
    private $doctrine;
    private $factory;
    private $tokenStorage;

    public function __construct(RegistryInterface $doctrine, FormFactoryInterface $factory, ValidatorInterface $validator, TokenStorageInterface $tokenStorage)
    {
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->factory = $factory;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(Request $request): MediaObject
    {
        $auth = $this->tokenStorage->getToken()->getUser();

        $mediaObject = new MediaObject();

        $form = $this->factory->create(MediaObjectType::class, $mediaObject);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            foreach ($mediaObject->getUsers() as $user) {
                if($user != $auth) {
                    throw new \Error('Вы не можете изменить фото другого пользователя');
                }
            }
            $em->persist($mediaObject);
            $em->flush();

            $mediaObject->file = null;

            return $mediaObject;
        }
        throw new ValidationException($this->validator->validate($mediaObject));
    }
}