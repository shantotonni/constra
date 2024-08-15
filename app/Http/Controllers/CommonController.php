<?php

namespace App\Http\Controllers;

use App\Http\Resources\Student\StudentCollection;
use App\Models\Bank;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Menu;
use App\Models\Miscellanious;
use App\Models\Purpose;
use App\Models\Role;
use App\Models\Sessions;
use App\Models\Student;
use App\Models\StudentBill;
use App\Models\StudentBillPayment;
use App\Models\Year;
use App\Services\BusinessService;
use App\Services\DepartmentService;
use App\Services\RoleService;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getAllSession(){
        return response()->json([
            'status' => 'success',
        ]);
    }

}
