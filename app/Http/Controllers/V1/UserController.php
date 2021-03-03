<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Transformers\UserTransformer;
use App\Validations\UserValidation;
use Illuminate\Auth\Access\AuthorizationException;

class UserController extends Controller
{
    protected $userRepository;
    protected $userValidation;

    public function __construct(
        UserRepository $userRepository,
        UserValidation $userValidation
    ) {
        $this->userRepository = $userRepository;
        $this->userValidation = $userValidation;
    }

    public function index(Request $request)
    {
        $paginator =  $this->userRepository->getUsers($request);
        return $this->paginateResponse(
            $request,
            $paginator,
            User::$Type,
            new UserTransformer()
        );
    }

    public function store(Request $request)
    {
        $attr = $this->resolveRequest($request);
        $this->userValidation->createUser($attr);
        $category = $this->userRepository->createUser($attr);

        return $this->crateUpdateResponse(
            $request,
            $category,
            User::$Type,
            new UserTransformer()
        );
    }

    public function update(Request $request, $id)
    {
        $attr = $this->resolveRequest($request);
        $this->userValidation->updateUser($attr);
        $category = $this->userRepository->updateUser($attr, $id);

        if ($category == null) {
            throw new AuthorizationException("Anda Tidak Diperbolehkan Mengakses Endpoint");
        }

        return $this->crateUpdateResponse(
            $request,
            $category,
            User::$Type,
            new UserTransformer()
        );
    }

    public function show(Request $request, $id)
    {
        $category = $this->userRepository->getUserById($id);

        if ($category != null) {
            return $this->singleResponse(
                $request,
                $category,
                User::$Type,
                new UserTransformer()
            );
        }

        return $this->emptyResponse("Data Tidak Ada");
    }

    public function destroy(Request $request, $id)
    {
        $category = $this->userRepository->deleteUser($id);
        if (!$category) {
            throw new AuthorizationException("Anda Tidak Diperbolehkan Mengakses Endpoint");
        }
        return $this->deleteResponse();
    }
}
