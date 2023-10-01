<?php

namespace App\Http\Controllers;

use App\Models\ProfileModel;
use Illuminate\Http\Request;

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

    /**
     * Update the specified resource in storage.
     */

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProfileModel $profileModel)
    {
        //
    }
}
