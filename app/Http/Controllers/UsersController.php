<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $no = 5;
        $data = array(
            'users' => User::orderBy('created_at', 'desc')->paginate(5),
            'url' => 'users',
            'no' => $no
        );
        return view('users.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array(
            'url' => 'users' 
        );
        return view('users.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'email' => 'required', 
            'name' => 'required',
            'password' => 'required'
        );
        $validate = $request->validate($rules);
        $validate['password'] = Hash::make($validate['password']);

        User::create($validate);
        return redirect('users')->with('success', 'User has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $data = array(
            'user' => $user,
            'url' => 'users' 
        );
        return view('users.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $rules = array(
            'email' => 'required', 
            'name' => 'required',
            'password' => 'required'
        );

        $validate = $request->validate($rules);
        $validate['password'] = Hash::make($validate['password']);
        User::where('id', $user->id)
                    ->update($validate);
        return redirect('/users')->with('success', 'User has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        User::destroy($user->id);
        return redirect('users')->with('success', 'User has been deleted');
    }
}
