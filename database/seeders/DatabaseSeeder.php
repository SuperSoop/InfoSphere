<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@infosphere.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        $admin->profile()->create(['bio' => 'Administrator of InfoSphere']);

        $moderator = User::create([
            'name' => 'Moderator',
            'email' => 'moderator@infosphere.test',
            'password' => Hash::make('password'),
            'role' => 'moderator',
            'email_verified_at' => now(),
        ]);
        $moderator->profile()->create(['bio' => 'Moderator of InfoSphere']);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'user@infosphere.test',
            'password' => Hash::make('password'),
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
        $user->profile()->create(['bio' => 'Just a regular user']);

        $categories = ['Technology', 'Science', 'Programming', 'Design', 'Business'];
        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => \Illuminate\Support\Str::slug($name),
            ]);
        }

        $tags = ['PHP', 'Laravel', 'JavaScript', 'CSS', 'HTML', 'Vue.js', 'React', 'MySQL', 'API', 'DevOps'];
        foreach ($tags as $name) {
            Tag::create([
                'name' => $name,
                'slug' => \Illuminate\Support\Str::slug($name),
            ]);
        }
    }
}
