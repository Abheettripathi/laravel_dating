<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    
    public function index()
    {
        return Admin::all();
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|max:255',
            'mobile'=>'required|string|max:255',
            'password'=>'required|string|max:100',
            'password1'=>'required|string|max:100',
            'access_module'=>'nullable|string|max:200',
            'type'=>'required|string|max:255',

        ]);
        return Admin::create($request->all());
    }

    
    public function show($id)
    {
        return Admin::findOrFail($id);
    }

   
    public function update(Request $request, $id)
    {
        $admins= Admin::findOrFail($id);
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|string|email|max:255',
            'mobile'=>'required|string|max:255',
            'password'=>'required|string|max:100',
            'password1'=>'required|string|max:100',
            'access_module'=>'nullable|string|max:200',
            'type'=>'required|string|max:255',

        ]);
        $admins->update($request->all());
        return $admins;


    }

    public function destroy($id)
    {
        $admins= Admin::findOrFail($id);
        $admins->delete();
        return response()->json(null , 204);
    }
}
