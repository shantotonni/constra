<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionAnswer\QuestionAnswerStoreRequest;
use App\Http\Resources\QuestionAnswer\QuestionAnswerCollection;
use App\Models\QuestionAnswer;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class QuestionAnswerController extends Controller
{

    public function index()
    {
        $quests = QuestionAnswer::orderBy('id','desc')->paginate(15);
        return new QuestionAnswerCollection($quests);
    }

    public function store(QuestionAnswerStoreRequest $request)
    {
        $quest = new QuestionAnswer();
        $quest->question = $request->question;
        $quest->answer = $request->answer;
        $quest->save();

        return response()->json(['message'=>'Question Answer Created Successfully'],200);
    }

    public function update(Request $request, QuestionAnswer $quest)
    {
        $quest = QuestionAnswer::where('id',$request->id)->first();
        $quest->question = $request->question;
        $quest->answer = $request->answer;
        $quest->save();
        return response()->json(['message'=>'Question Answer Updated Successfully'],200);
    }
    public function show($id){

        $quest = QuestionAnswer::Where('id',$id)->first();
        return response()->json([
            'data'=>$quest
        ]);

    }

    public function destroy($id)
    {
        $quest = QuestionAnswer::where('id', $id)->first();
        $quest->delete();
        return response()->json(['message' => 'Question Answer Deleted Successfully']);
    }

    public function search($query)
    {
        return new QuestionAnswerCollection(QuestionAnswer::Where('question', 'like', "%$query%")
            ->paginate(10));
    }
}
