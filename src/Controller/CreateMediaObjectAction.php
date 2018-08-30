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
use App\Entity\User;
use App\Form\MediaObjectType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class CreateMediaObjectAction
{
    private $validator;
    private $doctrine;
    private $factory;
    private $user;

    public function __construct(RegistryInterface $doctrine, FormFactoryInterface $factory, ValidatorInterface $validator, TokenStorageInterface $tokenStorage)
    {
        $this->validator = $validator;
        $this->doctrine = $doctrine;
        $this->factory = $factory;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function __invoke(Request $request): MediaObject
    {
        $mediaObject = new MediaObject();

        $form = $this->factory->create(MediaObjectType::class, $mediaObject);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->doctrine->getManager();
            $mediaObject->setCreatedUser($this->user);
            if ($form->get('avatar')->getData()) {
                $this->user->setPhoto($mediaObject);
            }
            $em->persist($mediaObject);
            $em->flush();

            $mediaObject->file = null;

            return $mediaObject;
        }
        throw new ValidationException($this->validator->validate($mediaObject));
    }
}