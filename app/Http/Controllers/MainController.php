<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class MainController extends Controller
{
    private $app_data;

    public function __construct()
    {
        //load app_data.php file fromapp folder    
        $this->app_data = require(app_path('app_data.php'));
    }
    public function StartGame():View
    {
        return view('home');
    }
    public function prepareGame(Request $request)
    {
        //validate the request
        $request->validate(
            [
            'total_questions' => 'required|integer|min:3|max:30',
            ],
            [
                'total_questions.required' => 'O número de perguntas é obrigatório',
                'total_questions.integer' => 'O número de perguntas deve ser um inteiro',
                'total_questions.min' => 'No mínimo :min questões',
                'total_questions.max' => 'No máximo :max questões',
            ]
        );

        //get total questions 

        $total_questions = intval($request->input('total_questions'));

        //prepare all the quiz structure
        $quiz = $this->prepareQuiz($total_questions);

        //store the quiz in session
        session()->put([
            'quiz' => $quiz,
            'total_questions' => $total_questions,
            'current_question' => 1,
            'correct_answers' => 0,
            'wrong_answers' => 0,
        ]);

        return redirect()->route('game');

       
    }

    private function prepareQuiz($total_questions)
    {
        $questions = [];
        $total_countries = count($this->app_data);

        //create countris index for unique questions
        $indexes = range(0, $total_countries - 1);
        shuffle($indexes);
        $indexes = array_slice($indexes, 0, $total_questions);

        //create array of questions
        $question_number = 1;
        foreach ($indexes as $index) {
            $question['question_number'] = $question_number++;
            $question['country'] = $this->app_data[$index]['country'];
            $question['correct_answer'] = $this->app_data[$index]['capital'];

            // wrong answers
            $other_capitals = array_column($this->app_data, 'capital');

            //remove correct answer
            $other_capitals = array_diff($other_capitals, [$question['correct_answer']]);

            //shuffle the wrong answers
            shuffle($other_capitals);
            $question['wrong_answers'] = array_slice($other_capitals, 0, 3);

            //store answers result
            $question['correct'] = null;
            $questions[] = $question;

        }

        return $questions;
    }
    public function game():View
    {
        //get quiz data from session
        $quiz = session('quiz');
        $total_questions = session('total_questions');
        $current_question = session('current_question') - 1 ;

        //prepare answers to show in view
        $answers = $quiz[$current_question]['wrong_answers'];
        $answers[] = $quiz[$current_question]['correct_answer'];
        shuffle($answers);
        return view('game')->with([
            'country' => $quiz[$current_question]['country'],
            'totalQuestions' => $total_questions,
            'currentQuestion' => $current_question ,
            'answers' => $answers
        ]);
        
    }
    public function answer($enc_answer)
    {
        //decrypt the answer
        try {
            $answer = Crypt::decryptString($enc_answer);
        } catch (\Exception $e) 
        {
            // Handle decryption error
            return redirect()->route('game');
        }

        // game logic
        $quiz = session('quiz');
        $current_question = session('current_question') - 1;
        $correct_answer = $quiz[$current_question]['correct_answer'];
        $correct_answers = session('correct_answers');
        $wrong_answer = session('wrong_answers');

        if ($answer == $correct_answer) {
            $correct_answers++;
            $quiz[$current_question]['correct'] = true;
        } else {
            $wrong_answer++;
            $quiz[$current_question]['correct'] = false;
        }

        //update session 
        session()->put([
            'quiz' => $quiz,
            'correct_answers' => $correct_answers,
            'wrong_answers' => $wrong_answer
        ]);

        //prepare data to show the correct answer
        $data = [
            'country' => $quiz[$current_question]['country'],
            'correctAnswer' => $correct_answer,
            'choiceAnswer' => $answer,
            'currentQuestion' => $current_question,
            'totalQuestions' => session('total_questions')
        ];

        return view('answer_result')->with($data);

    }
    public function nextQuestion()
    {
        $current_question = session('current_question');
        $total_questions = session('total_questions');

        //check if the game is over
        if ($current_question < $total_questions) {
            $current_question++;
            session()->put('current_question', $current_question);
            return redirect()->route('game');
        } else {
            // game over
            return redirect()->route('showResults');
        }
    }
    public function showResults()
    {
        $total_questions = (int) session('total_questions');
        // $correct_answers = (int) session('correct_answers');
        // $wrong_answers = (int) session('wrong_answers');


        return view('final_results')->with([
            'totalQuestions' => session('total_questions'),
            'correctAnswers' => session('correct_answers'),
            'wrongAnswers' => session('wrong_answers'),
            'porcentage' => round( session('correct_answers') / session('total_questions') * 100, 2)
        ]);
        
    }
}
