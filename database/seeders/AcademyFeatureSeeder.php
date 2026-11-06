<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Course;
use App\Models\Category;
use App\Models\LiveLecture;
use App\Models\Ebook;
use App\Models\Notification;

class AcademyFeatureSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure Instructor role exists in roles table
        DB::table('roles')->insertOrIgnore([
            ['id' => 3, 'name' => 'Instructor', 'slug' => 'instructor', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Ensure Categories exist
        $catDev = Category::firstOrCreate(['slug' => 'web-development'], ['name' => 'Web Development', 'description' => 'Modern full stack web development technologies.']);
        $catAi = Category::firstOrCreate(['slug' => 'artificial-intelligence'], ['name' => 'AI & Data Science', 'description' => 'Machine learning, deep learning, and AI engineering.']);
        $catDesign = Category::firstOrCreate(['slug' => 'ui-ux-design'], ['name' => 'UI/UX Design', 'description' => 'Product design, user experience, and Figma mastery.']);

        // 3. Create Sample Instructors / Teachers
        $teacher1 = User::updateOrCreate(
            ['email' => 'sarah.jenkins@digitalacademy.com'],
            [
                'name' => 'Dr. Sarah Jenkins',
                'password' => Hash::make('teacher@123'),
                'role' => 'instructor',
                'role_id' => 3,
                'headline' => 'Ex-Google AI Lead & Stanford Fellow',
                'bio' => 'Dr. Sarah has 12+ years of experience training 50,000+ engineers globally in Python, Machine Learning, and Large Language Models. She teaches practical, industry-grade architectures.',
                'avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&auto=format&fit=crop&q=80',
                'balance' => 2450.00,
                'earnings' => 12800.00,
            ]
        );

        $teacher2 = User::updateOrCreate(
            ['email' => 'alex.rivera@digitalacademy.com'],
            [
                'name' => 'Alex Rivera',
                'password' => Hash::make('teacher@123'),
                'role' => 'instructor',
                'role_id' => 3,
                'headline' => 'Staff Cloud Architect & Open Source Creator',
                'bio' => 'Alex is a high-scale infrastructure specialist who has architected systems handling 100M+ requests/day. He focuses on full stack Next.js, Node, Laravel, and distributed microservices.',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80',
                'balance' => 1890.00,
                'earnings' => 9450.00,
            ]
        );

        $teacher3 = User::updateOrCreate(
            ['email' => 'maya.lin@digitalacademy.com'],
            [
                'name' => 'Maya Lin',
                'password' => Hash::make('teacher@123'),
                'role' => 'instructor',
                'role_id' => 3,
                'headline' => 'Principal Product Designer (Design Systems)',
                'bio' => 'Former Lead Designer at tech scale-ups. Maya teaches human-centric UI/UX design, interactive prototyping, and design-to-code workflows.',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&auto=format&fit=crop&q=80',
                'balance' => 3200.00,
                'earnings' => 16500.00,
            ]
        );

        // 4. Update Courses with Teacher, Price, Video Demo & Metrics
        $course1 = Course::find(1);
        if ($course1) {
            $course1->update([
                'name' => 'Python for AI & Modern Automation',
                'slug' => 'python-ai-automation',
                'instructor_id' => $teacher1->id,
                'category_id' => $catAi->id,
                'description' => 'Master modern Python from fundamentals to advanced automation, machine learning pipelines, and API integrations.',
                'price' => 49.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=800&auto=format&fit=crop&q=80',
                'demo_video_url' => 'https://www.youtube.com/watch?v=kqtD5dpn9C8',
                'level' => 'Beginner to Pro',
                'duration' => '10 Weeks (40 Hours)',
                'enrolled_count' => 3420,
                'rating' => 4.9,
                'what_you_will_learn' => "Core Python data structures & OOP\nAutomating real-world tasks & web scraping\nBuilding REST APIs with FastAPI\nWorking with PyTorch & Hugging Face models",
            ]);
        }

        $course2 = Course::find(2);
        if ($course2) {
            $course2->update([
                'name' => 'Full-Stack Modern Web Engineering',
                'slug' => 'full-stack-web-engineering',
                'instructor_id' => $teacher2->id,
                'category_id' => $catDev->id,
                'description' => 'Build reactive, production-ready web apps with modern frontend frameworks, scalable backends, database design, and cloud deployments.',
                'price' => 89.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&auto=format&fit=crop&q=80',
                'demo_video_url' => 'https://www.youtube.com/watch?v=nu_pCVPKzTk',
                'level' => 'Intermediate',
                'duration' => '12 Weeks (60 Hours)',
                'enrolled_count' => 2850,
                'rating' => 4.8,
                'what_you_will_learn' => "Modern JavaScript, Vue & React patterns\nHigh performance REST & GraphQL APIs\nRelational databases & caching with Redis\nDocker containerization & CI/CD pipeline",
            ]);
        }

        $course3 = Course::find(3);
        if ($course3) {
            $course3->update([
                'name' => 'Enterprise System Architecture & Cloud',
                'slug' => 'enterprise-system-architecture',
                'instructor_id' => $teacher2->id,
                'category_id' => $catDev->id,
                'description' => 'Design resilient, scalable distributed systems capable of handling millions of concurrent users with zero downtime.',
                'price' => 149.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&auto=format&fit=crop&q=80',
                'demo_video_url' => 'https://www.youtube.com/watch?v=SqcY0GlETPk',
                'level' => 'Advanced',
                'duration' => '14 Weeks (75 Hours)',
                'enrolled_count' => 1490,
                'rating' => 5.0,
                'what_you_will_learn' => "Microservices vs Modular Monoliths\nEvent-Driven Architecture with Kafka\nDatabase sharding & replication\nSecurity, compliance & observability",
            ]);
        }

        // Add 4th course for UI/UX
        Course::firstOrCreate(
            ['slug' => 'ui-ux-design-systems-mastery'],
            [
                'name' => 'UI/UX Design Systems & High-Fidelity Prototyping',
                'instructor_id' => $teacher3->id,
                'category_id' => $catDesign->id,
                'description' => 'Master Figma, design tokens, responsive layouts, micro-interactions, and build enterprise-grade design systems.',
                'price' => 69.00,
                'thumbnail' => 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?w=800&auto=format&fit=crop&q=80',
                'demo_video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4',
                'level' => 'All Levels',
                'duration' => '8 Weeks (32 Hours)',
                'enrolled_count' => 2130,
                'rating' => 4.9,
                'what_you_will_learn' => "Advanced Figma auto-layout & component variants\nColor theory, typography & visual hierarchy\nConducting user interviews & usability testing\nHandoff to developers with clean design tokens",
            ]
        );

        // 5. Seed Live Lectures & Schedules
        LiveLecture::truncate();
        LiveLecture::create([
            'course_id' => 1,
            'instructor_id' => $teacher1->id,
            'title' => '🔴 LIVE NOW: Building Your First LLM Agent with Python',
            'description' => 'Interactive hands-on session on prompting, tool calling, and chaining with LangChain & Gemini. Live Q&A and code walkthrough.',
            'start_time' => Carbon::now()->subMinutes(15),
            'end_time' => Carbon::now()->addMinutes(75),
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
            'status' => 'live',
            'is_demo' => true,
        ]);

        LiveLecture::create([
            'course_id' => 2,
            'instructor_id' => $teacher2->id,
            'title' => 'Scheduled Today: High-Performance Database Indexing & Query Tuning',
            'description' => 'Deep dive into MySQL & PostgreSQL execution plans, B-trees, composite indices, and eliminating N+1 queries in production.',
            'start_time' => Carbon::now()->addHours(3),
            'end_time' => Carbon::now()->addHours(5),
            'meeting_link' => 'https://meet.google.com/xyz-uvwx-rst',
            'status' => 'scheduled',
            'is_demo' => false,
        ]);

        LiveLecture::create([
            'course_id' => 3,
            'instructor_id' => $teacher2->id,
            'title' => 'Tomorrow: Microservices Resiliency - Circuit Breakers & Retries',
            'description' => 'Architecting fail-safe distributed applications using circuit breakers, distributed rate limiting, and exponential backoff.',
            'start_time' => Carbon::now()->addDay()->setHour(18)->setMinute(0),
            'end_time' => Carbon::now()->addDay()->setHour(20)->setMinute(0),
            'meeting_link' => 'https://meet.google.com/res-ilience-pro',
            'status' => 'scheduled',
            'is_demo' => false,
        ]);

        // 6. Seed Educational E-Books ("E-Book Slot")
        Ebook::truncate();
        Ebook::create([
            'title' => 'The Modern Python Developer Handbook (2026 Edition)',
            'author' => 'Dr. Sarah Jenkins',
            'description' => 'Over 180 pages of clean architecture tips, performance tricks, typing, and async programming in modern Python.',
            'cover_image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600&auto=format&fit=crop&q=80',
            'file_path' => 'materials/pdfs/python_handbook_2026.pdf',
            'pages' => 184,
            'category_id' => $catAi->id,
            'downloads_count' => 12400,
            'is_free' => true,
        ]);

        Ebook::create([
            'title' => 'System Design & Scalability Cheat Sheet',
            'author' => 'Alex Rivera',
            'description' => 'A visual, reference-ready guide covering caching, load balancers, sharding, CAP theorem, and CDN architecture with diagrams.',
            'cover_image' => 'https://images.unsplash.com/photo-1532012164546-f432f2e3777a?w=600&auto=format&fit=crop&q=80',
            'file_path' => 'materials/pdfs/system_design_cheatsheet.pdf',
            'pages' => 96,
            'category_id' => $catDev->id,
            'downloads_count' => 24800,
            'is_free' => true,
        ]);

        Ebook::create([
            'title' => 'Design Tokens & UI Component Architecture',
            'author' => 'Maya Lin',
            'description' => 'The ultimate practical guide for bridging design and frontend development with scalable, reusable design systems.',
            'cover_image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=600&auto=format&fit=crop&q=80',
            'file_path' => 'materials/pdfs/design_tokens_guide.pdf',
            'pages' => 112,
            'category_id' => $catDesign->id,
            'downloads_count' => 9300,
            'is_free' => true,
        ]);

        // 7. Seed Verified Student Testimonials & Course Reviews
        \App\Models\Review::truncate();
        $studentUser = User::where('email', 'student@test.com')->first();
        $firstCourse = Course::first();

        if ($studentUser && $firstCourse) {
            \App\Models\Review::create([
                'user_id' => $studentUser->id,
                'course_id' => $firstCourse->id,
                'rating' => 5,
                'review' => 'The curriculum is extraordinarily structured. Building real distributed microservices hands-on gave me the confidence to pass my technical interviews at a tier-1 firm.',
                'created_at' => now()->subDays(3),
            ]);

            \App\Models\Review::create([
                'user_id' => $studentUser->id,
                'course_id' => $firstCourse->id,
                'rating' => 5,
                'review' => 'Dr. Sarah and Alex Rivera are brilliant mentors. The live whiteboard sessions and doubt-clearing queue made all the difference compared to pre-recorded video sites.',
                'created_at' => now()->subDays(6),
            ]);
        }

        // 8. Seed Broadcast Notifications
        Notification::truncate();
        Notification::create([
            'title' => '🔴 Live Masterclass is Happening Now!',
            'message' => 'Dr. Sarah Jenkins is streaming "Building Your First LLM Agent with Python". Join the live room to participate in Q&A.',
            'type' => 'lecture',
            'link' => '/schedule',
            'is_read' => false,
        ]);

        Notification::create([
            'title' => '📘 New E-Book Available for Free Download',
            'message' => '"System Design & Scalability Cheat Sheet" by Alex Rivera has just been added to the library slot.',
            'type' => 'ebook',
            'link' => '/ebooks',
            'is_read' => false,
        ]);

        Notification::create([
            'title' => '🎓 Become an Instructor on Digital Academy',
            'message' => 'Share your expertise, reach thousands of global students, and keep 80% of course revenue with our 20% platform fee.',
            'type' => 'system',
            'link' => '/become-instructor',
            'is_read' => false,
        ]);
    }
}
