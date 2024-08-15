<?php
namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuPermission;
use App\Models\OutletIP;
use App\Models\Seeting;
use App\Models\User;
use App\Models\UserMenu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class SettingController extends Controller
{
    public function dbConnection($outlet){
        $outletIP = OutletIP::where('DepotCode',$outlet)->first();
        $ipAddress =$outletIP->IPAddress;
        Config::set("database.connections.sqlsrv_eps", [
            'driver' => 'sqlsrv',
            'host' =>$ipAddress,
            'port' =>1433,
            'database' =>'EPS' ,
            'username' => 'sa',
            'password' => 'flexiload',
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ]);
    }

    public function menuPermission(Request $request)
    {
        $path = $request->name;
        $user = JWTAuth::parseToken()->authenticate();
        $checkPermission = UserMenu::join('MenuItem', 'MenuItem.ID', 'UserMenu.MenuItemId')
            ->where('UserMenu.UserId', $user->ID)
            ->where('MenuItem.Link', $path)
            ->exists();
        if ($checkPermission) {
            return response()->json(['message' => "menu found"], 200);
        } else {
            return response()->json(['message' => "menu not found"], 400);
        }
    }

    public function appSupportingData()
    {
        try {
            $auth = Auth::user();
            $query = Menu::select('Menus.*');
            if ($auth->RoleID === 'RepresentativeUser' || $auth->RoleID === 'GeneralUser') {
                $query->where('MenuID','!=','Users');
            }
            $data = $query->with('subMenus')
                ->orderBy('MenuOrder','asc')
                ->get();
            return response()->json([
                'status' => 'success',
                'menus' => $data,
                'user' => User::where('StaffID',$auth->StaffID)->with('roles')->first()
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function imageUpload($image, $namePrefix, $destination)
    {

        list($type, $file) = explode(';', $image);
        list(, $extension) = explode('/', $type);
        list(, $file) = explode(',', $file);
        $fileNameToStore = $namePrefix . strtotime(Carbon::now()) . rand(0, 100000000) . '.' . $extension;
        $source = fopen($image, 'r');
        $destination = fopen($destination . $fileNameToStore, 'w');
        stream_copy_to_stream($source, $destination);
        fclose($source);
        fclose($destination);
        return $fileNameToStore;
    }

    public function changePassword(Request $request){

        $this->validate($request,[
            'previous_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
        ]);

        $current_password = Auth::User()->password;
        $user = JWTAuth::parseToken()->authenticate();

        if(Hash::check($request->previous_password, $current_password))
        {
            if(Hash::check($request->password, $current_password)){
                return response()->json(['message'=>'Previous Password and Old Password Same']);
            }else{
                $user = User::where('user_id',$user->user_id)->first();
                $user->password = bcrypt($request->password);
                $user->save();
                return response()->json(['message'=>'Password Change successfully :)']);
            }

        }else{
            return response()->json(['success'=>'Previous Password Not Correct :)']);
        }

    }

    public function getAllSetting(){
        $setting = Seeting::query()->first();
        return response()->json([
           'setting' => $setting
        ]);
    }

    public function UpdateSetting(Request $request){
        $this->validate($request,[
            'email' => 'required',
            'mobile' => 'required',
            'address' => 'required',
            'opening_hour' => 'required',
        ]);

        $setting = Seeting::query()->where('id',$request->id)->first();
        $setting->email = $request->email;
        $setting->mobile = $request->mobile;
        $setting->address = $request->address;
        $setting->opening_hour = $request->opening_hour;
        $setting->facebook = $request->facebook;
        $setting->instagram = $request->instagram;
        $setting->twitter = $request->twitter;
        $setting->linkedin = $request->linkedin;
        $setting->save();
        return response()->json(['message'=>'Setting Updated Successfully'],200);
    }
}
