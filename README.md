# 📝 Simple Task Management System (Laravel 12 + Sail + Docker)

A Laravel 12-based task manager with user roles, task assignment, activity logs, RESTful API, and full Docker support using Laravel Sail.

---

## 🚀 Features

- ✅ User registration/login/password reset via Laravel Breeze
- ✅ Role-based access: **Admin** & **User**
- ✅ CRUD for Tasks (`title`, `description`, `due_date`, `status`)
- ✅ Assign tasks to other users
- ✅ Email notifications on assignment via **Mailpit**
- ✅ Task filtering/search by status, due date, and text
- ✅ Activity log for task changes
- ✅ Admins can manage all tasks; Users can only manage their own
- ✅ RESTful API for all task operations
- ✅ Unit & Feature tests
- ✅ Secure and optimized: CSRF/XSS protection, eager loading, indexing

---

## 🧱 Requirements

- Docker & Docker Compose installed
- Laravel Sail
- Composer

---

## 🧰 Installation

```bash
git clone https://github.com/your-username/task-manager.git
cd task-manager

# Install dependencies
composer install

# Set up Laravel Sail
php artisan sail:install --with=mysql,mailpit

# Start Docker services
./vendor/bin/sail up -d

# Generate env keys and migrate
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

---

## 👤 Test User

Seeder creates an admin and a regular user:

| Role  | Email              | Password  |
|-------|--------------------|-----------|
| Admin | admin@test.com     | password  |
| User  | user1@test.com     | password  |

---

## 🌱 Seeder & Dummy Data

`DatabaseSeeder` calls:

```php
$this->call([
    UserSeeder::class,
    TaskSeeder::class,
]);
```

**UserSeeder** creates:
- 1 Admin (`is_admin = true`)
- 4 Users

**TaskSeeder** creates:
- 20 tasks randomly assigned to users

---

## 🔁 Web Routes (`routes/web.php`)

```php
use App\Http\Controllers\TaskController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/tasks/{task}/assign', [TaskController::class, 'assign'])->name('tasks.assign');
});
```

---

## 📂 File Structure

```
app/
├── Models/Task.php          # Includes scopes, activity logging
├── Policies/TaskPolicy.php  # Handles ownership and admin logic
├── Http/
│   ├── Controllers/
│   │   └── TaskController.php
│   ├── Requests/TaskRequest.php
resources/views/
├── tasks/index.blade.php
├── tasks/create.blade.php
├── tasks/edit.blade.php
├── layouts/app.blade.php
routes/
├── web.php
├── api.php
```

---

## 🧠 Controller Example: TaskController (Web)

```php
public function index()
{
    $tasks = auth()->user()->is_admin
        ? Task::latest()->paginate(10)
        : Task::where('user_id', auth()->id())->latest()->paginate(10);

    return view('tasks.index', compact('tasks'));
}

public function assign(Request $request, Task $task)
{
    $request->validate(['user_id' => 'required|exists:users,id']);

    $task->update(['user_id' => $request->user_id]);

    // send mail via Mailpit
    Mail::to(User::find($request->user_id))->send(new TaskAssigned($task));

    return redirect()->back()->with('success', 'Task reassigned.');
}
```

---

## 🧪 Testing

Run PHPUnit tests:

```bash
./vendor/bin/sail test
```

Tests include:
- Task creation, filtering, and assignment
- API access and permissions
- User registration/login

---

## 📬 Mailpit

View all outgoing emails:

```
http://localhost:8025
```

---

## 📦 API Routes

| Method | Endpoint                   | Description               |
|--------|----------------------------|---------------------------|
| POST   | /api/auth/register         | Register a user           |
| POST   | /api/auth/login            | Login and get token       |
| GET    | /api/tasks                 | List tasks (with filters) |
| POST   | /api/tasks                 | Create task               |
| GET    | /api/tasks/{task}          | Show task                 |
| PUT    | /api/tasks/{task}          | Update task               |
| DELETE | /api/tasks/{task}          | Delete task               |
| POST   | /api/tasks/{task}/assign   | Assign to another user    |

---

## 🔒 Security Best Practices

- ✅ CSRF protection via `@csrf`
- ✅ XSS-safe via Blade’s `{{ }}`
- ✅ SQL injection-safe via query builder
- ✅ Indexes on `user_id`, `status`, `due_date` for performance
- ✅ Eager load relationships to reduce N+1 queries

---

## 🚀 Running Locally

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate --seed
```

---

## 🧾 Deployment Tips

- Set `APP_ENV=production` and `APP_DEBUG=false`
- Use a real SMTP mailer instead of Mailpit
- Configure storage link: `php artisan storage:link`
