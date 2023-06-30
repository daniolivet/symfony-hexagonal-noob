<?php

namespace App\Tests\Unit\User\Application;

use PHPUnit\Framework\TestCase;
use App\User\Application\CreateUserUseCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\User\Domain\Repository\IUserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateUserUseCaseTest extends TestCase {

    private const ASSERT_ERROR         = 'Error should be equals to expected.';
    private const VALIDATE_EMPTY_ERROR = 'This value should not be blank.';

    private MockObject $repository;
    private MockObject $validator;
    private CreateUserUseCase $createUseCaseClass;

    protected function setUp(): void{
        $this->repository = $this->getMockBuilder( IUserRepository::class )
            ->disableOriginalConstructor()
            ->getMock();

        $this->validator = $this->getMockBuilder( ValidatorInterface::class )
            ->disableOriginalConstructor()
            ->getMock();

        $this->createUseCaseClass = new CreateUserUseCase(
            $this->repository,
            $this->validator
        );

    }

    /**
     * @test
     */
    public function itShouldReturnValidateErrors() {

        // Arrage
        $requestData = $this->makeRequestData( [
            'password' => 'Malaga1997//',
            'email'    => 'dani',
            'name'     => 'Dani',
            'surnames' => 'Olivet Jiménez',
        ] );

        $violations = $this->makeViolationList( [
            [
                'message' => 'Invalid email',
                'value'   => $requestData['email'],
                'index'   => 'email',
                'example' => 'dani@',
            ],
        ] );

        $errorExpected = [
            'response' => false,
            'code'     => Response::HTTP_BAD_REQUEST,
            'message'  => 'There is errors in the request.',
            'errors'   => [
                'email' => 'Invalid email',
            ],
        ];

        $this->validator
            ->expects( self::exactly( 1 ) )
            ->method( 'validate' )
            ->willReturn( $violations );

        // Act
        $createUser = ( $this->createUseCaseClass )( $requestData );

        // Assert
        $this->assertEquals(
            $errorExpected,
            $createUser,
            self::ASSERT_ERROR
        );
    }

    /**
     * @test
     */
    public function itShouldCreateAnUser() {
        // Arrage
        $requestData = $this->makeRequestData( [
            'password' => 'Malaga1997//',
            'email'    => 'dani12@gmail.com',
            'name'     => 'Dani',
            'surnames' => 'Olivet Jiménez',
        ] );

        $responseExpected = [
            'response' => true,
            'code'     => Response::HTTP_OK,
            'message'  => 'User created succesfully!',
        ];

        $this->makeValidatorWithoutErrors();

        // Act
        $createUser = ( $this->createUseCaseClass )( $requestData );

        // Assert
        $this->assertEquals(
            $responseExpected,
            $createUser,
            self::ASSERT_ERROR
        );
    }

    /**
     * @test
     */
    public function itShouldReturnErrorInPasswordField() {
        // Arrage
        $requestData = $this->makeRequestData( [
            'password' => 'Malaga1997',
            'email'    => 'dani13@gmail.com',
            'name'     => 'Dani',
            'surnames' => 'Olivet Jiménez',
        ] );

        $responseExpected = [
            'response' => false,
            'code'     => Response::HTTP_BAD_REQUEST,
            'message'  => 'The password must have 8 characters, lowercase, uppercase, numbers and special characters.',
        ];

        $this->makeValidatorWithoutErrors();

        // Act
        $createUser = ( $this->createUseCaseClass )( $requestData );

        // Assert
        $this->assertEquals(
            $responseExpected,
            $createUser,
            self::ASSERT_ERROR
        );
    }

    /**
     * @test
     */
    public function itShouldReturnValidateErrorWithEmptyData() {

        // Arrage
        $requestData = $this->makeRequestData( [
            'password' => '',
            'email'    => '',
            'name'     => '',
            'surnames' => '',
        ] );

        $violations = $this->makeViolationList( [
            [
                'message' => self::VALIDATE_EMPTY_ERROR,
                'value'   => $requestData['password'],
                'index'   => 'password',
                'example' => '',
            ],
            [
                'message' => self::VALIDATE_EMPTY_ERROR,
                'value'   => $requestData['email'],
                'index'   => 'email',
                'example' => '',
            ],
            [
                'message' => self::VALIDATE_EMPTY_ERROR,
                'value'   => $requestData['name'],
                'index'   => 'name',
                'example' => '',
            ],
            [
                'message' => self::VALIDATE_EMPTY_ERROR,
                'value'   => $requestData['surnames'],
                'index'   => 'surnames',
                'example' => '',
            ],
        ] );

        $errorExpected = [
            'response' => false,
            'code'     => Response::HTTP_BAD_REQUEST,
            'message'  => 'There is errors in the request.',
            'errors'   => [
                "password" => self::VALIDATE_EMPTY_ERROR,
                "email"    => self::VALIDATE_EMPTY_ERROR,
                "name"     => self::VALIDATE_EMPTY_ERROR,
                "surnames" => self::VALIDATE_EMPTY_ERROR,
            ],
        ];

        $this->validator
            ->expects( self::exactly( 1 ) )
            ->method( 'validate' )
            ->willReturn( $violations );

        // Act
        $createUser = ( $this->createUseCaseClass )( $requestData );

        // Assert
        $this->assertEquals(
            $errorExpected,
            $createUser,
            self::ASSERT_ERROR
        );
    }

    /**
     * Make a mock of Request data
     *
     * @return array
     */
    private function makeRequestData( array $data ): array{
        return [
            'password' => $data['password'],
            'email'    => $data['email'],
            'name'     => $data['name'],
            'surnames' => $data['surnames'],
        ];
    }

    /**
     * Make a mock of ViolationList
     *
     * @param array $violationData
     */
    private function makeViolationList( array $violationData ): ConstraintViolationList{

        $violationList = [];

        foreach ( $violationData as $data ) {
            $mockViolationList = new ConstraintViolation(
                $data['message'],
                null,
                [],
                $data['value'],
                $data['index'],
                $data['example']
            );

            $violationList[] = $mockViolationList;
        }

        return new ConstraintViolationList( $violationList );
    }

    /**
     * Make validate method of Validator instance without errors.
     *
     * @return void
     */
    private function makeValidatorWithoutErrors(): void{
        $this->validator
            ->expects( self::exactly( 1 ) )
            ->method( 'validate' )
            ->willReturn( [] );
    }
}
