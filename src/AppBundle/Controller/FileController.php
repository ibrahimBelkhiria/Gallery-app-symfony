<?php

namespace AppBundle\Controller;

use AppBundle\Entity\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("api/upload",name="upload_image")
     * @Method({"POST"})
     */
    public function uploadImage(Request $request)
    {
               $file= new File();

        $uploadedImage=$request->files->get('file');


        /**
         * @var UploadedFile $image
         */
            $image=$uploadedImage;

            $imageName=md5(uniqid()).'.'.$image->guessExtension();

            $image->move($this->getParameter('image_directory'),$imageName);

            $file->setImage($imageName);
            $em=$this->getDoctrine()->getManager();
            $em->persist($file);
            $em->flush();


        $response=array(

            'code'=>0,
            'message'=>'File Uploaded with success!',
            'errors'=>null,
            'result'=>null

        );


        return new JsonResponse($response,Response::HTTP_CREATED);





    }

    /**
     * @Route("api/images",name="show_images")
     * @Method({"GET"})
     * @return JsonResponse
     */

    public function getImages()
    {


        $images=$this->getDoctrine()->getRepository('AppBundle:File')->findAll();


        $data=$this->get('jms_serializer')->serialize($images,'json');

        $response=array(

            'message'=>'images loaded with sucesss',
            'result' => json_decode($data)

        );

        return new JsonResponse($response,200);

    }


    /**
     * @param $id
     * @Route("api/image/{id}",name="show_image")
     * @Method({"GET"})
     * @return JsonResponse
     */
    public function getImage($id)
    {
        $imageName=$this->getDoctrine()->getRepository('AppBundle:File')->find($id)->getImage();


        $response=array(

            'code'=>0,
            'message'=>'get image with success!',
            'errors'=>null,
            'result'=>$imageName

        );

        return new JsonResponse($response,200);




    }





















}
