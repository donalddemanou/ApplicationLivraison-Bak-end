<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User as ModelsUser;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Get authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function current(Request $request)
    {
        return response()->json($request->user());
    }

    public function index()
    {
        return response()->json(ModelsUser::paginate(50));
    }

    public function admins()
    {
        return response()->json(ModelsUser::paginate(50));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'email' => ['required', 'email', 'unique:users'],
            'type' => ['required'],
        ]);

        $user = null;

        switch ($request->type) {
            case 'admin':
                $user = ModelsUser::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'slug' =>  ModelsUser::slug(),
                ]);

                $role = Role::where('name', 'admin')->firstOrFail();

                $user->asignRole($role);

                return response()->json($user->fresh());
                break;

            default:
                $user = ModelsUser::create([
                    'name' => $request->name,
                    'slug' => ModelsUser::slug(),
                    'email' => $request->email,
                ]);

                $role = Role::where('name', 'user')->first();

                $user->asignRole($role);

                return response()->json($user->fresh());
                break;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function follow($slug)
    {
        ModelsUser::where('slug', $slug)->firstOrFail()->addFollow();
        return response()->json();
    }

    public function unfollow($slug)
    {
        ModelsUser::where('slug', $slug)->firstOrFail()->unFollow();
        return response()->json();
    }

    public function show($slug)
    {
        $user = ModelsUser::with('orders')->where('slug', $slug)->firstOrFail();

        return response()->json($user);
    }
}
