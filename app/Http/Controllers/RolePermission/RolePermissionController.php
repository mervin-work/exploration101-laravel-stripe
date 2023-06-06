<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\RolePermission;
use App\Services\RolePermissionService;
use \Illuminate\Http\Response;
use App\Services\RoleService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
class RolePermissionController extends Controller
{

    /**
     * Get Users
    */
    public function index(Request $request, RolePermissionService $rpService)
    {
        try {

            if($request->ajax()) {
                return $rpService->index();
            }

            return view('admin.roles-permissions');

        } catch (\Throwable $th) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
