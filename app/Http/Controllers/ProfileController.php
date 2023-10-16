<?php

namespace App\Http\Controllers;

use App\Models\ProfileModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProfileModel $profileModel)
    {
        //
    }

    public function update_picture(Request $request, ProfileModel $profileModel)
    {
        if (auth()->user()) {
            
            try {
               /*  $request->validate([
                    'picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
                ]); */
        
                $imageName = 'IMG'.'_'.time().'.'.$request->file->getClientOriginalExtension();
                $imageOld = ProfileModel::where('id', auth()->user()->id)->first();

                if ($imageOld->picture !== null) {
                    unlink(public_path('images').'/'.$imageOld->picture);
                } 
        
                $request->file->move(public_path('images'), $imageName);
        
                ProfileModel::where('id', auth()->user()->id)->update([
                    'picture' => $imageName,
                ]);
        
                return response()->json([
                    'status' => '200',
                    'picture' => $imageName,
                    'message' => 'User picture updated successfully',
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Something went wrong',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Unauthorized',
            ], 401);
        }
    }

    public function update_name(Request $request, ProfileModel $profileModel)
    {
        
        if (auth()->user()) {
            
            try {
                $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                ]);
        
                ProfileModel::where('id', auth()->user()->id)->update([
                    'name' => $request->name,
                ]);
        
                return response()->json([
                    'status' => '200',
                    'message' => 'User name updated successfully',
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Something went wrong',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Unauthorized',
            ], 401);
        }
    }

    public function update_email(Request $request, ProfileModel $profileModel)
    {
        
        if (auth()->user()) {
            
            try {
                $request->validate([
                    'email' => ['required', 'string', 'email'],
                ]);
        
                ProfileModel::where('id', auth()->user()->id)->update([
                    'email' => $request->email,
                ]);
        
                return response()->json([
                    'status' => '200',
                    'message' => 'User email updated successfully',
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Something went wrong',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Unauthorized',
            ], 401);
        }
    }


    public function change_password(Request $request, ProfileModel $profileModel)
    {
        if (auth()->user()) {
            
            try {

                $old_password = $request->old_password;
                $new_password = $request->new_password;
                $confirm_password = $request->confirm_password;

                /* $request->validate([
                    'new_password' => ['required', 'confirmed', Rules\Password::defaults()],
                ]); */
               
                if ($new_password !== $confirm_password) {
                    return response()->json([
                        'status' => '400',
                        'message' => 'New password and confirm password does not match',
                    ], 200);
                } else {
                    $user = ProfileModel::where('id', auth()->user()->id)->first();
                    if (Hash::check($old_password, $user->password)) {
                        ProfileModel::where('id', auth()->user()->id)->update([
                            'password' => Hash::make($new_password),
                            'is_new_user' => '0'
                        ]);

                        return response()->json([
                            'status' => '200',
                            'message' => 'User password updated successfully',
                        ], 200);
                    } else {
                        return response()->json([
                            'status' => '400',
                            'message' => 'Old password does not match',
                        ], 200);
                    }
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Something went wrong',
                ], 500);
            }
        } else {
            return response()->json([
                'status' => '401',
                'message' => 'Unauthorized',
            ], 401);
        }
    }
}
