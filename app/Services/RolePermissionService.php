<?php
namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use App\Models\RolePermission;

class RolePermissionService {

    public function index() : Object
    {
        // return datatables()->of(User::select('name','email','id')->get()) //224ms - 212ms
        // $users = Cache::remember('users-page-'. request('draw', 1), 60*60, function(){
        // return datatables()->of(User::all()) //305ms - 268ms
        $rpCallback = Role::with('permissions')
        ->get();
        return datatables()->of($rpCallback) //224ms - 212ms
        ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="javascript:void(0)" class="actionView btn btn-info btn-sm">View</a> &nbsp';
                $btn = $btn.'<a href="javascript:void(0)" class="actionEdit btn btn-primary btn-sm">Edit</a> &nbsp';
                $btn = $btn.'<a href="javascript:void(0)"class="actionDelete btn btn-danger btn-sm">Delete</a>';
                return $btn;
            })
        ->rawColumns(['action'])->toJson();
        // });
        // return $users;
    }
}
