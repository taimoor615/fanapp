@extends('layouts.user-dashboard')

@section('title', 'Play Game')

@section('content')
<div class="container mt-4">
    <!-- Game Header -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h2><i class="fa fa-brain"></i> Team Trivia Challenge</h2>
                    <p class="mb-0">Test your knowledge and earn points!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Game Start Screen -->
    <div id="start-screen" class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body text-center p-5">
                    <i class="fa fa-play-circle fa-5x text-success mb-4"></i>
                    <h3>Ready to Play?</h3>
                    <p class="lead">Answer questions about your favorite team and climb the leaderboard!</p>

                    <!-- Game Settings -->
                    <input type="hidden" name="category" value="category">
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <label for="question-count" class="form-label">Number of Questions:</label>
                            <select class="form-select" id="question-count">
                                <option value="5">5 Questions (Quick)</option>
                                <option value="10" selected>10 Questions (Standard)</option>
                                <option value="15">15 Questions (Championship)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="difficulty" class="form-label">Difficulty:</label>
                            <select class="form-select" id="difficulty">
                                <option value="">Mixed Difficulty</option>
                                <option value="easy">Easy Only</option>
                                <option value="medium">Medium Only</option>
                                <option value="hard">Hard Only</option>
                            </select>
                        </div>
                    </div>

                    <button class="btn btn-success btn-lg mt-4" onclick="startGame()">
                        <i class="fa fa-rocket"></i> Start Game
                    </button>

                    <div class="mt-3">
                        <a href="{{ route('user.trivia.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Game Screen -->
    <div id="game-screen" class="d-none">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- Game Stats -->
                <div class="card mb-3 border-success">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-3">
                                <span class="badge bg-success fs-6">
                                    Question <span id="current-question">1</span>/<span id="total-questions">10</span>
                                </span>
                            </div>
                            <div class="col-3">
                                <span class="badge bg-primary fs-6">
                                    Score: <span id="current-score">0</span>
                                </span>
                            </div>
                            <div class="col-3">
                                <span class="badge bg-warning fs-6">
                                    Streak: <span id="current-streak">0</span>
                                </span>
                            </div>
                            <div class="col-3">
                                <span class="badge bg-info fs-6">
                                    Time: <span id="time-remaining">30</span>s
                                </span>
                            </div>
                        </div>
                        <div class="progress mt-3">
                            <div class="progress-bar bg-success" id="progress-bar" role="progressbar" style="width: 10%"></div>
                        </div>
                    </div>
                </div>

                <!-- Question Card -->
                <div class="card shadow-lg">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-secondary" id="question-difficulty">Medium</span>
                            <span class="badge bg-primary" id="question-points">20 points</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 id="question-text" class="mb-4 text-center">Loading question...</h4>

                        <div id="options-container" class="d-grid gap-3">
                            <!-- Options will be populated by JavaScript -->
                        </div>

                        <div class="mt-4 text-center">
                            <button class="btn btn-success btn-lg" id="next-btn" onclick="nextQuestion()" disabled>
                                Next Question <i class="fa fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Screen -->
    <div id="results-screen" class="d-none">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white text-center">
                        <h3><i class="fa fa-trophy"></i> Game Complete!</h3>
                    </div>
                    <div class="card-body text-center p-5">
                        <div class="results-animation mb-4">
                            <i class="fa fa-star fa-3x text-warning"></i>
                        </div>

                        <h2 class="text-success mb-3">Final Score: <span id="final-score">0</span></h2>

                        <div class="row mb-4">
                            <div class="col-md-3">
                                <h4 id="correct-count">0</h4>
                                <p class="text-muted">Correct</p>
                            </div>
                            <div class="col-md-3">
                                <h4 id="total-count">0</h4>
                                <p class="text-muted">Total</p>
                            </div>
                            <div class="col-md-3">
                                <h4 id="accuracy">0%</h4>
                                <p class="text-muted">Accuracy</p>
                            </div>
                            <div class="col-md-3">
                                <h4 id="xp-earned">0</h4>
                                <p class="text-muted">XP Earned</p>
                            </div>
                        </div>

                        <div class="progress mb-4">
                            <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                    id="final-progress" role="progressbar"></div>
                        </div>

                        <div id="performance-message" class="alert alert-success" role="alert">
                            <i class="fa fa-thumbs-up"></i> Great job! Keep playing to improve your ranking!
                        </div>

                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <button class="btn btn-success btn-lg" onclick="restartGame()">
                                <i class="fa fa-redo"></i> Play Again
                            </button>
                            {{-- <a href="{{ route('user.trivia.leaderboard') }}" class="btn btn-warning btn-lg">
                                <i class="fa fa-trophy"></i> View Leaderboard
                            </a> --}}
                            <a href="{{ route('user.trivia.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fa fa-home"></i> Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
        let gameData = {
            questions: [],
            currentQuestion: 0,
            score: 0,
            correctAnswers: 0,
            streak: 0,
            answers: [],
            timeRemaining: 30,
            timer: null
        };

        function startGame() {
            const questionCount = document.getElementById('question-count').value;
            const difficulty = document.getElementById('difficulty').value;

            const params = new URLSearchParams({
                limit: questionCount,
                ...(difficulty && { difficulty })
            });

            // Fetch questions from API
            fetch(`/api/user/trivia/questions?${params}`)
                .then(response => response.json())
                .then(data => {
                    gameData.questions = data.data;
                    gameData.currentQuestion = 0;
                    gameData.score = 0;
                    gameData.correctAnswers = 0;
                    gameData.streak = 0;
                    gameData.answers = [];

                    document.getElementById('total-questions').textContent = gameData.questions.length;
                    document.getElementById('start-screen').classList.add('d-none');
                    document.getElementById('game-screen').classList.remove('d-none');

                    showQuestion();
                    startTimer();
                })
                .catch(error => {
                    console.error('Error loading questions:', error);
                    alert('Error loading questions. Please try again.');
                });
        }

        function showQuestion() {
            const question = gameData.questions[gameData.currentQuestion];

            document.getElementById('current-question').textContent = gameData.currentQuestion + 1;
            document.getElementById('question-text').textContent = question.question;
            document.getElementById('question-difficulty').textContent = question.difficulty.charAt(0).toUpperCase() + question.difficulty.slice(1);
            document.getElementById('question-points').textContent = question.points + ' points';
            document.getElementById('current-score').textContent = gameData.score;
            document.getElementById('current-streak').textContent = gameData.streak;

            const progress = ((gameData.currentQuestion + 1) / gameData.questions.length) * 100;
            document.getElementById('progress-bar').style.width = progress + '%';

            // const optionsContainer = document.getElementById('options-container');
            // optionsContainer.innerHTML = '';

            // question.options.forEach((option, index) => {
            //     const button = document.createElement('button');
            //     button.className = 'btn btn-outline-primary btn-lg text-start';
            //     button.innerHTML = `<strong>${String.fromCharCode(65 + index)}.</strong> ${option.text}`;
            //     button.onclick = () => selectAnswer(option.id, button);
            //     optionsContainer.appendChild(button);
            // });

            const optionsContainer = document.getElementById('options-container');
            optionsContainer.innerHTML = '';

            question.options.forEach((option, index) => {
                const button = document.createElement('button');
                button.className = 'btn btn-outline-primary btn-lg text-start mb-2';
                button.innerHTML = `<strong>${String.fromCharCode(65 + index)}.</strong> ${option}`;
                button.onclick = () => selectAnswer(option, button); // use the string directly
                optionsContainer.appendChild(button);
            });

            document.getElementById('next-btn').disabled = true;
            gameData.timeRemaining = 30;
            startTimer();
        }

        function selectAnswer(optionId, button) {
            clearInterval(gameData.timer);

            // Disable all option buttons
            const buttons = document.querySelectorAll('#options-container button');
            buttons.forEach(btn => btn.disabled = true);

            // Store the answer
            gameData.answers.push({
                question_id: gameData.questions[gameData.currentQuestion].id,
                selected_answer: optionId
            });

            // Highlight selected answer
            button.classList.remove('btn-outline-primary');
            button.classList.add('btn-primary');

            // Enable next button
            document.getElementById('next-btn').disabled = false;
        }

        function nextQuestion() {
            gameData.currentQuestion++;

            if (gameData.currentQuestion < gameData.questions.length) {
                showQuestion();
            } else {
                submitAnswers();
            }
        }

        function startTimer() {
            clearInterval(gameData.timer);
            gameData.timer = setInterval(() => {
                gameData.timeRemaining--;
                document.getElementById('time-remaining').textContent = gameData.timeRemaining;

                if (gameData.timeRemaining <= 5) {
                    document.getElementById('time-remaining').parentElement.classList.remove('bg-info');
                    document.getElementById('time-remaining').parentElement.classList.add('bg-danger');
                }

                if (gameData.timeRemaining <= 0) {
                    clearInterval(gameData.timer);
                    // Auto-select a random answer or skip
                    nextQuestion();
                }
            }, 1000);
        }

        function submitAnswers() {
            clearInterval(gameData.timer);

            fetch('/api/user/trivia/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ answers: gameData.answers })
            })
            .then(response => response.json())
            .then(data => {
                showResults(data.data);
            })
            .catch(error => {
                console.error('Error submitting answers:', error);
                // Show mock results for demo
                showResults({
                    total_score: Math.floor(Math.random() * 200),
                    correct_answers: Math.floor(Math.random() * gameData.questions.length),
                    total_questions: gameData.questions.length,
                    accuracy_percentage: Math.floor(Math.random() * 100),
                    xp_earned: Math.floor(Math.random() * 150)
                });
            });
        }

        function showResults(results) {
            document.getElementById('game-screen').classList.add('d-none');
            document.getElementById('results-screen').classList.remove('d-none');

            document.getElementById('final-score').textContent = results.total_points;
            document.getElementById('correct-count').textContent = results.correct_answers;
            document.getElementById('total-count').textContent = results.total_questions;
            document.getElementById('accuracy').textContent = results.accuracy + '%';
            document.getElementById('xp-earned').textContent = results.xp_earned || results.total_points;

            const finalProgress = document.getElementById('final-progress');
            finalProgress.style.width = results.accuracy + '%';

            // Performance message
            const messageEl = document.getElementById('performance-message');
            if (results.accuracy >= 80) {
                messageEl.className = 'alert alert-success';
                messageEl.innerHTML = '<i class="fa fa-trophy"></i> Outstanding! You\'re a true team expert!';
            } else if (results.accuracy >= 60) {
                messageEl.className = 'alert alert-info';
                messageEl.innerHTML = '<i class="fa fa-thumbs-up"></i> Great job! Keep playing to master more trivia!';
            } else {
                messageEl.className = 'alert alert-warning';
                messageEl.innerHTML = '<i class="fa fa-graduation-cap"></i> Good effort! Practice makes perfect!';
            }
        }

        function restartGame() {
            document.getElementById('results-screen').classList.add('d-none');
            document.getElementById('start-screen').classList.remove('d-none');

            // Reset timer display
            document.getElementById('time-remaining').parentElement.classList.remove('bg-danger');
            document.getElementById('time-remaining').parentElement.classList.add('bg-info');
        }
    </script>

    <style>
        .results-animation {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }

        .btn:disabled {
            opacity: 0.6;
        }

        #options-container button {
            transition: all 0.3s ease;
        }

        #options-container button:hover:not(:disabled) {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
@endsection
