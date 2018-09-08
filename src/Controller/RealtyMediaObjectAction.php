<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 07.09.2018
 * Time: 20:46
 */

namespace App\Controller;

use App\Entity\Realty;
use App\Form\RealtyMediaObjectType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class RealtyMediaObjectAction
{
    private $doctrine;
    private $factory;

    public function __construct(RegistryInterface $doctrine, FormFactoryInterface $factory)
    {
        $this->doctrine = $doctrine;
        $this->factory = $factory;
    }

    public function __invoke($id, Request $request): Realty
    {
        $em = $this->doctrine->getManager();
        $realty = $em->getRepository(Realty::class)->find($id);

        $form = $this->factory->create(RealtyMediaObjectType::class, $realty);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($realty);
            $em->flush();

            return $realty;
        }
        throw new \Error('Form not valid!');
    }
}