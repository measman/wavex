<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserApiController extends Controller
{
    protected User $user; // Corrected the property name from $excahngerate to $user

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function userinfo()
    {
        $data = $this->user->fetchall();

        return response()->json(['data' => $data]);
    }
    public function userdelete(Request $request)
    {
        $id = $request->input('id');
        $user = User::find($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.']);
    }
    public function useredit(Request $request)
    {
        $id = $request->input('id');
        $user = User::where('id', $id)->first(); 
        return response()->json($user); 
    }
    public function userupdate(Request $request)
    {
        Log::info($request->all());
        $this->user->updateuserinfo($request);
        return response()->json(['message' => 'User Edited successfully.']);
    }
}
