<?php

namespace App\Tests\Integration\User;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RegisterUserTest extends WebTestCase {

    /**
     * @var KernelBrowser
     */
    private KernelBrowser $client;

    /**
     * @var Connection
     */
    private Connection $entityManager;

    protected function setUp(): void {
        $this->client        = static::createClient();
        $this->entityManager = $this->getContainer()->get( Connection::class );
        $this->entityManager->beginTransaction();
    }

    protected function tearDown(): void {
        $this->clearDatabase();
        $this->entityManager->close();
    }

    /**
     * @test
     */
    public function itShouldCreateAnUser() {

        // Arrage
        $responseExpected = [
            'response' => true,
            'message'  => 'User created succesfully!',
        ];

        // Act
        $this->client->request(
            'POST',
            '/user/create',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode( [
                "email"    => "dani12@gmail.com",
                "name"     => "Dani",
                "surnames" => "Olivet Jimenez",
                "password" => "Malaga1997//",
            ] )
        );

        // Assert
        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals(
            Response::HTTP_OK,
            $this->client->getResponse()->getStatusCode(),
            'Http code should be equals to expected.'
        );

        $this->assertEquals(
            $responseExpected,
            $response,
            'Response should be equals to expected.'
        );

    }

    /**
     * Clear database
     *
     * @return void
     */
    protected function clearDatabase(): void
    {
        foreach ( $this->entityManager->createSchemaManager()->listTableNames() as $tableName ) {
            $this->entityManager->executeQuery( 'TRUNCATE ' . $tableName );
        }
    }

}
