<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\Role;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users', 'users.create', 'users.update', 'users.delete', 'users.show']))
            {
                throw  new AuthorizationException('Permission Denied!');
            }
            $users = User::all();
            $data = [
                'users' => $users,
            ];
            return view('users.list', $data);
        } catch (Exception $ex)
        {
            session()->flash('error', $ex->getMessage());
            return redirect()->route('dashboard');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.create']))
            {
                throw  new AuthorizationException('Permission Denied!');
            }
            $roles = [];
            foreach (Role::all() as $role)
            {
                $roles[$role->id] = $role->name;
            }
            return view('users.create', compact('roles'));
        } catch (Exception $ex)
        {
            session()->flash('error', $ex->getMessage());
            return redirect()->route('dashboard');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUserRequest $request
     * @return Response
     */
    public function store(StoreUserRequest $request)
    {
        try
        {
            if (!Sentinel::hasAnyAccess(['users.create']))
            {
                throw  new AuthorizationException('Permission Denied!');
            }
            $role_id = $request->get('role_id');
            if ($role_id && $role = Sentinel::getRoleRepository()->find($role_id))
            {
                $credentials = [
                    'first_name' => $request->get('first_name'),
                    'last_name' => $request->get('last_name'),
                    'username' => $request->get('username'),
                    'email' => $request->get('email'),
                    'password' => $request->get('password'),
                ];
                $user = Sentinel::registerAndActivate($credentials);
                $role->users()->attach($user);
                session()->flash('success', 'User created!');
                return redirect()->route('users.list');
            }
            throw new Exception('A valid role is required!');
        }catch (Exception $ex)
        {
            session()->flash('error', $ex->getMessage());
            return redirect()->route('dashboard');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
