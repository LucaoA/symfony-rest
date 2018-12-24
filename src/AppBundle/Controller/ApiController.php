<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use AppBundle\Service\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


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
     *
     * @param Serializer $serializer
     * @return JsonResponse
     */
    public function listAction(Serializer $serializer):JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AppBundle:Task')->findAll();
        return new JsonResponse(
            $serializer->toJson($tasks),
            Response::HTTP_OK,
            [],
            true
        );
    }

    /**
     * Creates a new task entity.
     *
     * @Route("/", name="task_create", methods={"POST"})
     *
     * @param Serializer $serializer
     * @param Request $request
     * @return JsonResponse
     */
    public function createAction(Serializer $serializer, Request $request):JsonResponse
    {       
        $body = $request->getContent();
        $data = \json_decode($body, true);
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->submit($data);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            
            return new JsonResponse(
                $serializer->toJson($task),
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
            $serializer->toJson($message),
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
     *
     * @param Serializer $serializer
     * @param Request $request
     * @param $taskId
     * @return JsonResponse
     */
    public function updateAction(Serializer $serializer, Request $request, $taskId):JsonResponse
    {       
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AppBundle:Task')->findOneById($taskId);

        if (!$task) {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }

        $body = $request->getContent();
        $data = \json_decode($body, true);
       
        $form = $this->createForm(TaskType::class, $task);
        $form->submit($data);
        
        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $em = $this->getDoctrine()->getManager();
            $em->merge($task);
            $em->flush();
            
            return new JsonResponse(
                $serializer->toJson($task),
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
            $this->get(Serializer::class)->toJson($message),
            Response::HTTP_BAD_REQUEST,
            [],
            true
        );
    }

    /**
     * Delete a task entity.
     *
     * @Route(
     *  "/{taskId}",
     *  name="task_delete",
     *  methods={"DELETE"},
     *  requirements={"taskId"="\d+"}
     * )
     *
     * @param Request $request
     * @param $taskId
     * @return JsonResponse
     */
    public function deleteAction(Request $request, $taskId):JsonResponse
    {       
        // TODO - delete a task entity
    }
}
