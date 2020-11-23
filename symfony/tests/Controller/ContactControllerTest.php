<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testShowAllContacts()
    {
        $client = static::createClient();

        $client->request('GET', '/api/contacts');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateNewContact()
    {
        $client = static::createClient();

        // submits a raw JSON string in the request body
        $client->request(
            'POST',
            '/api/contact',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{"name":"TestName", "phone": "2020"}'
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    public function testShowContact()
    {
        $client = static::createClient();

        $client->request('GET', '/api/contacts', ["name" => "TestName"]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteContact()
    {
        $client = static::createClient();
        $client->request('GET', '/api/contacts', ["name" => "TestName"]);
        $contact = json_decode($client->getResponse()->getContent(), true);
        $client->request('DELETE', '/api/contact/' . $contact[0]["id"]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }



}