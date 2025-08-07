<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Team;
use App\Models\User;
use App\Models\RewardAction;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
        public function run()
    {
        // Create sample team
        $team = Team::create([
            'name' => 'Sample FC',
            'slug' => 'sample-fc',
            'primary_color' => '#ff0000',
            'secondary_color' => '#ffffff',
            'founded_year' => 2020,
            'description' => 'Sample football club for testing',
            'is_active' => true,
        ]);

        $this->call([
            AdminSeeder::class,
        ]);

        // Create sample fan user
        User::create([
            'team_id' => $team->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'fan@fanapp.com',
            'password' => Hash::make('password'),
            'role' => 'fan',
            'total_points' => 100,
            'is_active' => true,
        ]);

        // Create reward actions
        $rewardActions = [
            ['name' => 'Daily Login', 'description' => 'Login to the app', 'points_value' => 10, 'action_type' => 'app_login'],
            ['name' => 'Game Attendance', 'description' => 'Attend a game', 'points_value' => 100, 'action_type' => 'game_attendance'],
            ['name' => 'Social Share', 'description' => 'Share content on social media', 'points_value' => 25, 'action_type' => 'social_share'],
            ['name' => 'Merchandise Purchase', 'description' => 'Buy team merchandise', 'points_value' => 50, 'action_type' => 'merchandise_purchase'],
            ['name' => 'Photo Upload', 'description' => 'Upload fan photo', 'points_value' => 20, 'action_type' => 'photo_upload'],
            ['name' => 'Trivia Complete', 'description' => 'Complete trivia quiz', 'points_value' => 15, 'action_type' => 'trivia_complete'],
        ];

        foreach ($rewardActions as $action) {
            RewardAction::create($action);
        }
    }
}
