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
        $requestData = $this->makeRequestData();

        $violations = $this->makeViolationList( [
            'message' => 'Invalid email',
            'value'   => $requestData['email'],
            'index'   => 'email',
            'example' => 'dani@',
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
            'Error should be equals to expected.'
        );
    }

    /**
     * Make a mock of Request data
     *
     * @return array
     */
    private function makeRequestData(): array{
        return [
            'password' => 'Dani1234//',
            'email'    => 'dani',
            'name'     => 'Dani',
            'surnames' => 'Olivet Jimenez',
        ];
    }

    /**
     * Make a mock of ViolationList
     * 
     * @param array $violationData
     */
    private function makeViolationList( array $violationData ): ConstraintViolationList {
        $mockViolationList = new ConstraintViolation(
            $violationData['message'],
            null,
            [],
            $violationData['value'],
            $violationData['index'],
            $violationData['example']
        );

        return new ConstraintViolationList( [$mockViolationList] );
    }
}
