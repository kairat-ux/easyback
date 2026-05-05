<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@englisheasy.com'],
            [
                'name'     => 'Admin',
                'password' => 'admin123',
                'role'     => 'admin',
                'status'   => 'approved',
            ]
        );

        // Create a sample teacher
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@englisheasy.com'],
            [
                'name'     => 'Sample Teacher',
                'password' => 'teacher123',
                'role'     => 'teacher',
                'status'   => 'approved',
            ]
        );

        // Create a sample student
        User::firstOrCreate(
            ['email' => 'student@englisheasy.com'],
            [
                'name'     => 'Sample Student',
                'password' => 'student123',
                'role'     => 'student',
                'status'   => 'approved',
            ]
        );

        // Create sample lessons
        $lesson1 = Lesson::firstOrCreate(
            ['title' => 'Introduction to English Grammar'],
            [
                'description' => 'Learn the basics of English grammar including nouns, verbs, and adjectives.',
                'content'     => '<h2>Welcome to English Grammar</h2><p>In this lesson we will cover the fundamental building blocks of the English language.</p>',
                'vocabulary'  => [
                    ['word' => 'noun',      'translation' => 'существительное'],
                    ['word' => 'verb',      'translation' => 'глагол'],
                    ['word' => 'adjective', 'translation' => 'прилагательное'],
                ],
                'teacher_id'  => $teacher->id,
            ]
        );

        $lesson2 = Lesson::firstOrCreate(
            ['title' => 'Common English Phrases'],
            [
                'description' => 'Essential phrases for everyday communication in English.',
                'content'     => '<h2>Common Phrases</h2><p>Master these phrases to communicate confidently in everyday situations.</p>',
                'vocabulary'  => [
                    ['word' => 'hello',      'translation' => 'привет'],
                    ['word' => 'thank you',  'translation' => 'спасибо'],
                    ['word' => 'please',     'translation' => 'пожалуйста'],
                    ['word' => 'excuse me',  'translation' => 'извините'],
                ],
                'teacher_id'  => $teacher->id,
            ]
        );

        // Create sample exercises
        Exercise::firstOrCreate(
            ['title' => 'Grammar: Multiple Choice Quiz'],
            [
                'description' => 'Test your knowledge of basic English grammar.',
                'type'        => 'multiple_choice',
                'questions'   => [
                    [
                        'question'        => 'Which word is a noun?',
                        'options'         => ['run', 'happy', 'table', 'quickly'],
                        'correct_answer'  => 'table',
                    ],
                    [
                        'question'        => 'Which word is a verb?',
                        'options'         => ['beautiful', 'swim', 'chair', 'loudly'],
                        'correct_answer'  => 'swim',
                    ],
                    [
                        'question'        => 'Which sentence is correct?',
                        'options'         => ['She go to school.', 'She goes to school.', 'She going to school.', 'She gone to school.'],
                        'correct_answer'  => 'She goes to school.',
                    ],
                ],
                'teacher_id'  => $teacher->id,
                'lesson_id'   => $lesson1->id,
            ]
        );

        Exercise::firstOrCreate(
            ['title' => 'Fill in the Blank: Common Phrases'],
            [
                'description' => 'Fill in the missing words in common English phrases.',
                'type'        => 'fill_blank',
                'questions'   => [
                    [
                        'sentence'       => 'Good ___, how are you?',
                        'correct_answer' => 'morning',
                    ],
                    [
                        'sentence'       => 'Nice to ___ you.',
                        'correct_answer' => 'meet',
                    ],
                    [
                        'sentence'       => 'See you ___!',
                        'correct_answer' => 'later',
                    ],
                ],
                'teacher_id'  => $teacher->id,
                'lesson_id'   => $lesson2->id,
            ]
        );

        Exercise::firstOrCreate(
            ['title' => 'Matching: English to Russian'],
            [
                'description' => 'Match English words with their Russian translations.',
                'type'        => 'matching',
                'questions'   => [
                    ['left' => 'cat',   'right' => 'кошка'],
                    ['left' => 'dog',   'right' => 'собака'],
                    ['left' => 'house', 'right' => 'дом'],
                    ['left' => 'water', 'right' => 'вода'],
                ],
                'teacher_id'  => $teacher->id,
                'lesson_id'   => $lesson1->id,
            ]
        );

        $this->command->info('AdminSeeder completed: admin, teacher, student, 2 lessons, 3 exercises created.');
    }
}
