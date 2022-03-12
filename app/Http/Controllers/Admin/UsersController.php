<?php

namespace App\Http\Controllers\Admin;

use App\Entity\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use App\UseCases\Auth\RegisterService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private $register;

    public function __construct(RegisterService $register)
    {
        $this->register = $register;
        $this->middleware('can:manage-users');
    }

    public function index(Request $request)
    {
        $query = User::orderByDesc('id');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (!empty($value = $request->get('name'))) {
            $query->where('name', 'like', '%' . $value . '%');
        }

        if (!empty($value = $request->get('email'))) {
            $query->where('email', 'like', '%' . $value . '%');
        }

        if (!empty($value = $request->get('status'))) {
            $query->where('status', $value);
        }

        if (!empty($value = $request->get('role'))) {
            $query->where('role', $value);
        }

        $users = $query->paginate(20);

        $statuses = User::statusesList();
        $roles = User::rolesList();

        return view('admin.users.index', compact('users', 'statuses', 'roles'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(CreateRequest $request)
    {
        $user = User::create($request->only(['name', 'email']) + [
            'password' => bcrypt(Str::random()),
            'status' => User::STATUS_ACTIVE,
            ]);

        return redirect()->route('admin.users.show', $user);
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // Render update form
    public function edit(User $user)
    {
        $roles = User::rolesList();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    //Updating user
    public function update(UpdateRequest $request, User $user)
    {
        $user->update($request->only(['name', 'email', 'status', 'role']));

        return redirect()->route('admin.users.show', $user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index');
    }

    public function verify(User $user)
    {
        $user->verify();

        return redirect()->route('admin.users.show', $user);
    }
}
