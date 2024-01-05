<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    public function index(Request $request)
    {
        //  DB::enableQueryLog();
        // $users = \App\Models\User::paginate(10);

        $users = DB::table('users')
            // $users = User::
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->where('deleted_at', null)
            ->orderBy('id', 'desc')
            ->paginate(10);
        // $query = DB::getQueryLog();
        // dd($query);
        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        return view('pages.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        // dd($request->all());
        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        \App\Models\User::create($data);
        return redirect()->route('users.index')->with('success', 'User Created Successfully');
    }

    public function edit($id)
    {
        $user = \App\Models\User::findOrFail($id);
        return view('pages.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->all();
        // $data['password']=Hash::make($request->password);
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'User Updated Successfully');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User Deleted Successfully');
    }
}
