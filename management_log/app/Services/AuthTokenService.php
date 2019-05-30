<?php


namespace App\Services;


use App\Repositories\Contracts\AuthTokenRepository;
use App\Repositories\Validators\Auth_token\CreateAuthTokenValidator;
use App\Repositories\Validators\Auth_token\UpdateAuthTokenValidator;

class AuthTokenService extends AbstractService
{
    public function __construct(AuthTokenRepository $repository,
                                CreateAuthTokenValidator $createAuthTokenValidator,
                                UpdateAuthTokenValidator $updateAuthTokenValidator)
    {
        $this->repository = $repository;
        $this->createValidator = $createAuthTokenValidator;
        $this->updateValidator = $updateAuthTokenValidator;
    }
}