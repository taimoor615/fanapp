<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\UserTriviaAttempt;
use App\Models\TriviaQuestion;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TriviaController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:web');
    }

    // Trivia game homepage for users
    public function index()
    {
        $userStats = UserTriviaAttempt::getUserStats(auth()->id());
        $dailyChallenge = TriviaQuestion::where('is_active', true)
                                       ->where('difficulty', 'medium')
                                       ->inRandomOrder()
                                       ->first();

        // $userStats = [
        //     'total_attempts' => 45,
        //     'correct_attempts' => 32,
        //     'total_points' => 640,
        //     'accuracy_percentage' => 71.1,
        //     'rank' => 15,
        //     'streak' => 3
        // ];

        // $dailyChallenge = (object)[
        //     'id' => 5,
        //     'question' => 'Daily Challenge: What was our highest scoring game this season?',
        //     'difficulty' => 'medium',
        //     'points' => 50 // Bonus points for daily challenge
        // ];

        return view('user.trivia.index', compact('userStats', 'dailyChallenge'));
    }

    // Start a new trivia game
    public function play()
    {
        return view('user.trivia.play');
    }

    // Get random questions for gameplay
    public function getQuestions(Request $request)
    {

        $request->validate([
            'limit' => 'integer|min:1|max:20',
            'difficulty' => 'nullable|in:easy,medium,hard',
            // 'category' => 'nullable|string'
        ]);

        $limit = $request->get('limit', 5);
        $difficulty = $request->get('difficulty');
        // $category = $request->get('category');

        $query = TriviaQuestion::where('is_active', true)
                               ->inRandomOrder()
                               ->limit($limit);

        if ($difficulty) {
            $query->where('difficulty', $difficulty);
        }

        // if ($category) {
        //     $query->where('category', $category);
        // }

        $questions = $query->get();

        // $questions = [
        //     [
        //         'id' => 1,
        //         'question' => 'What year was the Miami Revenue Runners team founded?',
        //         'type' => 'multiple_choice',
        //         'difficulty' => 'easy',
        //         'points' => 10,
        //         'options' => [
        //             ['id' => 0, 'text' => '2015'],
        //             ['id' => 1, 'text' => '2018'],
        //             ['id' => 2, 'text' => '2020'],
        //             ['id' => 3, 'text' => '2022']
        //         ]
        //     ],
        //     [
        //         'id' => 2,
        //         'question' => 'Who holds the team record for most points in a single game?',
        //         'type' => 'multiple_choice',
        //         'difficulty' => 'medium',
        //         'points' => 20,
        //         'options' => [
        //             ['id' => 0, 'text' => 'Michael Jordan'],
        //             ['id' => 1, 'text' => 'James Thompson'],
        //             ['id' => 2, 'text' => 'Alex Rodriguez'],
        //             ['id' => 3, 'text' => 'Carlos Martinez']
        //         ]
        //     ],
        //     [
        //         'id' => 3,
        //         'question' => 'What is the team\'s home arena called?',
        //         'type' => 'multiple_choice',
        //         'difficulty' => 'easy',
        //         'points' => 10,
        //         'options' => [
        //             ['id' => 0, 'text' => 'Miami Center'],
        //             ['id' => 1, 'text' => 'Revenue Arena'],
        //             ['id' => 2, 'text' => 'Runners Stadium'],
        //             ['id' => 3, 'text' => 'Championship Court']
        //         ]
        //     ],
        //     [
        //         'id' => 4,
        //         'question' => 'How many championships has the team won in total?',
        //         'type' => 'multiple_choice',
        //         'difficulty' => 'hard',
        //         'points' => 30,
        //         'options' => [
        //             ['id' => 0, 'text' => '2'],
        //             ['id' => 1, 'text' => '3'],
        //             ['id' => 2, 'text' => '4'],
        //             ['id' => 3, 'text' => '5']
        //         ]
        //     ],
        //     [
        //         'id' => 5,
        //         'question' => 'What is the team\'s primary color?',
        //         'type' => 'multiple_choice',
        //         'difficulty' => 'easy',
        //         'points' => 10,
        //         'options' => [
        //             ['id' => 0, 'text' => 'Blue'],
        //             ['id' => 1, 'text' => 'Red'],
        //             ['id' => 2, 'text' => 'Green'],
        //             ['id' => 3, 'text' => 'Orange']
        //         ]
        //     ]
        // ];

        // Shuffle and limit questions
        $shuffled = collect($questions)->shuffle()->take($limit)->values();

        return response()->json([
            'status' => 'success',
            'data' => $shuffled,
            'total_questions' => $shuffled->count()
        ]);
    }

    // Submit trivia answers and calculate score
    public function submitAnswers(Request $request)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer|exists:trivia_questions,id',
            'answers.*.selected_answer' => 'required'
        ]);

        $userId = auth()->id();
        $answers = $request->get('answers');
        $questionIds = collect($answers)->pluck('question_id');

        // Fetch all related questions at once
        $questions = TriviaQuestion::whereIn('id', $questionIds)->get()->keyBy('id');

        $totalPoints = 0;
        $correctCount = 0;
        $results = [];

        foreach ($answers as $answer) {
            $questionId = $answer['question_id'];
            $selectedAnswer = $answer['selected_answer'];

            $question = $questions[$questionId] ?? null;
            if (!$question) continue;

            $isCorrect = $question->correct_answer == $selectedAnswer;

            $points = 0;
            if ($isCorrect) {
                $correctCount++;
                $points = match ($question->difficulty) {
                    'easy' => 10,
                    'medium' => 20,
                    'hard' => 30,
                    default => 10
                };
                $totalPoints += $points;
            }

            // Save to DB per question attempt
            UserTriviaAttempt::create([
                'user_id' => $userId,
                'question_id' => $questionId,
                'selected_answer' => $selectedAnswer,
                'is_correct' => $isCorrect,
                'points_earned' => $points,
                'completed_at' => Carbon::now()
            ]);

            $results[] = [
                'question_id' => $questionId,
                'selected_answer' => $selectedAnswer,
                'correct_answer' => $question->correct_answer,
                'is_correct' => $isCorrect,
                'points_earned' => $points
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_questions' => count($answers),
                'correct_answers' => $correctCount,
                'total_points' => $totalPoints,
                'accuracy' => round(($correctCount / count($answers)) * 100, 1),
                'results' => $results
            ]
        ]);
    }


    // Get user's trivia statistics
    public function getStats()
    {
        // $stats = UserTriviaAttempt::getUserStats(auth()->id());

        $stats = [
            'total_attempts' => 45,
            'correct_attempts' => 32,
            'total_points' => 640,
            'accuracy_percentage' => 71.1,
            'rank' => 15,
            'streak' => 3,
            'best_score' => 85,
            'recent_attempts' => [
                ['date' => '2024-08-05', 'score' => 75, 'accuracy' => 83.3],
                ['date' => '2024-08-04', 'score' => 60, 'accuracy' => 66.7],
                ['date' => '2024-08-03', 'score' => 85, 'accuracy' => 100.0]
            ]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    // Get leaderboard
    public function leaderboard(Request $request)
    {
        $period = $request->get('period', 'all_time'); // all_time, monthly, weekly

        $query = UserTriviaAttempt::select('user_id', DB::raw('SUM(points_earned) as total_points'))
                                 ->with('user:id,first_name')
                                 ->groupBy('user_id')
                                 ->orderBy('points_earned', 'desc')
                                 ->limit(50);

        if ($period === 'monthly') {
            $query->whereMonth('created_at', now()->month);
        } elseif ($period === 'weekly') {
            $query->where('created_at', '>=', now()->subWeek());
        }

        $leaderboardresult = $query->get();

        $leaderboard = $leaderboardresult->map(function ($result, $index) {
            return [
                'rank' => $index + 1,
                'first_name' => $result->user->first_name,
                'total_points' => $result->total_points,
                'user_id' => $result->user_id,
            ];
        });

        $currentUserId = auth()->id();
        $userRank = $leaderboard->firstWhere('user_id', $currentUserId);
        $userRankPosition = $leaderboard->search(fn($u) => $u['user_id'] === $currentUserId);
        $userRank = $userRankPosition !== false ? $userRankPosition + 1 : null;

        // $leaderboard = [
        //     ['rank' => 1, 'name' => 'Alex Rodriguez', 'total_points' => 1250, 'accuracy' => 89.5],
        //     ['rank' => 2, 'name' => 'Sarah Johnson', 'total_points' => 1180, 'accuracy' => 87.2],
        //     ['rank' => 3, 'name' => 'Michael Chen', 'total_points' => 1050, 'accuracy' => 85.8],
        //     ['rank' => 4, 'name' => 'Emma Wilson', 'total_points' => 985, 'accuracy' => 82.4],
        //     ['rank' => 5, 'name' => 'David Brown', 'total_points' => 920, 'accuracy' => 79.6]
        // ];

        // return response()->json([
        //     'status' => 'success',
        //     'data' => [
        //         'period' => $period,
        //         'leaderboard' => $leaderboard,
        //         'user_rank' => 15 // Current user's rank
        //     ]
        // ]);
         return view('user.trivia.leaderboard', compact('leaderboard','userRank','period'));
    }

    public function dailyChallenge()
    {
        $user = Auth::user();

        $alreadyAttempted = UserTriviaAttempt::where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereHas('question', fn($q) => $q->where('difficulty', 'medium'))
            ->exists();

        if ($alreadyAttempted) {
            return view('user.trivia.daily-completed');
        }

        $question = TriviaQuestion::where('is_active', true)
            ->where('difficulty', 'medium')
            ->inRandomOrder()
            ->first();

        return view('user.trivia.daily-challenge', compact('question'));
    }

    public function submitDailyChallenge(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:trivia_questions,id',
            'selected_answer' => 'required|string',
        ]);

        $user = Auth::user();
        $question = TriviaQuestion::find($request->question_id);

        // Check if user already attempted today
        $alreadyAttempted = UserTriviaAttempt::where('user_id', $user->id)
            ->where('question_id', $question->id)
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if ($alreadyAttempted) {
            return redirect()->route('user.trivia.daily-challenge')->with('error', 'You already attempted today!');
        }

        $isCorrect = strtolower($question->correct_answer) === strtolower($request->selected_answer);
        $points = $isCorrect ? $question->points : 0;

        // Store attempt
        UserTriviaAttempt::create([
            'user_id' => $user->id,
            'question_id' => $question->id,
            'selected_answer' => $request->selected_answer,
            'is_correct' => $isCorrect,
            'points_earned' => $points,
            'completed_at' => now(),
        ]);

        // Update user's total points
        if ($isCorrect) {
            $user->increment('total_points', $points);
        }

        return view('user.trivia.daily-result', compact('isCorrect', 'points', 'question'));
    }
    public function history()
    {
        $user = Auth::user();

        $attempts = UserTriviaAttempt::with('question') // if using question_id
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate 10 per page

        return view('user.trivia.history', compact('attempts'));
    }
}
