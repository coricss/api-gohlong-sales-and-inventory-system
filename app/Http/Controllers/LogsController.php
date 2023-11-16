<?php

namespace App\Http\Controllers;

use App\Models\Logs;
use App\Models\User;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class LogsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()) {
            try {
                return Logs::selectRaw('
                    logs.id,
                    logs.user_id,
                    users.picture,
                    users.name,
                    users.email,
                    logs.action,
                    logs.module,
                    logs.ip_address,
                    logs.browser,
                    logs.created_at
                ')->join('users', 'logs.user_id', '=', 'users.id')->orderBy('logs.id', 'desc')->get();

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => 'Error fetching sales'
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if(auth()->user()) {
            try {

                $agent = new Agent();

                $logs = new Logs();
                $logs->user_id = auth()->user()->id;
                $logs->action = $request->action;
                $logs->module = $request->module;
                $logs->ip_address = $request->getClientIp();
                $logs->browser = $agent->browser();
                $logs->save();

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => '500',
                    'message' => $th->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Logs $logs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Logs $logs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Logs $logs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Logs $logs)
    {
        //
    }
}
