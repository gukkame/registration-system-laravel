<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    // Delete a user by id
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    // Update a user by id
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'firstName' => 'sometimes|required|string|max:255',
            'lastName' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phoneNumber' => 'nullable|string|max:20',
            'subscriptionType' => 'sometimes|required|string|max:50',
            'startDate' => 'sometimes|required|date_format:d-m-Y',
            'endDate' => 'sometimes|required|date_format:d-m-Y',
        ]);

        $user->first_name = $validatedData['firstName'] ?? $user->first_name;
        $user->last_name = $validatedData['lastName'] ?? $user->last_name;
        $user->email = $validatedData['email'] ?? $user->email;
        $user->phone_number = $validatedData['phoneNumber'] ?? $user->phone_number;
        $user->subscription_type = $validatedData['subscriptionType'] ?? $user->subscription_type;
        $user->start_date = $validatedData['startDate'] ?? $user->start_date;
        $user->end_date = $validatedData['endDate'] ?? $user->end_date;

        if (!$user->save()) {
            return response()->json(['message' => 'Failed to update user'], 500);
        }

        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }
    // Add a new user
    public function store(Request $request)
    {
        Log::info('Adding a new user', ['request' => $request->all()]);
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phoneNumber' => 'nullable|string|max:20',
            'subscriptionType' => 'required|string|max:50',
            'startDate' => 'required|date_format:d-m-Y',
            'endDate' => 'required|date_format:d-m-Y',
        ]);

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

    // Get all users
    public function index()
    {
        $users = User::all();
        return response()->json($users, 200);
    }
}
