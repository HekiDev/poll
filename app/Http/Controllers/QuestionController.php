<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    //
    public function questions($id) {
        
        $question = DB::table('questions')
                    ->where('id', $id)
                    ->first();

        return response()->json([
            'question' => $question,
        ]);
    }

    public function choices() {
        $choices = DB::table('choices')
                    ->get();

        return response()->json([
            'choices' => $choices,
        ]);
    }

    public function submit_answer(Request $request) {

        $check_answer = DB::table('answers')
                    ->where('question_id', $request->question_id)
                    ->first();

        if($check_answer){

            DB::table('answers')
                ->where('question_id', $check_answer->question_id)
                ->update(['choice_id' => $request->choice_id]);

        }else{
            DB::table('answers')->insert([
                'question_id' => $request->question_id,
                'choice_id' => $request->choice_id
            ]);

            return response()->json([
                'choice_id' => $request->choice_id,
                'question_id' => $request->question_id,
            ]);
            
        }
    }
}
