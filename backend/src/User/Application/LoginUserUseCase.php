<?php

namespace App\User\Application;

use App\User\Domain\Repository\IUserRepository;

use App\User\Domain\Entity\User;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class LoginUserUseCase
{

    /**
     *
     * @param IUserRepository $repository
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        private readonly IUserRepository $repository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly JWTEncoderInterface $jwtEncoder,
    ) {
    }

    public function __invoke(array $requestData)
    {
        try {

            $user = $this->findUser($requestData);

            $token = $this->jwtEncoder->encode([
                'user_uuid' => $user->getUuid()
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

    private function findUser(array $data)
    {
        return $this->repository->findByEmail($data['email']);
    }

    /**
     * Hash user password
     *
     * @param  User   $user
     * @return void
     */
    private function hashUserPassword(User $user)
    {
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $user->getPassword()
        );

        $user->setPassword($hashedPassword);
    }
}