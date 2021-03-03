<?php

namespace App\Repositories;

use App\Models\User;
use App\Constants\PaginatorConst;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function getUsers($request)
    {
        $limit = $request->get('limit', PaginatorConst::$Size);
        $page = $request->get('page', PaginatorConst::$Page);
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $defaults = new User;
        $categories = $defaults->paginate($limit);
        return $categories;
    }

    public function getUserById($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function createUser($request)
    {
        $transac = DB::transaction(function () use ($request) {
            $user = new User;
            $user->email = $request->email;
            $user->name = $request->name;
            $user->save();
            return compact('user');
        });
        return $transac['user'];
    }

    public function updateUser($request, $id)
    {
        $transac = DB::transaction(function () use ($request, $id) {
            $user = User::find($id);
            $user->email = isset($request->email) ? $request->email : $user->email;
            $user->name = isset($request->name) ? $request->name : $user->name;
            $user->save();
            return compact('user');
        });
        return $transac['user'];
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return false;
        }
        $user->delete();
        return true;
    }
}
