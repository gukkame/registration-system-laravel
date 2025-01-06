<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function show($id)
    {
        // Find the user by id
        $user = User::find($id);
        return response()->json($user, 200);
    }
    public function store(Request $request)
    {
        Log::info('Adding a new user', ['request' => $request->all()]);
        // Validate the request data
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phoneNumber' => 'nullable|string|max:20',
            'subscriptionType' => 'required|string|max:50',
            'startDate' => 'required|date_format:d-m-Y',
            'endDate' => 'required|date_format:d-m-Y',
        ]);

        // Create a new user instance
        $user = new User();
        $user->first_name = $validatedData['firstName'];
        $user->last_name = $validatedData['lastName'];
        $user->email = $validatedData['email'];
        $user->phone_number = $validatedData['phoneNumber'];
        $user->subscription_type = $validatedData['subscriptionType'];
        $user->start_date = $validatedData['startDate'];
        $user->end_date = $validatedData['endDate'];

        Log::info('User added successfully', ['user' => $user]);
        if (!$user->save()) {
            return response()->json(['message' => 'Failed to add user'], 500);
        }

        return response()->json(['message' => 'User added successfully', 'user' => $user], 201);
    }
    public function index()
    {
        $users = User::all();
        Log::info('All users', ['users' => $users]);

        return response()->json($users, 200);
    }
}
