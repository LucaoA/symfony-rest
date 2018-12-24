<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotNull;

class ApiControllerTest extends WebTestCase
{

    public function testListAction()
    {
        // Create a new client to browse the application
        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('task_list');
        $client->request('GET', $url);
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testCreateActionWithCreatedRequest()
    {

        // Create a new client to browse the application
        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('task_create');
        $client->request('POST', $url,
            [],
            [],
            ['HTTP_Content-Type' => 'application/json'],
            json_encode(['title' => 'Lucas Antonelli', 'done' => false])
        );
        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
        $this->assertContains('Lucas Antonelli', $client->getResponse()->getContent());
    }

    public function testCreateActionWithBadRequest()
    {
        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('task_create');
        $client->request('POST', $url,
            [],
            [],
            ['HTTP_Content-Type' => 'application/json'],
            json_encode(['done' => false])
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertContains((new NotNull())->message, $client->getResponse()->getContent());
    }

    public function testEditActionWithNoContentRequest()
    {
        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('task_update', ['taskId' => '12313123']);
        $client->request('PUT', $url,
            [],
            [],
            ['HTTP_Content-Type' => 'application/json']
        );
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    }


    public function testUpdateActionWithNoContentRequest()
    {

        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('task_update', ['taskId' => '12313123']);
        $client->request('PUT', $url,
            [],
            [],
            ['HTTP_Content-Type' => 'application/json']
        );
        $this->assertEquals(Response::HTTP_NO_CONTENT, $client->getResponse()->getStatusCode());
    }

    public function testUpdateActionWithSuccessRequest()
    {

        $task = new Task();
        $task->setTitle('BLABALBAL');
        $task->setDone(false);
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getManager();
        $em->persist($task);
        $em->flush();

        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('task_update', ['taskId' => $task->getId()]);
        $client->request('PUT', $url,
            [],
            [],
            ['HTTP_Content-Type' => 'application/json'],
            json_encode(['title' => 'Lucas Antonelli', 'done' => false])
        );
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        $this->assertContains('Lucas Antonelli', $client->getResponse()->getContent());
    }

    public function testUpdateActionWithBadRequest()
    {

        $task = new Task();
        $task->setTitle('BLABALBAL');
        $task->setDone(false);
        $client = static::createClient();
        $em = $client->getContainer()->get('doctrine')->getManager();
        $em->persist($task);
        $em->flush();

        $client = static::createClient();
        $url = $client->getContainer()->get('router')->generate('task_update', ['taskId' => $task->getId()]);
        $client->request('PUT', $url,
            [],
            [],
            ['HTTP_Content-Type' => 'application/json'],
            json_encode(['done' => false])
        );
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertContains((new NotNull())->message, $client->getResponse()->getContent());
    }
}
