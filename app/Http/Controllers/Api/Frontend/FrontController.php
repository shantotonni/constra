<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\FreeEvaluationRequest;
use App\Http\Resources\Blog\BlogCollection;
use App\Http\Resources\BlogResource;
use App\Http\Resources\Event\EventCollection;
use App\Http\Resources\GalleryCollection;
use App\Http\Resources\Program\ProgramCollection;
use App\Http\Resources\Service\ServiceCollection;
use App\Mail\VolunteerMail;
use App\Models\About;
use App\Models\Advisors;
use App\Models\AskTheBoard;
use App\Models\Blog;
use App\Models\Cart;
use App\Models\Colleges;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Evaluation;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Imam;
use App\Models\ImmigrationRegistration;
use App\Models\Mailing;
use App\Models\Maktab;
use App\Models\Membership;
use App\Models\OurTeam;
use App\Models\Page;
use App\Models\Program;
use App\Models\ProgramSchedule;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\Ramadan;
use App\Models\Seeting;
use App\Models\Service;
use App\Models\Shura;
use App\Models\Slider;
use App\Models\SubCommittee;
use App\Models\Testimonial;
use App\Models\TimeSchedule;
use App\Models\Volunteer;
use App\Models\WebMenu;
use App\Models\WhyChooseUs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;

class FrontController extends Controller
{
    public function getOurBlog(){
        $blogs = Blog::where('status','Active')->orderBy('created_at','desc')->get();
        return new BlogCollection($blogs);
    }

    public function getHomePageSlider(Request $request){
        return Slider::query()->get();
    }

    public function getSettings(){
        return Seeting::query()->first();
    }

    public function getAbout(){
        return About::query()->first();
    }

    public function getAllServices(){
        return new ServiceCollection(Service::query()->orderBy('ordering','asc')->get());
    }

    public function getServicesDetails(Request $request){
        $service = Service::query()->where('id',$request->id)->first();
        return response()->json([
            'service' => $service
        ]);
    }

    public function getAllChoose(){
        return WhyChooseUs::query()->get();
    }

    public function getAllQA(){
        return QuestionAnswer::query()->get();
    }

    public function getAllTestimonials(){
        return Testimonial::query()->get();
    }

    public function getBlogDetails(Request $request){
        $id = $request->id;
        $news = Blog::query()->where('id', $id)->first();
        return response()->json([
            'news' => new BlogResource($news)
        ]);
    }

    public function getAllNews(){
        return Blog::query()->get();
    }

    public function getTeam(){
        return OurTeam::query()->first();
    }

    public function getAllCountry(){
        return Country::query()->get();
    }

    public function getAllHomePageDate(){
        return response()->json([
           'team' => $this->getTeam(),
           'country' => $this->getAllCountry(),
           'newses' => $this->getAllNews(),
           'testimonials' => $this->getAllTestimonials(),
           'question_answer' => $this->getAllQA(),
           'chooses' => $this->getAllChoose(),
           'services' => $this->getAllServices(),
           'about' => $this->getAbout(),
           'setting' => $this->getSettings(),
           'sliders' => $this->getSettings(),
           'blogs' => $this->getOurBlog(),
        ]);
    }

    public function getPage(Request $request){
        $page = Page::query()->where('slug',$request->slug)->first();
        return response()->json([
            'page' => $page
        ]);
    }

    public function getMorningTime(Request $request){
        $time = TimeSchedule::query()
            ->select('id','time','type','created_at',DB::raw("0 as morningAddedEnable"), DB::raw("0 as morningBookedEnable"))
            ->where('type','morning')->get();
        $ip = "192.168.100.119";//$_SERVER['SERVER_ADDR']
        $cart = Cart::query()
            ->whereDate('date',date('Y-m-d',strtotime($request->date)))
            ->where('ip',$ip)
            ->get();

        foreach ($time as $singleTime){
            $added_check = $cart->filter(function ($item) use ($singleTime, $ip, $request){
                return $item->time_schedule_id === $singleTime->id && $item->type === $singleTime->type
                    && date('Y-m-d',strtotime($item->date)) === date('Y-m-d',strtotime($request->date)) && $item->ip === $ip && $item->status == 'added';
            });

            $booked = $cart->filter(function ($item) use ($singleTime, $ip, $request){
                return $item->time_schedule_id === $singleTime->id && $item->type === $singleTime->type
                    && date('Y-m-d',strtotime($item->date)) === date('Y-m-d',strtotime($request->date)) && $item->ip === $ip && $item->status == 'booked';
            });

            if ($added_check->count() > 0){
                $singleTime->backgroundRed = 'rgb(22, 40, 66)';
                $singleTime->color = 'white';
                $singleTime->morningAddedEnable = 1;
            }elseif($booked->count() > 0){
                $singleTime->backgroundRed = 'red';
                $singleTime->color = 'white';
                $singleTime->morningBookedEnable = 1;
            } else {
                $singleTime->backgroundRed = 'white';
                $singleTime->color = 'black';
            }
        }

        return response()->json([
            'morning' => $time,
            'cart' => $cart,
        ]);
    }

    public function getAfternoonTime(Request $request){
        $time = TimeSchedule::query()
            ->select('id','time','type','created_at',DB::raw("0 as afterAddedEnable"), DB::raw("0 as morningBookedEnable"))
            ->where('type','afternoon')->get();

        $ip = "192.168.100.119";//$_SERVER['SERVER_ADDR']
        $cart = Cart::query()
            ->whereDate('date',date('Y-m-d',strtotime($request->date)))
            ->where('ip',$ip)
            ->get();

        foreach ($time as $singleTime){
            $added_check = $cart->filter(function ($item) use ($singleTime, $ip, $request){
                return $item->time_schedule_id === $singleTime->id && $item->type === $singleTime->type
                    && date('Y-m-d',strtotime($item->date)) === date('Y-m-d',strtotime($request->date)) && $item->ip === $ip && $item->status == 'added';
            });

            $booked = $cart->filter(function ($item) use ($singleTime, $ip, $request){
                return $item->time_schedule_id === $singleTime->id && $item->type === $singleTime->type
                    && date('Y-m-d',strtotime($item->date)) === date('Y-m-d',strtotime($request->date)) && $item->ip === $ip && $item->status == 'booked';
            });

            if ($added_check->count() > 0){
                $singleTime->backgroundRed = 'rgb(22, 40, 66)';
                $singleTime->color = 'white';
                $singleTime->afterAddedEnable = 1;
            }elseif($booked->count() > 0){
                $singleTime->backgroundRed = 'red';
                $singleTime->color = 'white';
                $singleTime->afterBookedEnable = 1;
            } else {
                $singleTime->backgroundRed = 'white';
                $singleTime->color = 'black';
            }
        }

        return response()->json([
            'afternoon' => $time,
            'cart' => $cart,
        ]);
    }

    public function getEveningTime(Request $request){
        $time = TimeSchedule::query()
            ->select('id','time','type','created_at',DB::raw("0 as eveningAddedEnable"), DB::raw("0 as eveningBookedEnable"))
            ->where('type','evening')->get();

        $ip = "192.168.100.119";//$_SERVER['SERVER_ADDR']
        $cart = Cart::query()
            ->whereDate('date',date('Y-m-d',strtotime($request->date)))
            ->where('ip',$ip)
            ->get();

        foreach ($time as $singleTime){
            $added_check = $cart->filter(function ($item) use ($singleTime, $ip, $request){
                return $item->time_schedule_id === $singleTime->id && $item->type === $singleTime->type
                    && date('Y-m-d',strtotime($item->date)) === date('Y-m-d',strtotime($request->date)) && $item->ip === $ip && $item->status == 'added';
            });

            $booked = $cart->filter(function ($item) use ($singleTime, $ip, $request){
                return $item->time_schedule_id === $singleTime->id && $item->type === $singleTime->type
                    && date('Y-m-d',strtotime($item->date)) === date('Y-m-d',strtotime($request->date)) && $item->ip === $ip && $item->status == 'booked';
            });

            if ($added_check->count() > 0){
                $singleTime->backgroundRed = 'rgb(22, 40, 66)';
                $singleTime->color = 'white';
                $singleTime->eveningAddedEnable = 1;
            }elseif($booked->count() > 0){
                $singleTime->backgroundRed = 'red';
                $singleTime->color = 'white';
                $singleTime->eveningBookedEnable = 1;
            } else {
                $singleTime->backgroundRed = '';
                $singleTime->color = '';
            }
        }

        return response()->json([
            'evening' => $time,
            'cart' => $cart,
        ]);
    }

    public function getOurGallery(){
        $gallery = Gallery::orderBy('created_at','desc')->where('status','Y')->get();
        return new GalleryCollection($gallery);
    }

    public function getTestimonial(){
        $testimonials = Testimonial::orderBy('created_at','desc')->get();
        return response()->json([
            'testimonials' => $testimonials
        ]);
    }

    public function immigrationRegistration(Request $request){
        $this->validate($request,[
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required',
            'phone'=>'required',
            'country'=>'required',
        ]);

        $registration               = new ImmigrationRegistration();
        $registration->first_name   = $request->first_name;
        $registration->last_name    = $request->last_name;
        $registration->email        = $request->email;
        $registration->phone        = $request->phone;
        $registration->country      = $request->country;
        $registration->course       = $request->course;
        $registration->type         = $request->type;
        $registration->save();

        return response()->json([
           'status'=>'success',
           'message'=>'Successfully Inserted'
        ]);
    }

    public function contact(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'phone'=>'required',
            'email'=>'required',
            'subject'=>'required',
            'message'=>'required',
        ]);

        $contact = new Contact();
        $contact->name = $request->name;
        $contact->phone = $request->phone;
        $contact->email = $request->email;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();

        return response()->json([
           'status'=>'success',
           'message'=>'Successfully Inserted'
        ]);
    }

    public function freeEvaluationStore(FreeEvaluationRequest $request){

        $photo = $request->file('image');
        $imagename = $request->name.'_'.time() . '.' . $photo->getClientOriginalExtension();

        // Add Normal Image...
        $destinationPath = public_path('images/cv');
        $photo->move($destinationPath, $imagename);

        $evaluation = new Evaluation();
        $evaluation->name = $request->name;
        $evaluation->email = $request->email;
        $evaluation->confirm_email = $request->confirm_email;
        $evaluation->country_residence = $request->country_residence;
        $evaluation->state = $request->state;
        $evaluation->city = $request->city;
        $evaluation->country_citizenship = $request->country_citizenship;
        $evaluation->gender = $request->gender;
        $evaluation->age = $request->age;
        $evaluation->phone = $request->phone;
        $evaluation->marital_status = $request->marital_status;
        $evaluation->dependant_children = $request->dependant_children;
        $evaluation->english_read = $request->english_read;
        $evaluation->english_write = $request->english_write;
        $evaluation->english_speak = $request->english_speak;
        $evaluation->english_listen = $request->english_listen;
        $evaluation->french_read = $request->french_read;
        $evaluation->french_write = $request->french_write;
        $evaluation->french_speak = $request->french_speak;

        $evaluation->french_listen = $request->french_listen;
        $evaluation->post_secondary_education = $request->post_secondary_education;
        $evaluation->total_year_of_education = $request->total_year_of_education;
        $evaluation->work_experience = $request->work_experience;
        $evaluation->status = 'pending';
        $evaluation->file = $imagename;

        $evaluation->save();

        return response()->json([
           'status'=>'success',
           'message'=>'Successfully Inserted'
        ]);
    }

    public function cartAdd(Request $request){
        $exists = Cart::where('time_schedule_id',$request->time_schedule_id)
            ->whereDate('date',date('Y-m-d',strtotime($request->date)))
            ->where('type',$request->timeObject['type'])
            ->where('ip','192.168.100.119')
            ->exists();
        if ($exists){
            return response()->json([
                'status'=>'error',
                'message'=>'Already Added This Item In Your Cart'
            ]);
        }

        $cart = new Cart();
        $cart->time_schedule_id = $request->time_schedule_id;
        $cart->date = date('Y-m-d',strtotime($request->date));
        $cart->time = $request->time;
        $cart->type = $request->timeObject['type'];
        $cart->status = 'added';
        $cart->ip = "192.168.100.119";//$_SERVER['SERVER_ADDR']
        $cart->save();

        return response()->json([
            'status'=>'success',
            'message'=>'Successfully Inserted'
        ]);
    }

    public function getCartItem(Request $request){
        $ip = "192.168.100.119";//$_SERVER['SERVER_ADDR']
        $cart = Cart::query()
            ->whereDate('date',date('Y-m-d',strtotime($request->date)))
            ->where('ip',$ip)
            ->where('status','added')
            ->get();
        return response()->json([
            'status'=>'success',
            'cart_item' => $cart,
            'cart_item_total' => count($cart),
        ]);
    }

    public function cartItemDelete(Request $request){
        $item = json_decode($request->item);
        Cart::query()->where('id',$item->id)
            ->delete();
        return response()->json([
            'status'=>'success',
            'message'=>'Successfully Deleted'
        ]);
    }

}
