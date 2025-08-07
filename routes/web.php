<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\Admin\RewardController as AdminRewardController;
use App\Http\Controllers\Admin\TriviaController as AdminTriviaController;
use App\Http\Controllers\Admin\FanPhotoController as AdminFanPhotoController;
use App\Http\Controllers\Admin\GamePaymentController as AdminGamePaymentController;

use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\NewsController as UserNewsController;
use App\Http\Controllers\User\GameController as UserGameController;
use App\Http\Controllers\User\RewardController as UserRewardController;
use App\Http\Controllers\User\TriviaController as UserTriviaController;
use App\Http\Controllers\User\FanPhotoController as UserFanPhotoController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\User\GamePaymentController as UserGamePaymentController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Guest Routes (Login/Register)
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Admin Entry Point
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Landing Page (Public)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }

    if (Auth::guard('web')->check()) {
        return redirect()->route('user.dashboard');
    }

    return view('welcome'); // Show welcome for non-authenticated users
})->name('home');
/*
|--------------------------------------------------------------------------
| Guest Routes (Login/Register)
|--------------------------------------------------------------------------
*/
Route::middleware('guest:web')->group(function () {
    // Frontend Login/Register
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Login)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::group(['middleware' => ['guest:admin']], function () {
        Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminController::class, 'login'])->name('admin.login');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Teams Management
    Route::resource('teams', AdminTeamController::class);

    // Users Management
    Route::resource('users', AdminUserController::class);

    // News Management
    Route::resource('news', AdminNewsController::class);
    Route::post('news/bulk-action', [AdminNewsController::class, 'bulkAction'])->name('news.bulk-action');
    Route::get('news-analytics', [AdminNewsController::class, 'analytics'])->name('news.analytics');

    // Games Management
    Route::resource('games', AdminGameController::class);
    Route::post('games/payments/{payment}/update', [AdminGameController::class, 'paymentsUpdateStatus'])->name('game.payments.update');

    // Rewards Management
    Route::prefix('rewards')->name('rewards.')->group(function () {
        Route::get('/', [AdminRewardController::class, 'index'])->name('index');
        Route::get('/create', [AdminRewardController::class, 'create'])->name('create');
        Route::post('/', [AdminRewardController::class, 'store'])->name('store');
        Route::get('/{reward}/edit', [AdminRewardController::class, 'edit'])->name('edit');
        Route::put('/{reward}', [AdminRewardController::class, 'update'])->name('update');
        Route::delete('/{reward}', [AdminRewardController::class, 'destroy'])->name('destroy');
        Route::get('/redemptions', [AdminRewardController::class, 'redemptions'])->name('redemptions');
    });

    // Fan Photos Management
    Route::prefix('fan-photos')->name('fan-photos.')->group(function () {
        Route::get('/', [AdminFanPhotoController::class, 'index'])->name('index');
        Route::post('/{photo}/approve', [AdminFanPhotoController::class, 'approve'])->name('approve');
        Route::post('/{photo}/reject', [AdminFanPhotoController::class, 'reject'])->name('reject');
        Route::delete('/{photo}', [AdminFanPhotoController::class, 'destroy'])->name('destroy');
    });

    // Trivia Management
    Route::resource('trivia', AdminTriviaController::class);
    Route::post('trivia/bulk-action', [AdminTriviaController::class, 'bulkAction'])->name('trivia.bulk-action');
    Route::get('trivia-analytics', [AdminTriviaController::class, 'analytics'])->name('trivia.analytics');
    Route::get('trivia-performance', [AdminTriviaController::class, 'performance'])->name('trivia.performance');

    //Payment Routes
    // Route::get('games/{game}/payments', [AdminGamePaymentController::class, 'index'])->name('game.payments');

    // Admin Logout
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Additional Admin Game Routes (add to admin middleware group)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... your existing admin routes ...

    // Additional Game Management Routes
    Route::prefix('games')->name('games.')->group(function () {
        Route::patch('/{game}/toggle-featured', [AdminGameController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::get('/{game}/attendances', [AdminGameController::class, 'attendances'])->name('attendances');
        Route::post('/bulk-update-status', [AdminGameController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        Route::get('/stats', [AdminGameController::class, 'stats'])->name('stats');
    });
});

/*
|--------------------------------------------------------------------------
| User Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'verified'])->prefix('dashboard')->name('user.')->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');

    // Profile & Settings
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');

    // News
    Route::prefix('news')->name('news.')->group(function () {
        // Route::get('news', [UserNewsController::class, 'index'])->name('news.index');
        // Route::get('news/{id}', [UserNewsController::class, 'show'])->name('news.show');

        Route::get('/', [UserNewsController::class, 'index'])->name('index');
        Route::get('/{post}', [UserNewsController::class, 'show'])->name('show');
    });

    // Games
    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/', [UserGameController::class, 'index'])->name('index');
        Route::get('/{game}', [UserGameController::class, 'show'])->name('show');
        Route::post('/{game}/payment', [UserGameController::class, 'payment'])->name('payment');
        Route::post('/{game}/attend', [UserGameController::class, 'markAttendance'])->name('attend');
    });

    // Rewards
    Route::prefix('rewards')->name('rewards.')->group(function () {
        Route::get('/', [UserRewardController::class, 'index'])->name('index');
        Route::get('/catalog', [UserRewardController::class, 'catalog'])->name('catalog');
        Route::post('/{reward}/redeem', [UserRewardController::class, 'redeem'])->name('redeem');
        Route::get('/history', [UserRewardController::class, 'history'])->name('history');
    });

    // Fan Photos
    Route::prefix('fan-photos')->name('fan-photos.')->group(function () {
        Route::get('/', [UserFanPhotoController::class, 'index'])->name('index');
        Route::post('/', [UserFanPhotoController::class, 'store'])->name('store');
        Route::delete('/{photo}', [UserFanPhotoController::class, 'destroy'])->name('destroy');
    });

    // Trivia
    Route::prefix('trivia')->name('trivia.')->group(function () {
        // Route::get('/{question}', [UserTriviaController::class, 'show'])->name('show');
        // Route::post('/{question}/answer', [UserTriviaController::class, 'answer'])->name('answer');

        Route::get('/', [UserTriviaController::class, 'index'])->name('index');
        Route::get('/play', [UserTriviaController::class, 'play'])->name('play');
        Route::get('/leaderboard', [UserTriviaController::class, 'leaderboard'])->name('leaderboard');
        Route::get('/history', [UserTriviaController::class, 'history'])->name('history');
        Route::get('/daily-challenge', [UserTriviaController::class, 'dailyChallenge'])->name('daily-challenge');
        Route::post('/daily-challenge', [UserTriviaController::class, 'submitDailyChallenge'])->name('submit-daily-challenge');
        Route::get('/practice/{difficulty?}', [UserTriviaController::class, 'practice'])->name('practice');
    });

    // Leaderboard
    Route::get('/leaderboard', [UserDashboardController::class, 'leaderboard'])->name('leaderboard');

    // Store
    Route::prefix('store')->name('store.')->group(function () {
        Route::get('/', [UserDashboardController::class, 'store'])->name('index');
        // Add more store routes as needed
    });

    // Upload Payment
    Route::get('/games/{game}/upload-receipt', [UserGamePaymentController::class, 'create'])->name('games.payment');
    Route::post('/games/{game}/upload-receipt', [UserGamePaymentController::class, 'store'])->name('games.payment.store');

    // User Logout Route
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Additional User Game Routes (add to user middleware group)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'verified'])->prefix('dashboard')->name('user.')->group(function () {
    // ... your existing user routes ...

    // Additional Game Routes
    Route::prefix('games')->name('games.')->group(function () {
        Route::get('/my-attendances', [UserGameController::class, 'myAttendances'])->name('my-attendances');
        Route::get('/upcoming', [UserGameController::class, 'upcomingGames'])->name('upcoming');
        Route::get('/calendar', [UserGameController::class, 'gameCalendar'])->name('calendar');
        Route::post('/quick-attendance', [UserGameController::class, 'quickAttendance'])->name('quick-attendance');
    });
});

/*
|--------------------------------------------------------------------------
| API Routes (for Mobile App)
|--------------------------------------------------------------------------
*/
// API routes are in routes/api.php
