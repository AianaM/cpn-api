<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 08.09.2018
 * Time: 18:59
 */

namespace App\Controller;

use App\Form\MultiMediaObjectsType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;


class CreateMultipleMediaObjectAction
{
    private $doctrine;
    private $factory;

    public function __construct(RegistryInterface $doctrine, FormFactoryInterface $factory)
    {
        $this->doctrine = $doctrine;
        $this->factory = $factory;
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
                $em->persist($mediaObject);
            }
            $em->flush();

            return $mediaObjects;
        }
        throw new \Error('Form not valid!');
    }
}