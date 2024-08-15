<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionCollection;
use App\Mail\QuestionAnsMail;
use App\Models\AskTheBoard;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class QuestionController extends Controller
{
    public function list(){
        $questions = Question::orderBy('created_at','desc')->paginate(12);
        return new QuestionCollection($questions);
    }

    public function replyStore(Request $request){
         try {

            $question = Question::where('id',$request->QuestionId)->first();
            $email = $question->email;
            $data = strip_tags($request->message);
            Mail::to($email)->send(new QuestionAnsMail($data, $request->title));
            return response()->json([
               'status'=>'success',
               'message'=>'Successfully Send'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong! '.$exception->getMessage()
            ],500);
            }
    }

    public function boardQuestionList(){
        $questions = AskTheBoard::orderBy('created_at','desc')->paginate(12);
        return new QuestionCollection($questions);
    }


}
