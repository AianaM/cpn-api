<?php
/**
 * Created by PhpStorm.
 * User: aiana
 * Date: 10.09.2018
 * Time: 22:08
 */

namespace App\Controller;


use App\Entity\MediaObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Vich\UploaderBundle\Handler\DownloadHandler;

class MediaObjectController extends AbstractController
{
    /**
     * @Route("/m/{id}", name="getOriginalFile", methods={"GET"},
     *     requirements={
     *          "id": "\d+"
     *     })
     * @param $id
     * @param DownloadHandler $downloadHandler
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function originalAction($id, DownloadHandler $downloadHandler)
    {
        $media = $this->getDoctrine()->getRepository(MediaObject::class)
            ->find($id);
        return $downloadHandler->downloadObject($media, $fileField = 'file');
    }

    /**
     * @Route("/m/{id}/{filter}", name="getThumbnailFile", methods={"GET"},
     *     requirements={
     *          "thumbnail": "squared_thumbnail_64|squared_thumbnail",
     *          "id": "\d+"
     *     })
     * @param $id
     * @param $filter
     * @param CacheManager $imagineCacheManager
     * @param UploaderHelper $vichUploaderHelper
     * @return RedirectResponse
     */
    public function thumbnailAction($id, $filter, CacheManager $imagineCacheManager, UploaderHelper $vichUploaderHelper)
    {
        $media = $this->getDoctrine()->getRepository(MediaObject::class)
            ->find($id);

        $path = $vichUploaderHelper->asset($media, 'file');
        return new RedirectResponse($imagineCacheManager->getBrowserPath($path, $filter), 302);
//        return new RedirectResponse($imagineCacheManager->resolve($path, $filter), 302);
    }

}
