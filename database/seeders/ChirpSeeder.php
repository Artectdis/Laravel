<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Chirp;
use Illuminate\Database\Seeder;

class ChirpSeeder extends Seeder
{
    public function run(): void
    {
        // Create a few sample users if they don't exist
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Creating 10 users first...');
            $users = User::factory(10)->create();
        }
        // Sample chirps
        $chirps = [
            'Just discovered Laravel - where has this been all my life? 🚀',
            'Building something cool with Chirper today!',
            'Laravel\'s Eloquent ORM is pure magic ✨',
            'Deployed my first app with Laravel Cloud. So smooth!',
            'Who else is loving Blade components?',
            'Friday deploys with Laravel? No problem! 😎',
            'Tinker is my favorite debugging playground.',
            'Queue jobs saved my app\'s performance today. Game changer.',
            'Artisan commands are like having a superpower.',
            'Just wrote my first custom Artisan command. Felt amazing.',
            'Laravel\'s validation rules are so clean and readable.',
            'Middleware makes auth feel effortless.',
            'Sanctum tokens set up in under five minutes. Impressive.',
            'Soft deletes are a lifesaver for user data management.',
            'Model observers are underrated. So much cleaner code.',
            'Laravel Pint keeps my code style consistent automatically.',
            'Tested my first API with Pest today. Loved every second.',
            'Horizon makes queue monitoring actually enjoyable.',
            'Just shipped a feature using Laravel Events and Listeners.',
            'Resource controllers are the perfect starting point.',
            'The Laravel docs are genuinely some of the best I\'ve read.',
            'Polymorphic relationships just clicked for me. Mind blown.',
            'Using Vite with Laravel feels incredibly fast.',
            'Breeze gave me a full auth scaffold in minutes.',
            'Just discovered model factories. Testing is so fun now.',
            'Rate limiting with Laravel is surprisingly simple.',
            'Task scheduling with the kernel is clean and expressive.',
            'Livewire makes reactive UIs without writing JavaScript.',
            'Spatie\'s permission package pairs perfectly with Laravel.',
            'Just set up full-text search with Scout. Works great.',
            'Eager loading fixed my N+1 problem instantly.',
            'Enums in Laravel models are such a nice touch.',
            'UUID primary keys in one line. Love the flexibility.',
            'Cast attributes to value objects? Yes please.',
            'API resources make JSON responses a joy to craft.',
            'The Folio package is a fresh take on routing.',
            'Volt components are tiny and powerful.',
            'Laravel Pulse gives me real-time app health at a glance.',
            'Pennant feature flags let me ship safely and gradually.',
            'Mailable classes make email a first-class citizen.',
            'Notification channels in Laravel are super flexible.',
            'Just built a multi-tenant app with Tenancy for Laravel.',
            'Storage facades make S3 uploads feel trivial.',
            'Image processing with Intervention Image and Laravel rocks.',
            'Laravel Cashier handles subscriptions like a champ.',
            'The pipeline pattern in Laravel is elegant.',
            'Dependency injection in controllers keeps things testable.',
            'Form requests are the cleanest way to handle validation.',
            'Service providers make bootstrapping feel structured.',
            'Repository pattern pairs beautifully with Eloquent.',
            'Just learned about deferred providers. Very clever.',
            'Auth policies are readable and straightforward.',
            'Broadcasting events to Pusher was easier than expected.',
            'Echo.js and Laravel make websockets feel approachable.',
            'Just discovered withCount() and it saved me a query.',
            'Chunking large datasets with Eloquent feels so safe.',
            'Cursor pagination is silky smooth on big tables.',
            'Wrote a complex query scope today. Felt expressive.',
            'Conditional relationship loading with when() is great.',
            'Batched jobs with Bus::batch() are seriously powerful.',
            'Retrying failed jobs with backoff has saved me twice.',
            'Just containerized a Laravel app. Docker + Sail is perfect.',
            'Octane gave my app a serious speed boost today.',
            'Reverb for self-hosted websockets is a brilliant addition.',
            'Just wrote my first macro on the Collection class.',
            'Custom casts make domain logic feel natural in Eloquent.',
            'Prompts for CLI input are polished and user-friendly.',
            'Laravel Zero makes great CLI apps with minimal effort.',
            'Debugbar helps me see exactly what\'s happening per request.',
            'Telescope is like an x-ray machine for my Laravel app.',
            'Spatie\'s media library handles file uploads beautifully.',
            'Testing with fake storage keeps tests fast and isolated.',
            'Event faking in tests makes assertions super clean.',
            'Http::fake() is a brilliant way to mock API calls.',
            'RefreshDatabase vs DatabaseTransactions - finally get it.',
            'Just hit 100% code coverage on a module. Small win!',
            'PHP 8.3 + Laravel 11 is a joy to write.',
            'Named arguments make complex method calls so readable.',
            'Readonly properties keep my DTOs clean and immutable.',
            'Fibers in PHP are opening up interesting Laravel patterns.',
            'Just published my first Laravel package on Packagist!',
            'Open sourced a small helper package. Feels great to give back.',
            'The Laravel community on Discord is incredibly helpful.',
            'Laracasts has leveled up my skills more than any book.',
            'Laracon talks always leave me with a list of things to try.',
            'Code review today taught me more than a week of tutorials.',
            'Pair programming on a Laravel project is underrated.',
            'Wrote a blog post about my Laravel journey. Sharing tomorrow.',
            'Upgraded from Laravel 10 to 11 without a single issue.',
            'Discovered a neat PR from Taylor today. Always learning.',
            'Laravel\'s release cadence is steady and predictable. Love it.',
            'My team adopted Laravel standards across all projects. 🎉',
            'Onboarded a junior dev to Laravel today. Fun experience.',
            'Built a full CRUD app in two hours. Framework is fast.',
            'Just finished a client project using only core Laravel. Clean.',
            'Refactored legacy spaghetti into clean Laravel services.',
            'Zero downtime deploy using Envoyer. Smooth as butter.',
            'My first Stripe webhook handler in Laravel worked first try.',
            'Laravel never stops surprising me with how thoughtful it is.',
        ];

        // Create chirps for random users
        foreach ($chirps as $message) {
            $createdAt = now()->subMinutes(rand(5, 1440));
            $isEdited = rand(1, 100) <= 20;
            $updatedAt = $isEdited 
            ? $createdAt->copy()->addSeconds(10) // 10s after creation
            : $createdAt; 
            $users->random()->chirps()->create([
                'message' => $message,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }
    }
}