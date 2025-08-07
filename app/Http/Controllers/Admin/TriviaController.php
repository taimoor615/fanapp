<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TriviaQuestion;
use App\Models\UserTriviaAttempt;

class TriviaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // Display all trivia questions for admin management
    public function index()
    {
        $questions = TriviaQuestion::with('team', 'userAttempts')->latest()->paginate(12);

        // dd($questions);
        // $questions = [
        //     (object)[
        //         'id' => 1,
        //         'question' => 'What year was the Miami Revenue Runners team founded?',
        //         'type' => 'multiple_choice',
        //         'difficulty' => 'easy',
        //         'points' => 10,
        //         'is_active' => true,
        //         'created_at' => '2024-08-01 10:00:00',
        //         'total_attempts' => 150,
        //         'correct_attempts' => 120,
        //         'success_rate' => 80.0
        //     ],
        //     (object)[
        //         'id' => 2,
        //         'question' => 'Who holds the team record for most points in a single game?',
        //         'type' => 'multiple_choice',
        //         'difficulty' => 'medium',
        //         'points' => 20,
        //         'is_active' => true,
        //         'created_at' => '2024-08-02 15:30:00',
        //         'total_attempts' => 89,
        //         'correct_attempts' => 45,
        //         'success_rate' => 50.6
        //     ]
        // ];

        return view('admin.trivia.index', compact('questions'));
    }

    // Show single trivia question with admin analytics
    public function show($id)
    {
        $question = TriviaQuestion::with('userAttempts.user')->findOrFail($id);

        // $question = (object)[
        //     'id' => $id,
        //     'question' => 'What year was the Miami Revenue Runners team founded?',
        //     'options' => ['2015', '2018', '2020', '2022'],
        //     'correct_answer' => '1',
        //     'type' => 'multiple_choice',
        //     'difficulty' => 'easy',
        //     'points' => 10,
        //     'is_active' => true,
        //     'category' => 'Team History',
        //     'created_at' => '2024-08-01 10:00:00',
        //     'total_attempts' => 150,
        //     'correct_attempts' => 120,
        //     'success_rate' => 80.0,
        //     'recent_attempts' => [
        //         (object)['user' => 'John Doe', 'is_correct' => true, 'completed_at' => '2024-08-05 10:30:00'],
        //         (object)['user' => 'Jane Smith', 'is_correct' => false, 'completed_at' => '2024-08-05 09:15:00']
        //     ]
        // ];

        return view('admin.trivia.show', compact('question'));
    }

    // Show create form
    public function create()
    {
        return view('admin.trivia.create');
    }

    // Store new trivia question
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|max:1000',
            'type' => 'required|in:multiple_choice,true_false,text',
            'difficulty' => 'required|in:easy,medium,hard',
            'category' => 'nullable|string|max:100',
            'options' => 'required|array|min:2|max:6',
            'options.*.text' => 'required|string|max:255',
            'correct_answer' => 'required|in:0,1,2,3,4,5',
            'is_active' => 'nullable|boolean',
            'team_id'=> 'nullable|integer',
            'points' => 'required|integer|min:1|max:100'
        ]);

        // Extract only the texts of the options
        $optionTexts = array_map(fn($option) => $option['text'], $request->options);

        $correctAnswerIndex = (int)$request->correct_answer;
        $correctAnswerText = $optionTexts[$correctAnswerIndex] ?? null;

        if (!$correctAnswerText) {
            return back()->withErrors(['correct_answer' => 'Invalid correct answer selected.'])->withInput();
        }

        TriviaQuestion::create([
            'team_id' => $request->team_id,
            'question' => $request->question,
            'type' => $request->type,
            'difficulty' => $request->difficulty,
            'category' => $request->category,
            'options' => $optionTexts, // array of strings
            'correct_answer' => $correctAnswerText, // actual string answer
            'is_active' => $request->has('is_active'),
            'points' => $request->points,
        ]);

        return redirect()->route('admin.trivia.index')->with('success', 'Trivia question created successfully!');
    }


    // Show edit form
    public function edit($id)
    {
        $question = TriviaQuestion::findOrFail($id);

        // $question = (object)[
        //     'id' => $id,
        //     'question' => 'What year was the Miami Revenue Runners team founded?',
        //     'options' => ['2015', '2018', '2020', '2022'],
        //     'correct_answer' => '1',
        //     'type' => 'multiple_choice',
        //     'difficulty' => 'easy',
        //     'category' => 'Team History',
        //     'is_active' => true
        // ];

        return view('admin.trivia.edit', compact('question'));
    }

    // Update trivia question
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'question' => 'required|max:1000',
        //     'type' => 'required|in:multiple_choice,true_false,text',
        //     'difficulty' => 'required|in:easy,medium,hard',
        //     'category' => 'nullable|string|max:100',
        //     'options' => 'required|array|min:2|max:6',
        //     'options.*' => 'required|string|max:255',
        //     'correct_answer' => 'required',
        //     'is_active' => 'boolean'
        // ]);
        // dd($request->all());
        $request->validate([
        'question' => 'required|max:1000',
        'type' => 'required|in:multiple_choice,true_false,text',
        'difficulty' => 'required|in:easy,medium,hard',
        'category' => 'nullable|string|max:100',
        'options' => 'required|array|min:2|max:6',
        'options.*.text' => 'required|string|max:255',
        'correct_answer' => 'required|in:0,1,2,3,4,5',
        'is_active' => 'nullable|boolean',
        'team_id'=> 'nullable|integer',
        'points' => 'required|integer|min:1|max:100'
        ]);

        $question = TriviaQuestion::findOrFail($id);

        // Extract text options from array
        $optionTexts = array_map(function ($opt) {
            return $opt['text'];
        }, $request->options);

        // Get correct answer based on index
        $correctAnswerIndex = (int) $request->correct_answer;
        $correctAnswerText = $optionTexts[$correctAnswerIndex] ?? null;

        $question->update([
            'team_id' => $request->team_id,
            'question' => $request->question,
            'type' => $request->type,
            'difficulty' => $request->difficulty,
            'category' => $request->category,
            'options' => $optionTexts, // array of strings
            'correct_answer' => $correctAnswerText, // actual text value
            'is_active' => $request->has('is_active'),
            'points' => $request->points,
        ]);

        return redirect()->route('admin.trivia.index')->with('success', 'Trivia question updated successfully!');
    }

    // Delete trivia question
    public function destroy($id)
    {
        $question = TriviaQuestion::findOrFail($id);
        $question->delete();

        return redirect()->route('admin.trivia.index')->with('success', 'Trivia question deleted successfully!');
    }

    // Bulk actions for questions
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,change_difficulty',
            'selected_items' => 'required|array|min:1',
            'difficulty' => 'required_if:action,change_difficulty|in:easy,medium,hard'
        ]);

        // $ids = $request->selected_items;

        switch($request->action) {
            case 'delete':
                // TriviaQuestion::whereIn('id', $ids)->delete();
                $message = 'Selected questions deleted successfully!';
                break;
            case 'activate':
                // TriviaQuestion::whereIn('id', $ids)->update(['is_active' => true]);
                $message = 'Selected questions activated successfully!';
                break;
            case 'deactivate':
                // TriviaQuestion::whereIn('id', $ids)->update(['is_active' => false]);
                $message = 'Selected questions deactivated successfully!';
                break;
            case 'change_difficulty':
                // TriviaQuestion::whereIn('id', $ids)->update(['difficulty' => $request->difficulty]);
                $message = "Selected questions difficulty changed to {$request->difficulty} successfully!";
                break;
        }

        return redirect()->route('admin.trivia.index')->with('success', $message);
    }

    // Analytics dashboard
    public function analytics()
    {
        // $stats = [
        //     'total_questions' => TriviaQuestion::count(),
        //     'active_questions' => TriviaQuestion::where('is_active', true)->count(),
        //     'total_attempts' => UserTriviaAttempt::count(),
        //     'unique_players' => UserTriviaAttempt::distinct('user_id')->count(),
        //     'average_accuracy' => UserTriviaAttempt::avg('is_correct') * 100,
        //     'difficulty_breakdown' => TriviaQuestion::groupBy('difficulty')->selectRaw('difficulty, count(*) as count')->get(),
        //     'category_breakdown' => TriviaQuestion::groupBy('category')->selectRaw('category, count(*) as count')->get(),
        //     'recent_activity' => UserTriviaAttempt::with('user', 'question')->latest()->limit(10)->get()
        // ];

        $stats = [
            'total_questions' => 50,
            'active_questions' => 45,
            'total_attempts' => 2500,
            'unique_players' => 150,
            'average_accuracy' => 72.5,
            'difficulty_breakdown' => [
                (object)['difficulty' => 'easy', 'count' => 20],
                (object)['difficulty' => 'medium', 'count' => 20],
                (object)['difficulty' => 'hard', 'count' => 10]
            ],
            'category_breakdown' => [
                (object)['category' => 'Team History', 'count' => 15],
                (object)['category' => 'Players', 'count' => 20],
                (object)['category' => 'Games', 'count' => 15]
            ],
            'recent_activity' => [
                (object)['user' => 'John Doe', 'question' => 'Team founding year?', 'is_correct' => true],
                (object)['user' => 'Jane Smith', 'question' => 'Player record?', 'is_correct' => false]
            ]
        ];

        return view('admin.trivia.analytics', compact('stats'));
    }

    // Question performance report
    public function performance()
    {
        // $questions = TriviaQuestion::withCount(['userAttempts', 'userAttempts as correct_attempts' => function($query) {
        //     $query->where('is_correct', true);
        // }])
        // ->having('user_attempts_count', '>', 0)
        // ->get()
        // ->map(function($q) {
        //     $q->success_rate = round(($q->correct_attempts / $q->user_attempts_count) * 100, 2);
        //     return $q;
        // })
        // ->sortByDesc('user_attempts_count');

        $questions = [
            (object)[
                'question' => 'What year was the team founded?',
                'difficulty' => 'easy',
                'total_attempts' => 150,
                'correct_attempts' => 120,
                'success_rate' => 80.0
            ],
            (object)[
                'question' => 'Who holds the scoring record?',
                'difficulty' => 'medium',
                'total_attempts' => 89,
                'correct_attempts' => 45,
                'success_rate' => 50.6
            ]
        ];

        return view('admin.trivia.performance', compact('questions'));
    }
}
