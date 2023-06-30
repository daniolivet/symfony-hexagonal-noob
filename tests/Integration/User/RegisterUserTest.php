<?php

namespace App\Tests\Integration\User;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RegisterUserTest extends WebTestCase {

    private const VALIDATE_EMPTY_ERROR = 'This value should not be blank.';
    private const USER_DATA            = [
        "email"    => "dani12@gmail.com",
        "name"     => "Dani",
        "surnames" => "Olivet Jimenez",
        "password" => "Malaga1997//",
    ];

    /**
     * @var KernelBrowser
     */
    private static KernelBrowser $client;

    /**
     * @var Connection
     */
    private Connection $entityManager;

    public static function setUpBeforeClass(): void{
        self::$client = static::createClient();
    }

    protected function setUp(): void{
        $this->entityManager = $this->getContainer()->get( Connection::class );
        $this->entityManager->beginTransaction();
    }

    protected function tearDown(): void{
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
        $this->makeRequest( self::USER_DATA );

        // Assert
        $response = json_decode( self::$client->getResponse()->getContent(), true );

        $this->makeAsserts(
            Response::HTTP_OK,
            $response,
            $responseExpected
        );

    }

    /**
     * @test
     */
    public function itShouldReturnErrorEmailExists() {

        // Arrage
        $responseExpected = [
            "response" => false,
            "message"  => "The user with email dani12@gmail.com already exists.",
        ];

        $this->makeRequest( self::USER_DATA );

        // Act
        $this->makeRequest( self::USER_DATA );

        // Assert
        $response = json_decode( self::$client->getResponse()->getContent(), true );

        $this->makeAsserts(
            Response::HTTP_BAD_REQUEST,
            $response,
            $responseExpected
        );
    }

    /**
     * @test
     */
    public function itShouldReturnErrorWithEmptyData() {

        // Arrage
        $responseExpected = [
            "response" => false,
            "message"  => "There is errors in the request.",
            "errors"   => [
                "password" => self::VALIDATE_EMPTY_ERROR,
                "email"    => self::VALIDATE_EMPTY_ERROR,
                "name"     => self::VALIDATE_EMPTY_ERROR,
                "surnames" => self::VALIDATE_EMPTY_ERROR,
            ],
        ];

        // Act
        $this->makeRequest( [
            "email"    => "",
            "name"     => "",
            "surnames" => "",
            "password" => "",
        ] );

        // Assert
        $response = json_decode( self::$client->getResponse()->getContent(), true );

        $this->makeAsserts(
            Response::HTTP_BAD_REQUEST,
            $response,
            $responseExpected
        );
    }

    /**
     * @param array $body
     */
    private function makeRequest( array $body ): void{
        self::$client->request(
            'POST',
            '/user/create',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode( $body )
        );
    }

    /**
     * @param  int    $statusCode
     * @param  array  $response
     * @param  array  $responseExpected
     * @return void
     */
    private function makeAsserts( int $statusCode, array $response, array $responseExpected ): void{
        $this->assertEquals(
            $statusCode,
            self::$client->getResponse()->getStatusCode(),
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
    protected function clearDatabase(): void {
        foreach ( $this->entityManager->createSchemaManager()->listTableNames() as $tableName ) {
            $this->entityManager->executeQuery( 'TRUNCATE ' . $tableName );
        }
    }

}
