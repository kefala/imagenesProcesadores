<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Image;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Image controller.
 *
 * @Route("/images")
 */
class ImageController extends Controller
{
    /**
     * Lists all Image entities.
     *
     * @Route("/", name="images_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('AppBundle:Image')->findAll();

        return $this->render('image/index.html.twig', array(
            'images' => $images,
            ));
    }

    /**
     * Finds and displays a Image entity.
     *
     * @Route("/{id}", name="images_show")
     * @Method("GET")
     */
    public function showAction(Image $image)
    {

        return $this->render('image/show.html.twig', array(
            'image' => $image,
            ));
    }

    /**
     * Finds and displays a Image entity.
     *
     * @Route("/api/{id}", name="api_create")
     * @Method("GET")
     */
    public function apiImage($id)
    {
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();

        $image = $em->getRepository('AppBundle:Image')->find($id);

        if (!empty($image)) {
            $response->setData(array(
                'nombre' => $image->getName(),
                'path' => $image->getUrl()
            ));
        } else {
            $response->setData(array());
        }
        
        

        return $response;
    }

    /**
     * Finds and displays a Image entity.
     *
     * @Route("/api/image/create/{name}/{url}", name="create_image")
     * @Method("GET")
     */
    public function addImage($name, $url)
    {
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('AppBundle:Image')->findAll();

        if (sizeof($images) < 8) {
            $image = new Image();
            $image->setName($name);
            $image->setUrl($url);
            
            $em->persist($image);
            $em->flush();

            $response->setData(array(
                'data' => $image->getId()
            ));  
        } else {
            $response->setData(array());  
        }
        
        return $response;
    }

    /**
     * Finds and displays a Image entity.
     *
     * @Route("/api/images/getAll", name="create_image")
     * @Method("GET")
     */
    public function getAllApi()
    {
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('AppBundle:Image')->findAll();

        $dao = [];

        foreach ($images as $key => $value) {
            $dao[$key] = array(
                'nombre' => $value->getName(), 
                'url' => $value->getUrl(), 
            );
        }

        $response->setData(array(
            'images' => $dao
        )); 
        
        return $response;
    }

}
