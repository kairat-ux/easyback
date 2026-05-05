# EnglishEasy — Laravel Full Specification
## Stack: Laravel 10 + MySQL + React (Vite) + Chart.js + i18n

---

## ЧТО НУЖНО ДОБАВИТЬ / ИЗМЕНИТЬ

1. **Responsive Web Design** — Bootstrap 5 или Tailwind, mobile-first
2. **Charts (Chart.js)** — Bar, Pie, Polar Area, Line — все 4 типа в проекте
3. **i18n (3 языка)** — EN / RU / KZ — переключатель, перевод всего текста
4. **File Upload** — сохранение в `storage/app/public/uploads` + запись в БД
5. **Email sending** — уведомления через Laravel Mail (Mailtrap для dev)
6. **Роли** — student / teacher / admin через Laravel Sanctum + Middleware

---

## СТРУКТУРА LARAVEL ПРОЕКТА

```
englisheasy/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── LessonController.php
│   │   │   ├── ExerciseController.php
│   │   │   ├── ProgressController.php
│   │   │   ├── UserController.php
│   │   │   ├── FileController.php        ← NEW
│   │   │   ├── ContactController.php
│   │   │   └── ChartController.php       ← NEW
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/
│   │       ├── RegisterRequest.php
│   │       ├── LoginRequest.php
│   │       ├── LessonRequest.php
│   │       ├── ExerciseRequest.php
│   │       └── FileUploadRequest.php     ← NEW
│   ├── Models/
│   │   ├── User.php
│   │   ├── Lesson.php
│   │   ├── Exercise.php
│   │   ├── Attempt.php
│   │   ├── UploadedFile.php              ← NEW
│   │   └── ContactMessage.php
│   └── Mail/
│       ├── WelcomeMail.php               ← NEW
│       ├── TeacherApprovedMail.php       ← NEW
│       └── ContactReplyMail.php          ← NEW
├── database/
│   └── migrations/
│       ├── create_users_table.php
│       ├── create_lessons_table.php
│       ├── create_exercises_table.php
│       ├── create_attempts_table.php
│       ├── create_uploaded_files_table.php  ← NEW
│       └── create_contact_messages_table.php
├── resources/
│   ├── lang/                             ← NEW
│   │   ├── en.json
│   │   ├── ru.json
│   │   └── kz.json
│   └── views/
│       └── emails/
│           ├── welcome.blade.php
│           ├── teacher_approved.blade.php
│           └── contact_reply.blade.php
├── routes/
│   └── api.php
├── frontend/
│   └── src/
│       ├── i18n/                         ← NEW
│       │   ├── index.js
│       │   ├── en.js
│       │   ├── ru.js
│       │   └── kz.js
│       ├── components/
│       │   ├── Navbar.jsx
│       │   ├── Footer.jsx
│       │   ├── LanguageSwitcher.jsx      ← NEW
│       │   ├── charts/
│       │   │   ├── BarChart.jsx          ← NEW
│       │   │   ├── PieChart.jsx          ← NEW
│       │   │   ├── PolarAreaChart.jsx    ← NEW
│       │   │   └── LineChart.jsx         ← NEW
│       │   └── FileUpload.jsx            ← NEW
│       └── pages/
│           ├── Progress.jsx              ← UPDATED (charts)
│           ├── AdminPanel.jsx            ← UPDATED (charts)
│           └── ... (остальные без изменений)
└── .env
```

---

## 1. DATABASE MIGRATIONS

### create_users_table.php
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('role', ['student', 'teacher', 'admin'])->default('student');
    $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
    $table->string('avatar')->nullable();          // путь к аватару
    $table->string('preferred_language', 5)->default('en');  // en / ru / kz
    $table->timestamps();
});
```

### create_lessons_table.php
```php
Schema::create('lessons', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->longText('content')->nullable();
    $table->json('vocabulary')->nullable();       // [{"word":"..","translation":".."}]
    $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
    $table->timestamps();
});
```

### create_exercises_table.php
```php
Schema::create('exercises', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('description')->nullable();
    $table->enum('type', ['multiple_choice', 'fill_blank', 'matching']);
    $table->json('questions');
    $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('lesson_id')->nullable()->constrained('lessons')->nullOnDelete();
    $table->timestamps();
});
```

### create_attempts_table.php
```php
Schema::create('attempts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('exercise_id')->constrained('exercises')->cascadeOnDelete();
    $table->integer('score');
    $table->integer('max_score');
    $table->json('answers')->nullable();
    $table->timestamps();
});
```

### create_uploaded_files_table.php  ← НОВАЯ ТАБЛИЦА
```php
Schema::create('uploaded_files', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->string('original_name');           // оригинальное имя файла
    $table->string('stored_name');             // имя на диске (uuid)
    $table->string('path');                    // storage путь
    $table->string('disk')->default('public'); // диск (public/s3)
    $table->string('mime_type');
    $table->unsignedBigInteger('size');        // в байтах
    $table->string('category')->nullable();    // 'lesson_material', 'avatar', 'exercise'
    $table->morphs('fileable');                // полиморфная связь (Lesson/Exercise/User)
    // fileable_id + fileable_type
    $table->timestamps();
});
```

### create_contact_messages_table.php
```php
Schema::create('contact_messages', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email');
    $table->text('message');
    $table->boolean('replied')->default(false);
    $table->timestamps();
});
```

---

## 2. MODELS

### app/Models/User.php
```php
<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = ['name', 'email', 'password', 'role', 'status', 'avatar', 'preferred_language'];
    protected $hidden = ['password'];
    protected $casts = ['password' => 'hashed'];

    public function lessons() { return $this->hasMany(Lesson::class, 'teacher_id'); }
    public function exercises() { return $this->hasMany(Exercise::class, 'teacher_id'); }
    public function attempts() { return $this->hasMany(Attempt::class, 'student_id'); }
    public function uploadedFiles() { return $this->hasMany(UploadedFile::class); }

    public function isAdmin() { return $this->role === 'admin'; }
    public function isTeacher() { return $this->role === 'teacher'; }
    public function isStudent() { return $this->role === 'student'; }
}
```

### app/Models/UploadedFile.php
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class UploadedFile extends Model
{
    protected $fillable = [
        'user_id', 'original_name', 'stored_name', 'path',
        'disk', 'mime_type', 'size', 'category', 'fileable_id', 'fileable_type'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function fileable() { return $this->morphTo(); }

    // Возвращает публичный URL
    public function getUrlAttribute() {
        return Storage::disk($this->disk)->url($this->path);
    }

    protected $appends = ['url'];
}
```

### app/Models/Lesson.php
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['title', 'description', 'content', 'vocabulary', 'teacher_id'];
    protected $casts = ['vocabulary' => 'array'];

    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function exercises() { return $this->hasMany(Exercise::class); }
    public function files() { return $this->morphMany(UploadedFile::class, 'fileable'); }
}
```

### app/Models/Exercise.php
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = ['title', 'description', 'type', 'questions', 'teacher_id', 'lesson_id'];
    protected $casts = ['questions' => 'array'];

    public function teacher() { return $this->belongsTo(User::class, 'teacher_id'); }
    public function lesson() { return $this->belongsTo(Lesson::class); }
    public function attempts() { return $this->hasMany(Attempt::class); }
    public function files() { return $this->morphMany(UploadedFile::class, 'fileable'); }
}
```

### app/Models/Attempt.php
```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $fillable = ['student_id', 'exercise_id', 'score', 'max_score', 'answers'];
    protected $casts = ['answers' => 'array'];

    public function student() { return $this->belongsTo(User::class, 'student_id'); }
    public function exercise() { return $this->belongsTo(Exercise::class); }
}
```

---

## 3. MIDDLEWARE

### app/Http/Middleware/RoleMiddleware.php
```php
<?php
namespace App\Http\Middleware;
use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, $roles)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        if ($user->status !== 'approved') {
            return response()->json(['error' => 'Account not approved'], 403);
        }
        return $next($request);
    }
}
```

### Регистрация в bootstrap/app.php (Laravel 11) или Kernel.php (Laravel 10):
```php
// Laravel 10 — app/Http/Kernel.php
protected $routeMiddleware = [
    // ...
    'role' => \App\Http\Middleware\RoleMiddleware::class,
];
```

---

## 4. CONTROLLERS

### app/Http/Controllers/AuthController.php
```php
<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $status = $request->role === 'teacher' ? 'pending' : 'approved';

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
            'role'     => $request->role ?? 'student',
            'status'   => $status,
        ]);

        // Отправить welcome email
        Mail::to($user->email)->send(new WelcomeMail($user));

        $message = $user->role === 'teacher'
            ? 'Registration successful. Wait for admin approval.'
            : 'Registration successful. You can login now.';

        return response()->json(['message' => $message], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        if ($user->status === 'pending') {
            return response()->json(['error' => 'Account pending admin approval'], 403);
        }
        if ($user->status === 'rejected') {
            return response()->json(['error' => 'Account was rejected'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
                'preferred_language' => $user->preferred_language,
            ]
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->only(
            'id', 'name', 'email', 'role', 'status', 'avatar', 'preferred_language', 'created_at'
        ));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function updateLanguage(Request $request)
    {
        $request->validate(['language' => 'required|in:en,ru,kz']);
        $request->user()->update(['preferred_language' => $request->language]);
        return response()->json(['message' => 'Language updated']);
    }
}
```

### app/Http/Controllers/FileController.php  ← ГЛАВНЫЙ НОВЫЙ КОНТРОЛЛЕР
```php
<?php
namespace App\Http\Controllers;

use App\Models\UploadedFile;
use App\Http\Requests\FileUploadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    /**
     * Загрузка файла
     * POST /api/files/upload
     * FormData: file, category, fileable_type (optional), fileable_id (optional)
     */
    public function upload(FileUploadRequest $request)
    {
        $file = $request->file('file');
        $category = $request->input('category', 'general');

        // Генерируем уникальное имя чтобы не было коллизий
        $storedName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/' . $category . '/' . $storedName;

        // Сохраняем на диск public (storage/app/public)
        Storage::disk('public')->put($path, file_get_contents($file));

        // Записываем в БД
        $record = UploadedFile::create([
            'user_id'       => $request->user()->id,
            'original_name' => $file->getClientOriginalName(),
            'stored_name'   => $storedName,
            'path'          => $path,
            'disk'          => 'public',
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
            'category'      => $category,
            'fileable_type' => $request->input('fileable_type'),
            'fileable_id'   => $request->input('fileable_id'),
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'file'    => $record,
        ], 201);
    }

    /**
     * Список файлов текущего пользователя
     * GET /api/files
     */
    public function index(Request $request)
    {
        $files = UploadedFile::where('user_id', $request->user()->id)
            ->latest()
            ->get();
        return response()->json($files);
    }

    /**
     * Удалить файл
     * DELETE /api/files/{id}
     */
    public function destroy(Request $request, $id)
    {
        $file = UploadedFile::findOrFail($id);

        // Только владелец или admin
        if ($file->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        Storage::disk($file->disk)->delete($file->path);
        $file->delete();

        return response()->json(['message' => 'File deleted']);
    }

    /**
     * Скачать / открыть файл
     * GET /api/files/{id}/download
     */
    public function download($id)
    {
        $file = UploadedFile::findOrFail($id);
        $fullPath = Storage::disk($file->disk)->path($file->path);
        return response()->download($fullPath, $file->original_name);
    }
}
```

### app/Http/Controllers/ChartController.php  ← ДАННЫЕ ДЛЯ ГРАФИКОВ
```php
<?php
namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\User;
use App\Models\Exercise;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    /**
     * GET /api/charts/admin
     * Данные для Admin Panel — все 4 типа графиков
     */
    public function adminCharts()
    {
        // 1. BAR CHART — кол-во попыток по упражнениям (топ 7)
        $barData = Attempt::select('exercise_id', DB::raw('COUNT(*) as count'))
            ->with('exercise:id,title')
            ->groupBy('exercise_id')
            ->orderByDesc('count')
            ->limit(7)
            ->get()
            ->map(fn($a) => [
                'label' => $a->exercise->title ?? 'Unknown',
                'count' => $a->count,
            ]);

        // 2. PIE CHART — распределение пользователей по ролям
        $pieData = User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get();

        // 3. POLAR AREA — средний балл по типам упражнений
        $polarData = Attempt::select(
                'exercises.type',
                DB::raw('ROUND(AVG(attempts.score / attempts.max_score * 100), 1) as avg_score')
            )
            ->join('exercises', 'attempts.exercise_id', '=', 'exercises.id')
            ->groupBy('exercises.type')
            ->get();

        // 4. LINE CHART — регистрации за последние 30 дней
        $lineData = User::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'bar'   => $barData,
            'pie'   => $pieData,
            'polar' => $polarData,
            'line'  => $lineData,
        ]);
    }

    /**
     * GET /api/charts/student
     * Данные для Student Progress — персональные графики
     */
    public function studentCharts(Request $request)
    {
        $userId = $request->user()->id;

        // 1. BAR CHART — мои баллы по упражнениям
        $barData = Attempt::where('student_id', $userId)
            ->with('exercise:id,title')
            ->latest()
            ->limit(10)
            ->get()
            ->map(fn($a) => [
                'label' => $a->exercise->title ?? 'Unknown',
                'score' => $a->max_score > 0 ? round($a->score / $a->max_score * 100, 1) : 0,
            ]);

        // 2. PIE CHART — завершённые vs незавершённые упражнения
        $totalExercises = Exercise::count();
        $doneExercises = Attempt::where('student_id', $userId)
            ->distinct('exercise_id')->count();
        $pieData = [
            ['label' => 'Completed', 'count' => $doneExercises],
            ['label' => 'Not started', 'count' => max(0, $totalExercises - $doneExercises)],
        ];

        // 3. POLAR AREA — баллы по типам упражнений
        $polarData = Attempt::select(
                'exercises.type',
                DB::raw('ROUND(AVG(attempts.score / attempts.max_score * 100), 1) as avg_score')
            )
            ->join('exercises', 'attempts.exercise_id', '=', 'exercises.id')
            ->where('attempts.student_id', $userId)
            ->groupBy('exercises.type')
            ->get();

        // 4. LINE CHART — прогресс по дням (последние 14 дней)
        $lineData = Attempt::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('ROUND(AVG(score / max_score * 100), 1) as avg_score')
            )
            ->where('student_id', $userId)
            ->where('created_at', '>=', now()->subDays(14))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'bar'   => $barData,
            'pie'   => $pieData,
            'polar' => $polarData,
            'line'  => $lineData,
        ]);
    }
}
```

### app/Http/Controllers/UserController.php
```php
<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\TeacherApprovedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'role', 'status', 'created_at')
            ->orderByDesc('created_at')->get();

        $stats = [
            'total'    => User::count(),
            'students' => User::where('role', 'student')->count(),
            'teachers' => User::where('role', 'teacher')->count(),
            'pending'  => User::where('status', 'pending')->count(),
        ];

        return response()->json(['users' => $users, 'stats' => $stats]);
    }

    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'approved']);

        // Отправить email об одобрении
        Mail::to($user->email)->send(new TeacherApprovedMail($user));

        return response()->json(['message' => 'User approved']);
    }

    public function reject($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'rejected']);
        return response()->json(['message' => 'User rejected']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return response()->json(['error' => 'Cannot delete admin'], 403);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
```

### app/Http/Controllers/ContactController.php
```php
<?php
namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Mail\ContactReplyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'message' => 'required|string|min:10',
        ]);

        $msg = ContactMessage::create($request->only('name', 'email', 'message'));

        // Отправить email пользователю (подтверждение)
        Mail::to($request->email)->send(new ContactReplyMail($request->name));

        return response()->json(['message' => 'Message sent successfully'], 201);
    }
}
```

---

## 5. FORM REQUESTS

### app/Http/Requests/RegisterRequest.php
```php
<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules()
    {
        return [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'in:student,teacher',
        ];
    }
}
```

### app/Http/Requests/FileUploadRequest.php
```php
<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules()
    {
        return [
            'file'          => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,gif,mp3,mp4',
            'category'      => 'nullable|string|in:lesson_material,avatar,exercise,general',
            'fileable_type' => 'nullable|string',
            'fileable_id'   => 'nullable|integer',
        ];
    }
    public function messages()
    {
        return [
            'file.max'   => 'File must be less than 10MB',
            'file.mimes' => 'Allowed: pdf, doc, docx, jpg, png, gif, mp3, mp4',
        ];
    }
}
```

---

## 6. MAIL CLASSES

### app/Mail/WelcomeMail.php
```php
<?php
namespace App\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeMail extends Mailable
{
    public function __construct(public $user) {}

    public function envelope() {
        return new Envelope(subject: 'Welcome to EnglishEasy!');
    }
    public function content() {
        return new Content(view: 'emails.welcome');
    }
}
```

### app/Mail/TeacherApprovedMail.php
```php
<?php
namespace App\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TeacherApprovedMail extends Mailable
{
    public function __construct(public $user) {}

    public function envelope() {
        return new Envelope(subject: 'Your EnglishEasy teacher account is approved!');
    }
    public function content() {
        return new Content(view: 'emails.teacher_approved');
    }
}
```

### app/Mail/ContactReplyMail.php
```php
<?php
namespace App\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ContactReplyMail extends Mailable
{
    public function __construct(public string $name) {}

    public function envelope() {
        return new Envelope(subject: 'We received your message');
    }
    public function content() {
        return new Content(view: 'emails.contact_reply');
    }
}
```

---

## 7. BLADE EMAIL TEMPLATES

### resources/views/emails/welcome.blade.php
```html
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>
  body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
  .container { background: #fff; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 8px; }
  .header { background: #4f46e5; color: white; padding: 20px; text-align: center; border-radius: 6px; }
  .btn { display: inline-block; background: #4f46e5; color: white; padding: 12px 24px;
         text-decoration: none; border-radius: 6px; margin-top: 20px; }
</style></head>
<body>
  <div class="container">
    <div class="header"><h1>Welcome to EnglishEasy! 🎓</h1></div>
    <p>Hi <strong>{{ $user->name }}</strong>,</p>
    <p>Your account has been created successfully.</p>
    @if($user->role === 'teacher')
      <p>⏳ Your teacher account is <strong>pending admin approval</strong>. You'll receive another email once approved.</p>
    @else
      <p>✅ You can now start learning English!</p>
      <a href="{{ config('app.frontend_url') }}/login" class="btn">Start Learning</a>
    @endif
    <p style="color:#888;font-size:12px;margin-top:30px">EnglishEasy Team</p>
  </div>
</body>
</html>
```

### resources/views/emails/teacher_approved.blade.php
```html
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>
  body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
  .container { background: #fff; max-width: 600px; margin: 0 auto; padding: 30px; border-radius: 8px; }
  .header { background: #16a34a; color: white; padding: 20px; text-align: center; border-radius: 6px; }
  .btn { display: inline-block; background: #16a34a; color: white; padding: 12px 24px;
         text-decoration: none; border-radius: 6px; margin-top: 20px; }
</style></head>
<body>
  <div class="container">
    <div class="header"><h1>Account Approved! ✅</h1></div>
    <p>Hi <strong>{{ $user->name }}</strong>,</p>
    <p>🎉 Your teacher account on <strong>EnglishEasy</strong> has been <strong>approved</strong> by the administrator.</p>
    <p>You can now log in and start creating lessons and exercises for your students.</p>
    <a href="{{ config('app.frontend_url') }}/login" class="btn">Go to Dashboard</a>
    <p style="color:#888;font-size:12px;margin-top:30px">EnglishEasy Team</p>
  </div>
</body>
</html>
```

### resources/views/emails/contact_reply.blade.php
```html
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="font-family:Arial,sans-serif;padding:20px;">
  <h2>Hi {{ $name }},</h2>
  <p>Thank you for contacting EnglishEasy! We received your message and will reply within 24 hours.</p>
  <p>Best regards,<br><strong>EnglishEasy Team</strong></p>
</body>
</html>
```

---

## 8. ROUTES (routes/api.php)

```php
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, LessonController, ExerciseController,
    ProgressController, UserController, FileController,
    ContactController, ChartController
};

// Public routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);
Route::post('/contact',       [ContactController::class, 'store']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/auth/me',     [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::patch('/auth/language', [AuthController::class, 'updateLanguage']);

    // Lessons (view — all; create/edit/delete — teacher+admin)
    Route::get('/lessons',      [LessonController::class, 'index']);
    Route::get('/lessons/{id}', [LessonController::class, 'show']);

    Route::middleware('role:teacher,admin')->group(function () {
        Route::post('/lessons',       [LessonController::class, 'store']);
        Route::put('/lessons/{id}',   [LessonController::class, 'update']);
        Route::delete('/lessons/{id}',[LessonController::class, 'destroy']);
    });

    // Exercises
    Route::get('/exercises',       [ExerciseController::class, 'index']);
    Route::get('/exercises/{id}',  [ExerciseController::class, 'show']);

    Route::middleware('role:student')->group(function () {
        Route::post('/exercises/{id}/submit', [ExerciseController::class, 'submit']);
        Route::get('/progress',               [ProgressController::class, 'index']);
        Route::get('/charts/student',         [ChartController::class, 'studentCharts']);
    });

    Route::middleware('role:teacher,admin')->group(function () {
        Route::post('/exercises',        [ExerciseController::class, 'store']);
        Route::put('/exercises/{id}',    [ExerciseController::class, 'update']);
        Route::delete('/exercises/{id}', [ExerciseController::class, 'destroy']);
    });

    // File upload — все залогиненные
    Route::post('/files/upload',          [FileController::class, 'upload']);
    Route::get('/files',                  [FileController::class, 'index']);
    Route::delete('/files/{id}',          [FileController::class, 'destroy']);
    Route::get('/files/{id}/download',    [FileController::class, 'download']);

    // Admin only
    Route::middleware('role:admin')->group(function () {
        Route::get('/users',              [UserController::class, 'index']);
        Route::patch('/users/{id}/approve', [UserController::class, 'approve']);
        Route::patch('/users/{id}/reject',  [UserController::class, 'reject']);
        Route::delete('/users/{id}',      [UserController::class, 'destroy']);
        Route::get('/charts/admin',       [ChartController::class, 'adminCharts']);
    });
});
```

---

## 9. .ENV SETTINGS

```env
APP_NAME=EnglishEasy
APP_URL=http://localhost:8000
FRONTEND_URL=http://localhost:5173

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=englisheasy
DB_USERNAME=root
DB_PASSWORD=

# Mailtrap для разработки (зарегистрироваться на mailtrap.io — бесплатно)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@englisheasy.com
MAIL_FROM_NAME="EnglishEasy"

FILESYSTEM_DISK=public
```

### config/app.php — добавить:
```php
'frontend_url' => env('FRONTEND_URL', 'http://localhost:5173'),
```

---

## 10. FRONTEND — i18n (Мультиязычность)

### frontend/src/i18n/en.js
```js
export default {
  nav: {
    home: 'Home', lessons: 'Lessons', exercises: 'Exercises',
    progress: 'My Progress', dashboard: 'Dashboard', admin: 'Admin Panel',
    login: 'Login', register: 'Register', logout: 'Logout',
  },
  home: {
    title: 'Learn English Online',
    subtitle: 'Interactive lessons, exercises, and real progress tracking',
    startBtn: 'Start Learning',
    loginBtn: 'Login',
    registerBtn: 'Register',
  },
  lessons: { title: 'All Lessons', loading: 'Loading...', empty: 'No lessons yet' },
  exercises: { title: 'Exercises', submit: 'Submit Answers', retry: 'Try Again', score: 'Your Score' },
  progress: {
    title: 'My Progress', completed: 'Exercises Completed',
    avgScore: 'Average Score', history: 'Attempt History',
    exercise: 'Exercise', score: 'Score', best: 'Best', attempts: 'Attempts', date: 'Date',
  },
  admin: {
    title: 'Admin Panel', totalUsers: 'Total Users', students: 'Students',
    teachers: 'Teachers', pending: 'Pending', approve: 'Approve', reject: 'Reject', delete: 'Delete',
  },
  auth: {
    email: 'Email', password: 'Password', name: 'Full Name',
    loginTitle: 'Login', registerTitle: 'Create Account',
    loginBtn: 'Sign In', registerBtn: 'Sign Up',
    noAccount: "Don't have an account?", hasAccount: 'Already have an account?',
    role: 'I am a', student: 'Student', teacher: 'Teacher',
  },
  contact: {
    title: 'Contact Us', name: 'Your Name', email: 'Email',
    message: 'Message', send: 'Send Message', success: 'Message sent!',
  },
  upload: {
    title: 'Upload File', choose: 'Choose File', upload: 'Upload',
    success: 'File uploaded!', maxSize: 'Max 10MB',
  },
  charts: {
    attemptsPerExercise: 'Attempts per Exercise',
    usersByRole: 'Users by Role',
    avgScoreByType: 'Avg Score by Exercise Type',
    registrationsLast30: 'Registrations (Last 30 Days)',
    myScores: 'My Scores',
    completedVsNot: 'Completed vs Not Started',
    progressLast14: 'Progress (Last 14 Days)',
  },
};
```

### frontend/src/i18n/ru.js
```js
export default {
  nav: {
    home: 'Главная', lessons: 'Уроки', exercises: 'Упражнения',
    progress: 'Мой прогресс', dashboard: 'Панель учителя', admin: 'Администратор',
    login: 'Войти', register: 'Регистрация', logout: 'Выйти',
  },
  home: {
    title: 'Учи английский онлайн',
    subtitle: 'Интерактивные уроки, упражнения и отслеживание прогресса',
    startBtn: 'Начать учёбу',
    loginBtn: 'Войти',
    registerBtn: 'Регистрация',
  },
  lessons: { title: 'Все уроки', loading: 'Загрузка...', empty: 'Уроков пока нет' },
  exercises: { title: 'Упражнения', submit: 'Отправить ответы', retry: 'Попробовать снова', score: 'Ваш результат' },
  progress: {
    title: 'Мой прогресс', completed: 'Выполнено упражнений',
    avgScore: 'Средний балл', history: 'История попыток',
    exercise: 'Упражнение', score: 'Балл', best: 'Лучший', attempts: 'Попытки', date: 'Дата',
  },
  admin: {
    title: 'Панель администратора', totalUsers: 'Всего пользователей', students: 'Студенты',
    teachers: 'Учителя', pending: 'На рассмотрении', approve: 'Одобрить', reject: 'Отклонить', delete: 'Удалить',
  },
  auth: {
    email: 'Email', password: 'Пароль', name: 'Полное имя',
    loginTitle: 'Вход', registerTitle: 'Создать аккаунт',
    loginBtn: 'Войти', registerBtn: 'Зарегистрироваться',
    noAccount: 'Нет аккаунта?', hasAccount: 'Уже есть аккаунт?',
    role: 'Я являюсь', student: 'Студент', teacher: 'Учитель',
  },
  contact: {
    title: 'Связаться с нами', name: 'Ваше имя', email: 'Email',
    message: 'Сообщение', send: 'Отправить', success: 'Сообщение отправлено!',
  },
  upload: {
    title: 'Загрузить файл', choose: 'Выбрать файл', upload: 'Загрузить',
    success: 'Файл загружен!', maxSize: 'Макс. 10МБ',
  },
  charts: {
    attemptsPerExercise: 'Попытки по упражнениям',
    usersByRole: 'Пользователи по ролям',
    avgScoreByType: 'Средний балл по типам',
    registrationsLast30: 'Регистрации (последние 30 дней)',
    myScores: 'Мои результаты',
    completedVsNot: 'Выполнено / Не начато',
    progressLast14: 'Прогресс (последние 14 дней)',
  },
};
```

### frontend/src/i18n/kz.js
```js
export default {
  nav: {
    home: 'Басты бет', lessons: 'Сабақтар', exercises: 'Жаттығулар',
    progress: 'Менің прогресім', dashboard: 'Мұғалім панелі', admin: 'Әкімші',
    login: 'Кіру', register: 'Тіркелу', logout: 'Шығу',
  },
  home: {
    title: 'Ағылшынды онлайн үйрен',
    subtitle: 'Интерактивті сабақтар, жаттығулар және прогресті бақылау',
    startBtn: 'Оқуды бастау',
    loginBtn: 'Кіру',
    registerBtn: 'Тіркелу',
  },
  lessons: { title: 'Барлық сабақтар', loading: 'Жүктелуде...', empty: 'Сабақтар жоқ' },
  exercises: { title: 'Жаттығулар', submit: 'Жауаптарды жіберу', retry: 'Қайтадан көру', score: 'Нәтиже' },
  progress: {
    title: 'Менің прогресім', completed: 'Орындалған жаттығулар',
    avgScore: 'Орташа балл', history: 'Талпыныс тарихы',
    exercise: 'Жаттығу', score: 'Балл', best: 'Үздік', attempts: 'Талпыныстар', date: 'Күн',
  },
  admin: {
    title: 'Әкімші панелі', totalUsers: 'Барлық пайдаланушылар', students: 'Студенттер',
    teachers: 'Мұғалімдер', pending: 'Күтуде', approve: 'Бекіту', reject: 'Қабылдамау', delete: 'Жою',
  },
  auth: {
    email: 'Email', password: 'Құпия сөз', name: 'Толық аты',
    loginTitle: 'Кіру', registerTitle: 'Аккаунт жасау',
    loginBtn: 'Кіру', registerBtn: 'Тіркелу',
    noAccount: 'Аккаунт жоқ па?', hasAccount: 'Аккаунт бар ма?',
    role: 'Мен', student: 'Студент', teacher: 'Мұғалім',
  },
  contact: {
    title: 'Бізбен байланыс', name: 'Атыңыз', email: 'Email',
    message: 'Хабарлама', send: 'Жіберу', success: 'Хабарлама жіберілді!',
  },
  upload: {
    title: 'Файл жүктеу', choose: 'Файл таңдау', upload: 'Жүктеу',
    success: 'Файл жүктелді!', maxSize: 'Макс. 10МБ',
  },
  charts: {
    attemptsPerExercise: 'Жаттығулар бойынша талпыныстар',
    usersByRole: 'Рөлдер бойынша пайдаланушылар',
    avgScoreByType: 'Орташа балл түрлері бойынша',
    registrationsLast30: 'Тіркелулер (соңғы 30 күн)',
    myScores: 'Менің нәтижелерім',
    completedVsNot: 'Орындалды / Басталмады',
    progressLast14: 'Прогресс (соңғы 14 күн)',
  },
};
```

### frontend/src/i18n/index.js
```js
import en from './en';
import ru from './ru';
import kz from './kz';

const translations = { en, ru, kz };

export function t(lang, key) {
  const keys = key.split('.');
  let result = translations[lang] || translations['en'];
  for (const k of keys) {
    result = result?.[k];
    if (result === undefined) return key;
  }
  return result;
}

export const availableLanguages = [
  { code: 'en', label: 'English', flag: '🇬🇧' },
  { code: 'ru', label: 'Русский', flag: '🇷🇺' },
  { code: 'kz', label: 'Қазақша', flag: '🇰🇿' },
];

export default translations;
```

---

## 11. FRONTEND — Language Context

### frontend/src/context/LanguageContext.jsx
```jsx
import { createContext, useContext, useState, useEffect } from 'react';
import { t as translate, availableLanguages } from '../i18n';
import { useAuth } from './AuthContext';
import { api } from '../api/client';

const LanguageContext = createContext(null);

export function LanguageProvider({ children }) {
  const [lang, setLang] = useState(() => localStorage.getItem('lang') || 'en');
  const { user } = useAuth();

  useEffect(() => {
    if (user?.preferred_language && user.preferred_language !== lang) {
      setLang(user.preferred_language);
    }
  }, [user]);

  const changeLang = async (code) => {
    setLang(code);
    localStorage.setItem('lang', code);
    // Синхронизировать с сервером если залогинен
    if (user) {
      try { await api.patch('/auth/language', { language: code }); } catch {}
    }
  };

  const t = (key) => translate(lang, key);

  return (
    <LanguageContext.Provider value={{ lang, changeLang, t, availableLanguages }}>
      {children}
    </LanguageContext.Provider>
  );
}

export const useLang = () => useContext(LanguageContext);
```

### Обновить App.jsx — обернуть в LanguageProvider:
```jsx
import { LanguageProvider } from './context/LanguageContext';

// В return:
<AuthProvider>
  <LanguageProvider>
    <BrowserRouter>
      ...
    </BrowserRouter>
  </LanguageProvider>
</AuthProvider>
```

---

## 12. FRONTEND — Language Switcher Component

### frontend/src/components/LanguageSwitcher.jsx
```jsx
import { useLang } from '../context/LanguageContext';

export default function LanguageSwitcher() {
  const { lang, changeLang, availableLanguages } = useLang();

  return (
    <div className="lang-switcher">
      {availableLanguages.map(({ code, label, flag }) => (
        <button
          key={code}
          onClick={() => changeLang(code)}
          className={`lang-btn ${lang === code ? 'active' : ''}`}
          title={label}
        >
          {flag} {code.toUpperCase()}
        </button>
      ))}
    </div>
  );
}
```

### CSS для LanguageSwitcher (добавить в App.css или index.css):
```css
.lang-switcher {
  display: flex;
  gap: 4px;
  align-items: center;
}
.lang-btn {
  padding: 4px 10px;
  border: 1px solid #e2e8f0;
  background: transparent;
  border-radius: 4px;
  cursor: pointer;
  font-size: 13px;
  transition: all 0.2s;
}
.lang-btn:hover { background: #f1f5f9; }
.lang-btn.active { background: #4f46e5; color: white; border-color: #4f46e5; }
```

### Использование в Navbar.jsx:
```jsx
import { useLang } from '../context/LanguageContext';
import LanguageSwitcher from './LanguageSwitcher';

export default function Navbar() {
  const { user, logout } = useAuth();
  const { t } = useLang();

  return (
    <nav className="navbar">
      <div className="nav-brand">EnglishEasy</div>
      <div className="nav-links">
        <a href="/">{t('nav.home')}</a>
        {user && <a href="/lessons">{t('nav.lessons')}</a>}
        {user && <a href="/exercises">{t('nav.exercises')}</a>}
        {user?.role === 'student' && <a href="/progress">{t('nav.progress')}</a>}
        {(user?.role === 'teacher' || user?.role === 'admin') && <a href="/teacher">{t('nav.dashboard')}</a>}
        {user?.role === 'admin' && <a href="/admin">{t('nav.admin')}</a>}
      </div>
      <div className="nav-right">
        <LanguageSwitcher />
        {user
          ? <button onClick={logout}>{t('nav.logout')}</button>
          : <>
              <a href="/login">{t('nav.login')}</a>
              <a href="/register">{t('nav.register')}</a>
            </>
        }
      </div>
    </nav>
  );
}
```

---

## 13. FRONTEND — Chart Components

### Установка:
```bash
cd frontend
npm install chart.js react-chartjs-2
```

### frontend/src/components/charts/BarChart.jsx
```jsx
import { Bar } from 'react-chartjs-2';
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend } from 'chart.js';
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

export default function BarChart({ data, title }) {
  const chartData = {
    labels: data.map(d => d.label),
    datasets: [{
      label: title,
      data: data.map(d => d.count ?? d.score),
      backgroundColor: [
        '#6366f1','#8b5cf6','#a78bfa','#c4b5fd',
        '#818cf8','#4f46e5','#7c3aed',
      ],
      borderRadius: 6,
    }],
  };
  const options = {
    responsive: true,
    plugins: { legend: { position: 'top' }, title: { display: true, text: title } },
  };
  return <Bar data={chartData} options={options} />;
}
```

### frontend/src/components/charts/PieChart.jsx
```jsx
import { Pie } from 'react-chartjs-2';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
ChartJS.register(ArcElement, Tooltip, Legend);

export default function PieChart({ data, title }) {
  const chartData = {
    labels: data.map(d => d.label ?? d.role),
    datasets: [{
      data: data.map(d => d.count),
      backgroundColor: ['#6366f1','#f59e0b','#10b981','#ef4444','#3b82f6'],
      borderWidth: 2,
    }],
  };
  return (
    <div>
      <h3 style={{ textAlign: 'center', marginBottom: 8 }}>{title}</h3>
      <Pie data={chartData} />
    </div>
  );
}
```

### frontend/src/components/charts/PolarAreaChart.jsx
```jsx
import { PolarArea } from 'react-chartjs-2';
import { Chart as ChartJS, RadialLinearScale, ArcElement, Tooltip, Legend } from 'chart.js';
ChartJS.register(RadialLinearScale, ArcElement, Tooltip, Legend);

export default function PolarAreaChart({ data, title }) {
  const chartData = {
    labels: data.map(d => d.type ?? d.label),
    datasets: [{
      data: data.map(d => d.avg_score ?? d.count),
      backgroundColor: [
        'rgba(99,102,241,0.6)',
        'rgba(245,158,11,0.6)',
        'rgba(16,185,129,0.6)',
        'rgba(239,68,68,0.6)',
      ],
      borderWidth: 1,
    }],
  };
  return (
    <div>
      <h3 style={{ textAlign: 'center', marginBottom: 8 }}>{title}</h3>
      <PolarArea data={chartData} />
    </div>
  );
}
```

### frontend/src/components/charts/LineChart.jsx
```jsx
import { Line } from 'react-chartjs-2';
import {
  Chart as ChartJS, CategoryScale, LinearScale, PointElement,
  LineElement, Title, Tooltip, Legend, Filler
} from 'chart.js';
ChartJS.register(CategoryScale, LinearScale, PointElement, LineElement, Title, Tooltip, Legend, Filler);

export default function LineChart({ data, title, yLabel = 'Value' }) {
  const chartData = {
    labels: data.map(d => d.date),
    datasets: [{
      label: yLabel,
      data: data.map(d => d.count ?? d.avg_score),
      borderColor: '#6366f1',
      backgroundColor: 'rgba(99,102,241,0.1)',
      fill: true,
      tension: 0.4,
      pointBackgroundColor: '#6366f1',
      pointRadius: 4,
    }],
  };
  const options = {
    responsive: true,
    plugins: { legend: { position: 'top' }, title: { display: true, text: title } },
    scales: { y: { beginAtZero: true } },
  };
  return <Line data={chartData} options={options} />;
}
```

---

## 14. FRONTEND — Progress Page с графиками

### frontend/src/pages/Progress.jsx
```jsx
import { useState, useEffect } from 'react';
import { api } from '../api/client';
import { useAuth } from '../context/AuthContext';
import { useLang } from '../context/LanguageContext';
import BarChart from '../components/charts/BarChart';
import PieChart from '../components/charts/PieChart';
import PolarAreaChart from '../components/charts/PolarAreaChart';
import LineChart from '../components/charts/LineChart';

export default function Progress() {
  const { user } = useAuth();
  const { t } = useLang();
  const [progress, setProgress] = useState(null);
  const [charts, setCharts] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    Promise.all([
      api.get('/progress'),
      api.get('/charts/student'),
    ]).then(([prog, chart]) => {
      setProgress(prog);
      setCharts(chart);
    }).finally(() => setLoading(false));
  }, []);

  if (loading) return <div className="loading">{t('lessons.loading')}</div>;

  return (
    <div className="container progress-page">
      <h1>{t('progress.title')}</h1>
      <p><strong>{user.name}</strong> — {user.email}</p>

      {/* Stats Cards */}
      <div className="stats-grid">
        <div className="stat-card">
          <h2>{progress.stats.exercises_completed}</h2>
          <p>{t('progress.completed')}</p>
        </div>
        <div className="stat-card">
          <h2>{progress.stats.avg_score}%</h2>
          <p>{t('progress.avgScore')}</p>
        </div>
      </div>

      {/* Charts Grid — все 4 типа */}
      {charts && (
        <div className="charts-grid">
          <div className="chart-card">
            <BarChart data={charts.bar} title={t('charts.myScores')} />
          </div>
          <div className="chart-card">
            <PieChart data={charts.pie} title={t('charts.completedVsNot')} />
          </div>
          <div className="chart-card">
            <PolarAreaChart data={charts.polar} title={t('charts.avgScoreByType')} />
          </div>
          <div className="chart-card">
            <LineChart data={charts.line} title={t('charts.progressLast14')} yLabel="%" />
          </div>
        </div>
      )}

      {/* Attempt Table */}
      <h2>{t('progress.history')}</h2>
      <div className="table-responsive">
        <table className="table">
          <thead>
            <tr>
              <th>{t('progress.exercise')}</th>
              <th>{t('progress.score')}</th>
              <th>Max</th>
              <th>{t('progress.best')}</th>
              <th>{t('progress.attempts')}</th>
              <th>{t('progress.date')}</th>
            </tr>
          </thead>
          <tbody>
            {progress.attempts.map(a => (
              <tr key={a.id}>
                <td>{a.exercise_title}</td>
                <td>{a.score}</td>
                <td>{a.max_score}</td>
                <td><strong>{a.best_score}</strong></td>
                <td>{a.attempt_count}</td>
                <td>{new Date(a.attempted_at).toLocaleDateString()}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
```

---

## 15. FRONTEND — Admin Panel с графиками

### frontend/src/pages/AdminPanel.jsx (обновлённый)
```jsx
import { useState, useEffect } from 'react';
import { api } from '../api/client';
import { useLang } from '../context/LanguageContext';
import BarChart from '../components/charts/BarChart';
import PieChart from '../components/charts/PieChart';
import PolarAreaChart from '../components/charts/PolarAreaChart';
import LineChart from '../components/charts/LineChart';

export default function AdminPanel() {
  const { t } = useLang();
  const [data, setData] = useState(null);
  const [charts, setCharts] = useState(null);
  const [loading, setLoading] = useState(true);

  const fetchData = () => {
    Promise.all([api.get('/users'), api.get('/charts/admin')])
      .then(([users, chartData]) => { setData(users); setCharts(chartData); })
      .finally(() => setLoading(false));
  };
  useEffect(fetchData, []);

  const approve = async (id) => { await api.patch(`/users/${id}/approve`); fetchData(); };
  const reject  = async (id) => { await api.patch(`/users/${id}/reject`); fetchData(); };
  const del     = async (id) => { if (!confirm('Delete?')) return; await api.delete(`/users/${id}`); fetchData(); };

  if (loading) return <div className="loading">{t('lessons.loading')}</div>;

  return (
    <div className="container admin-panel">
      <h1>{t('admin.title')}</h1>

      {/* Stats */}
      <div className="stats-grid">
        <div className="stat-card"><h2>{data.stats.total}</h2><p>{t('admin.totalUsers')}</p></div>
        <div className="stat-card"><h2>{data.stats.students}</h2><p>{t('admin.students')}</p></div>
        <div className="stat-card"><h2>{data.stats.teachers}</h2><p>{t('admin.teachers')}</p></div>
        <div className="stat-card pending"><h2>{data.stats.pending}</h2><p>{t('admin.pending')}</p></div>
      </div>

      {/* Charts — все 4 типа */}
      {charts && (
        <div className="charts-grid">
          <div className="chart-card">
            <BarChart data={charts.bar} title={t('charts.attemptsPerExercise')} />
          </div>
          <div className="chart-card">
            <PieChart data={charts.pie} title={t('charts.usersByRole')} />
          </div>
          <div className="chart-card">
            <PolarAreaChart data={charts.polar} title={t('charts.avgScoreByType')} />
          </div>
          <div className="chart-card">
            <LineChart data={charts.line} title={t('charts.registrationsLast30')} yLabel="Users" />
          </div>
        </div>
      )}

      {/* Users Table */}
      <div className="table-responsive">
        <table className="table">
          <thead>
            <tr><th>ID</th><th>{t('auth.name')}</th><th>{t('auth.email')}</th>
                <th>Role</th><th>Status</th><th>Actions</th></tr>
          </thead>
          <tbody>
            {data.users.map(u => (
              <tr key={u.id}>
                <td>{u.id}</td><td>{u.name}</td><td>{u.email}</td>
                <td><span className={`badge badge-${u.role}`}>{u.role}</span></td>
                <td><span className={`badge badge-${u.status}`}>{u.status}</span></td>
                <td className="action-btns">
                  {u.status === 'pending' && <>
                    <button className="btn btn-success btn-sm" onClick={() => approve(u.id)}>{t('admin.approve')}</button>
                    <button className="btn btn-warning btn-sm" onClick={() => reject(u.id)}>{t('admin.reject')}</button>
                  </>}
                  {u.role !== 'admin' &&
                    <button className="btn btn-danger btn-sm" onClick={() => del(u.id)}>{t('admin.delete')}</button>}
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
```

---

## 16. FRONTEND — File Upload Component

### frontend/src/components/FileUpload.jsx
```jsx
import { useState } from 'react';
import { useLang } from '../context/LanguageContext';

export default function FileUpload({ category = 'general', fileableType, fileableId, onSuccess }) {
  const { t } = useLang();
  const [file, setFile] = useState(null);
  const [uploading, setUploading] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  const handleUpload = async () => {
    if (!file) return;
    setUploading(true);
    setError('');

    const formData = new FormData();
    formData.append('file', file);
    formData.append('category', category);
    if (fileableType) formData.append('fileable_type', fileableType);
    if (fileableId)   formData.append('fileable_id', fileableId);

    try {
      const token = localStorage.getItem('token');
      const res = await fetch('/api/files/upload', {
        method: 'POST',
        headers: { Authorization: `Bearer ${token}` },
        body: formData,
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Upload failed');
      setSuccess(t('upload.success'));
      setFile(null);
      if (onSuccess) onSuccess(data.file);
    } catch (err) {
      setError(err.message);
    } finally {
      setUploading(false);
    }
  };

  return (
    <div className="file-upload">
      <h3>{t('upload.title')}</h3>
      <p className="hint">{t('upload.maxSize')} — PDF, DOC, JPG, PNG, MP3, MP4</p>

      <div className="upload-area">
        <input
          type="file"
          id="file-input"
          accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.mp3,.mp4"
          onChange={e => { setFile(e.target.files[0]); setSuccess(''); setError(''); }}
          style={{ display: 'none' }}
        />
        <label htmlFor="file-input" className="upload-label">
          📎 {file ? file.name : t('upload.choose')}
        </label>
        {file && (
          <span className="file-size">
            ({(file.size / 1024 / 1024).toFixed(2)} MB)
          </span>
        )}
      </div>

      {error   && <div className="alert alert-error">{error}</div>}
      {success && <div className="alert alert-success">{success}</div>}

      <button
        className="btn btn-primary"
        onClick={handleUpload}
        disabled={!file || uploading}
      >
        {uploading ? 'Uploading...' : t('upload.upload')}
      </button>
    </div>
  );
}
```

---

## 17. RESPONSIVE CSS (добавить в frontend/src/index.css)

```css
/* ====== CSS VARIABLES ====== */
:root {
  --primary: #4f46e5;
  --primary-hover: #4338ca;
  --success: #16a34a;
  --danger: #dc2626;
  --warning: #d97706;
  --bg: #f8fafc;
  --card-bg: #ffffff;
  --text: #1e293b;
  --text-muted: #64748b;
  --border: #e2e8f0;
  --radius: 8px;
  --shadow: 0 1px 3px rgba(0,0,0,0.1);
  --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
}

* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', -apple-system, sans-serif; background: var(--bg); color: var(--text); }

/* ====== LAYOUT ====== */
.container { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 16px; }

/* ====== NAVBAR — RESPONSIVE ====== */
.navbar {
  background: white;
  border-bottom: 1px solid var(--border);
  padding: 12px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 8px;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: var(--shadow);
}
.nav-brand { font-size: 20px; font-weight: 700; color: var(--primary); }
.nav-links { display: flex; gap: 16px; flex-wrap: wrap; }
.nav-links a { color: var(--text); text-decoration: none; font-size: 14px; }
.nav-links a:hover { color: var(--primary); }
.nav-right { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }

/* Mobile Navbar */
@media (max-width: 768px) {
  .navbar { padding: 10px 16px; }
  .nav-links { order: 3; width: 100%; justify-content: center; gap: 12px; }
  .nav-right { gap: 8px; }
}

/* ====== STATS GRID — RESPONSIVE ====== */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 16px;
  margin: 24px 0;
}
.stat-card {
  background: white;
  border-radius: var(--radius);
  padding: 20px;
  text-align: center;
  box-shadow: var(--shadow);
  border-top: 4px solid var(--primary);
}
.stat-card h2 { font-size: 32px; color: var(--primary); }
.stat-card p { color: var(--text-muted); font-size: 13px; margin-top: 4px; }
.stat-card.pending { border-top-color: var(--warning); }
.stat-card.pending h2 { color: var(--warning); }

/* ====== CHARTS GRID — RESPONSIVE ====== */
.charts-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 20px;
  margin: 24px 0;
}
.chart-card {
  background: white;
  border-radius: var(--radius);
  padding: 20px;
  box-shadow: var(--shadow);
}
@media (max-width: 900px) { .charts-grid { grid-template-columns: 1fr; } }

/* ====== TABLE — RESPONSIVE ====== */
.table-responsive { overflow-x: auto; border-radius: var(--radius); }
.table { width: 100%; border-collapse: collapse; background: white; }
.table th, .table td { padding: 12px 16px; text-align: left; border-bottom: 1px solid var(--border); }
.table th { background: #f8fafc; font-weight: 600; font-size: 13px; text-transform: uppercase; }
.table tr:hover td { background: #f8fafc; }

/* ====== BADGES ====== */
.badge { display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
.badge-student  { background: #dbeafe; color: #1d4ed8; }
.badge-teacher  { background: #dcfce7; color: #15803d; }
.badge-admin    { background: #fae8ff; color: #9333ea; }
.badge-approved { background: #dcfce7; color: #15803d; }
.badge-pending  { background: #fef9c3; color: #a16207; }
.badge-rejected { background: #fee2e2; color: #dc2626; }

/* ====== BUTTONS ====== */
.btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px;
       border-radius: var(--radius); font-size: 14px; font-weight: 500;
       cursor: pointer; border: none; transition: all 0.2s; }
.btn-primary { background: var(--primary); color: white; }
.btn-primary:hover { background: var(--primary-hover); }
.btn-success { background: var(--success); color: white; }
.btn-danger  { background: var(--danger); color: white; }
.btn-warning { background: var(--warning); color: white; }
.btn-sm { padding: 4px 10px; font-size: 12px; }
.btn:disabled { opacity: 0.6; cursor: not-allowed; }
.action-btns { display: flex; gap: 6px; flex-wrap: wrap; }

/* ====== FORMS ====== */
.auth-container {
  max-width: 420px;
  margin: 60px auto;
  background: white;
  border-radius: var(--radius);
  padding: 32px;
  box-shadow: var(--shadow-md);
}
.auth-container h1 { margin-bottom: 24px; text-align: center; }
.auth-container form { display: flex; flex-direction: column; gap: 14px; }
.auth-container input,
.auth-container select { padding: 10px 14px; border: 1px solid var(--border);
                          border-radius: var(--radius); font-size: 14px; width: 100%; }
.auth-container input:focus { outline: none; border-color: var(--primary); }
.auth-container button[type=submit] { padding: 12px; background: var(--primary);
                                       color: white; border: none; border-radius: var(--radius);
                                       font-size: 15px; cursor: pointer; }
.auth-container p { text-align: center; margin-top: 16px; font-size: 14px; }
.auth-container a { color: var(--primary); }

/* ====== ALERTS ====== */
.alert { padding: 10px 14px; border-radius: var(--radius); margin: 8px 0; font-size: 14px; }
.alert-error   { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
.alert-success { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }

/* ====== FILE UPLOAD ====== */
.file-upload { background: white; border-radius: var(--radius); padding: 20px; box-shadow: var(--shadow); }
.file-upload h3 { margin-bottom: 8px; }
.file-upload .hint { color: var(--text-muted); font-size: 13px; margin-bottom: 12px; }
.upload-area { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 12px; }
.upload-label { display: inline-block; padding: 8px 16px; background: #f1f5f9;
                border: 2px dashed var(--border); border-radius: var(--radius);
                cursor: pointer; font-size: 14px; transition: all 0.2s; }
.upload-label:hover { border-color: var(--primary); background: #ede9fe; }
.file-size { color: var(--text-muted); font-size: 12px; }

/* ====== TABS ====== */
.tabs { display: flex; gap: 0; border-bottom: 2px solid var(--border); margin-bottom: 20px; }
.tabs button { padding: 10px 20px; border: none; background: none; cursor: pointer;
               font-size: 14px; font-weight: 500; color: var(--text-muted);
               border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all 0.2s; }
.tabs button.active { color: var(--primary); border-bottom-color: var(--primary); }

/* ====== PAGES ====== */
.progress-page, .admin-panel, .teacher-dashboard { padding: 24px 0; }
.progress-page h1, .admin-panel h1 { margin-bottom: 8px; }

/* ====== RESPONSIVE GENERAL ====== */
@media (max-width: 480px) {
  .auth-container { margin: 20px 16px; padding: 24px 16px; }
  .table th, .table td { padding: 8px 10px; font-size: 13px; }
  .stats-grid { grid-template-columns: repeat(2, 1fr); }
}
```

---

## 18. УСТАНОВКА И ЗАПУСК

### Команды для Claude Code:

```bash
# 1. Создать Laravel проект (если не создан)
composer create-project laravel/laravel englisheasy
cd englisheasy

# 2. Установить Sanctum
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

# 3. Настроить .env (обновить DB_ и MAIL_ переменные)

# 4. Запустить миграции + сидер
php artisan migrate
php artisan db:seed --class=AdminSeeder

# 5. Создать симлинк для файлов
php artisan storage:link

# 6. Frontend
npm create vite@latest frontend -- --template react
cd frontend
npm install react-router-dom chart.js react-chartjs-2

# 7. Запустить
php artisan serve        # backend :8000
cd frontend && npm run dev  # frontend :5173
```

### database/seeders/AdminSeeder.php
```php
<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@englisheasy.com'],
            [
                'name'     => 'Admin',
                'password' => 'admin123',
                'role'     => 'admin',
                'status'   => 'approved',
            ]
        );
    }
}
```

### config/cors.php — обновить:
```php
'paths' => ['api/*'],
'allowed_origins' => ['http://localhost:5173'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'supports_credentials' => false,
```

---

## ИТОГОВЫЙ CHECKLIST ДЛЯ СУБАГЕНТА

- [ ] Все миграции созданы и выполнены
- [ ] Модели с relationships написаны
- [ ] RoleMiddleware зарегистрирован
- [ ] Все контроллеры (Auth, Lesson, Exercise, Progress, User, File, Chart, Contact)
- [ ] FormRequest валидация
- [ ] 3 Mail класса + Blade шаблоны
- [ ] routes/api.php с правильными middleware
- [ ] `php artisan storage:link` выполнен
- [ ] Frontend: AuthContext + LanguageContext
- [ ] Frontend: 3 файла переводов (en, ru, kz)
- [ ] Frontend: LanguageSwitcher в Navbar
- [ ] Frontend: 4 chart компонента (Bar, Pie, PolarArea, Line)
- [ ] Progress.jsx — все 4 чарта
- [ ] AdminPanel.jsx — все 4 чарта
- [ ] FileUpload.jsx компонент
- [ ] Responsive CSS (mobile + tablet + desktop)
- [ ] .env настроен (DB + Mail + storage)
- [ ] AdminSeeder выполнен
