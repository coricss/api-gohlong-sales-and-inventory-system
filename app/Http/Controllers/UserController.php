<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()) {
            if(auth()->user()->role === 'super_admin') {
                return UserModel::select(
                    'id',
                    'picture',
                    'name', 
                    'email',
                    'role',
                    'created_at',
                    'updated_at'
                )->whereNotIn('id', [auth()->user()->id])
                ->orderBy('id', 'desc')->get();
            } else if (auth()->user()->role === 'admin') {
                return UserModel::select(
                    'id',
                    'picture',
                    'name', 
                    'email',
                    'role',
                    'created_at',
                    'updated_at'
                )->where('role', 'user')
                ->whereNotIn('id', [auth()->user()->id])
                ->orderBy('id', 'desc')->get();
            } else {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->user()) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.UserModel::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            try {
                if ($request->hasFile('picture')) {
                    $image = $request->file('picture');
                    $image_name = 'IMG_'.date('ymd').time() . '.' . $image->getClientOriginalExtension();
                   /*  $image->move(public_path('images'), $image_name); */
                    $image->storeAs('images', $image_name, 'public');
    
                    $user = User::create([
                        'picture' => $image_name,
                        'name' => $request->name,
                        'email' => $request->email,
                        'role' => $request->role,
                        'password' => Hash::make($request->password),
                    ]);
                } else {
                    $user = User::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'role' => $request->role,
                        'password' => Hash::make($request->password),
                    ]);
                }

                return response()->json([
                    'status' => '200',
                    'message' => 'User registered successfully'
                ]);

               /*  return $request; */
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error creating user'
                ], 500);
            }

            
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return UserModel::select(
            'id',
            'picture',
            'name', 
            'email',
            'role',
            'created_at',
            'updated_at'
        )->where('id', $id)->first();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserModel $userModel)
    {
        if (auth()->user()) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email'],
            ]);

            try {

                $user_pic = User::select('picture')->where('id', $request->id)->first();

                if ($request->hasFile('picture')) {
                    $image = $request->file('picture');
                    $image_name = 'IMG_'.date('ymd').time() . '.' . $image->getClientOriginalExtension();
                    /* $image->move(public_path('images'), $image_name); */
                    $image->storeAs('images', $image_name, 'public');
    
                    $user = User::find($request->id);
                    $user->picture = $image_name;
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->role = $request->role;
                    $user->updated_at = date('Y-m-d H:i:s');
                    $user->save();

                    if($user_pic->picture !== null) {
                       /*  $image_path = public_path().'/images/'.$user_pic->picture; */
                       /*  if (file_exists($image_path)) {
                            unlink($image_path);
                        } */
                        $image_path = 'images/'.$user_pic->picture;
                        Storage::disk('public')->delete($image_path);
                        
                    }
                } else {
                    $user = User::find($request->id);
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->role = $request->role;
                    $user->updated_at = date('Y-m-d H:i:s');
                    $user->save();
                }

                return response()->json([
                    'status' => '200',
                    'message' => 'User updated successfully'
                ]);

               /*  return $request; */
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error updating user'
                ], 500);
            }

            
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    public function reset_password($id) {
        if (auth()->user()) {
            $user = User::find($id);
            $user->password = Hash::make('GTSC@2023');
            $user->updated_at = date('Y-m-d H:i:s');
            $user->save();

            return response()->json([
                'status' => '200',
                'name' => $user->name,
                'message' => 'Password reset successfully'
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        
        if (auth()->user()) {
            $user = User::find($id);
            $user->delete();

            if($user->picture !== null) {
               /*  $image_path = public_path().'/images/'.$user->picture;
                if (file_exists($image_path)) {
                    unlink($image_path);
                } */
                $image_path = 'images/'.$user->picture;
                Storage::disk('public')->delete($image_path);
            }

            return response()->json([
                'status' => '200',
                'message' => 'User deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Unauthorized'
            ], 401);
        }

    }
}
