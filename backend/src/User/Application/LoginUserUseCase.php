<?php

namespace App\User\Application;

use App\User\Domain\Repository\IUserRepository;

use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class LoginUserUseCase
{

    public function __construct(
        private readonly IUserRepository $repository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTEncoderInterface $jwtEncoder,
    ) {
    }

    /**
     * 
     * @param array $requestData
     * 
     * @return array
     */
    public function __invoke(array $requestData): array
    {
        try {

            $user = $this->repository->findByEmail( $requestData['email'] );

            $isPasswordValid = $this->passwordHasher->isPasswordValid( $user, $requestData['password'] );
            if( !$isPasswordValid ) {
                throw new InvalidPasswordException();
            }

            $token = $this->jwtEncoder->encode([
                'uuid' => $user->getUuid()
            ]);

            return [
                'response' => true,
                'code'     => Response::HTTP_OK,
                'message'  => 'User logged in succesfully!',
                'token'    => $token
            ];
        } catch (\RuntimeException $e) {
            return [
                'response' => false,
                'code'     => Response::HTTP_BAD_REQUEST,
                'message'  => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            return [
                'response' => false,
                'code'     => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message'  => $e->getMessage(),
            ];
        }
    }
}