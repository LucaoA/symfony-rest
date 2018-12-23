<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

/**
 * Task controller.
 *
 * @Route("api/v1/tasks")
 */
class ApiController extends Controller
{
    /**
     * Lists all task entities. 
     *
     * @Route("/", name="task_list", methods={"GET"})
     */
    public function listAction()
    {   
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AppBundle:Task')->findAll();
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = array(new DateTimeNormalizer(), new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        return new JsonResponse(
            $serializer->serialize($tasks, 'json'),
            Response::HTTP_OK,
            [],
            true
        );
    }

     /**
     *  Creates a new task entity.
     *
     * @Route("/", name="task_create", methods={"POST"})
     * @Method("POST")
     */
    public function createAction(Request $request)
    {       
        $body = $request->getContent();
        $data = \json_decode($body, true);

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->submit($data);
        
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = array(new DateTimeNormalizer(), new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            
            return new JsonResponse(
                $serializer->serialize($task, 'json'),
                Response::HTTP_CREATED,
                [],
                true
            );
        }
        
        $validator = $this->get('validator');
        $errors = $validator->validate($task);

        $message = [];
        foreach ($errors as $error) {
            $message[$error->getPropertyPath()][] = $error->getMessage();
        }
        
        return new JsonResponse(
            $serializer->serialize($message, 'json'),
            Response::HTTP_BAD_REQUEST,
            [],
            true
        );
    }
    
    /**
     * Update a new task entity.
     * 
     * @Route(
     *  "/{taskId}", 
     *  name="task_update", 
     *  methods={"PUT"},
     *  requirements={"taskId"="\d+"}
     * )
     */
    public function updateAction(Request $request, $taskId)
    {       
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Task')->findOneById($taskId);

        if (!$task) {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        $body = $request->getContent();
        $data = \json_decode($body, true);
       
        $form = $this->createForm(TaskType::class, $task);
        $form->submit($data);
        
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = array(new DateTimeNormalizer(), new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $em = $this->getDoctrine()->getManager();
            $em->merge($task);
            $em->flush();
            
            return new JsonResponse(
                $serializer->serialize($task, 'json'),
                Response::HTTP_OK,
                [],
                true
            );
        }

        $validator = $this->get('validator');
        $errors = $validator->validate($task);

        $message = [];
        foreach ($errors as $error) {
            $message[$error->getPropertyPath()][] = $error->getMessage();
        }
        
        return new JsonResponse(
            $serializer->serialize($message, 'json'),
            Response::HTTP_BAD_REQUEST,
            [],
            true
        );
    }

    /**
     * Delete a new task entity.
     * 
     * @Route(
     *  "/{taskId}",
     *  name="task_delete",
     *  methods={"DELETE"},
     *  requirements={"taskId"="\d+"}
     * )
     */
    public function deleteAction(Request $request, $taskId)
    {       
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Task')->findOneById($taskId);

        if (!$task) {
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
