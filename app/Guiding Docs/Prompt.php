//Prompt version 1.

I want to scaffold a complete Laravel API structure for an entity named EntityName based on the Laravel migration schema snippet I provide. The generated architecture should support both standard CRUD and custom operations, with a clear separation of concerns across files.

Requirements:
Use the Laravel migration schema snippet I provide to generate the migration file.

Include handling of relationships if the schema contains foreign keys or associations. The model should define relevant Eloquent relationships and support nested resource responses if applicable.

Generate CreateEntityNameRequest and UpdateEntityNameRequest form request classes, with appropriate validation rules clearly separated for create and update operations.

Scaffold the following files with clear responsibilities and communication:

Migration (using the provided schema)

Model (including relationships)

Form Requests for create and update validation

API Controller handling CRUD plus example custom operations (e.g., activate, deactivate, assignUser)

Service class for business logic

Repository + Interface for data access abstraction

Resource class for JSON API responses, supporting nested relations if any

Policy class for authorization logic

Policy registration in AuthServiceProvider

RepositoryServiceProvider for interface binding

API routes including CRUD and custom endpoints

Test stubs (feature/unit tests) for basic coverage of CRUD and custom methods

Please also include:
All necessary Artisan commands to generate the files.

Precise file paths and naming conventions according to Laravel best practices.

Clean, well-defined communication, data flow, and relationships between the components.
Also add necessary comments for understanding the code.

📁 ile Structure
Copy
Edit
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── EntityNameController.php
│   ├── Requests/
│   │   ├── CreateEntityNameRequest.php
│   │   └── UpdateEntityNameRequest.php
│   └── Resources/
│       └── EntityNameResource.php
├── Models/
│   └── EntityName.php
├── Policies/
│   └── EntityNamePolicy.php
├── Repositories/
│   ├── Contracts/
│   │   └── EntityNameRepositoryInterface.php
│   └── EntityNameRepository.php
├── Services/
│   └── EntityNameService.php
├── Providers/
│   └── RepositoryServiceProvider.php

routes/
└── api.php

📜 Artisan Commands
File	Command
Model + Migration	php artisan make:model EntityName -m
Create Request
php artisan make:request CreateEntityNameRequest
Update Request	
php artisan make:request UpdateEntityNameRequest
Controller	
php artisan make:controller Api/EntityNameController
Policy	
php artisan make:policy EntityNamePolicy --model=EntityName
Resource	
php artisan make:resource EntityNameResource
Provider	
php artisan make:provider RepositoryServiceProvider

These are the migration files:

  Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('domain')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['slug', 'is_active']);
        });

        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('color')->default('#3B82F6');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['workspace_id', 'slug']);
            $table->index(['workspace_id', 'is_active']);
        });

        Schema::create('team_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['owner', 'admin', 'member', 'viewer'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['team_id', 'user_id']);
            $table->index(['user_id', 'role']);
        });

        Schema::create('workspace_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['owner', 'admin', 'member', 'viewer'])->default('member');
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['workspace_id', 'user_id']);
            $table->index(['user_id', 'role']);
        });



Prompt version 2.

I want laravel code, not html and start by giving me the laravel commands & non laravel(shell commands) and i can execute in one go to create all the files.Lest's stick to production and enterprise grade practices,I want to scaffold a complete Laravel API structure for an entity named EntityName based on the Laravel migration schema snippet I will provide. The generated architecture should support both standard CRUD operations and custom actions, and follow a clean, modular, and maintainable design pattern.

✅ Core Requirements
Migration

Use the exact Laravel migration schema snippet I provide.

Respect any foreign keys or constraints.

Add necessary indexes if indicated.

Model

Generate EntityName.php inside app/Models/.

Define any Eloquent relationships (e.g., hasMany, belongsTo) based on the schema or foreign keys.

Include necessary $fillable or $guarded fields.

Form Requests

Create CreateEntityNameRequest.php and UpdateEntityNameRequest.php in app/Http/Requests/.

Define appropriate validation rules.

Differentiate required fields between creation and update operations.

API Controller

Create EntityNameController.php inside app/Http/Controllers/Api/.

Handle:

✅ CRUD methods: index, store, show, update, destroy

✅ Custom methods-this depend on the entity (examples):

activate()

deactivate()

assignUser(Request $request, EntityName $entity)

Service Class

Place in app/Services/EntityNameService.php.

Handles all business logic.

Decouples controller from data access.

Repository Pattern

Interface: app/Repositories/Contracts/EntityNameRepositoryInterface.php

Implementation: app/Repositories/EntityNameRepository.php

Bindings should be registered in:

app/Providers/RepositoryServiceProvider.php

Resource Class

Create EntityNameResource.php in app/Http/Resources/.

Return consistent, clean JSON API responses.

Support nested relations if applicable.

Authorization Policy

Policy file: app/Policies/EntityNamePolicy.php

Register the policy in AuthServiceProvider.

Also re-create the migration files.

API Routes

Define routes in routes/api.php.

Include both RESTful and custom endpoints:
for example(This depends on the entities available)
Route::apiResource('entities', EntityNameController::class);
Route::prefix('entities')->controller(EntityNameController::class)->group(function () {
    Route::apiResource('/', EntityNameController::class);
    Route::patch('{entity}/activate', 'activate');
    Route::patch('{entity}/deactivate', 'deactivate');
    Route::post('{entity}/assign-user', 'assignUser');
});
✅ Automated Testing (Feature & Unit)
Generate the following test coverage using Laravel’s testing tools:

Test File

Path: tests/Feature/EntityNameTest.php

Include:

✅ test_user_can_list_entities

✅ test_user_can_create_entity

✅ test_user_can_view_entity

✅ test_user_can_update_entity

✅ test_user_can_delete_entity

✅ test_custom_actions_like_activate_or_assign_user

Factory

File: database/factories/EntityNameFactory.php

Use to create fake instances for testing.

Authentication

Use actingAs() and RefreshDatabase in tests.

Validation & Auth Checks

Include negative test cases for:

Invalid inputs

Unauthorized actions (test policies)

Run Tests

php artisan test
📜 Required Artisan Commands
Purpose	Command
Model + Migration	
php artisan make:model EntityName -m
Create Request	
php artisan make:request CreateEntityNameRequest
Update Request	
php artisan make:request UpdateEntityNameRequest
Controller	
php artisan 
make:controller 
Api/EntityNameController
Policy	
php artisan make:policy EntityNamePolicy --model=EntityName
Resource	
php artisan 
make:resource EntityNameResource
Provider	
php artisan 
make:provider 
RepositoryServiceProvider
Factory	
php artisan make:factory EntityNameFactory --model=EntityName
Test Class	php artisan make:test EntityNameTest

📁 Final File Structure

app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── EntityNameController.php
│   ├── Requests/
│   │   ├── CreateEntityNameRequest.php
│   │   └── UpdateEntityNameRequest.php
│   └── Resources/
│       └── EntityNameResource.php
├── Models/
│   └── EntityName.php
├── Policies/
│   └── EntityNamePolicy.php
├── Repositories/
│   ├── Contracts/
│   │   └── EntityNameRepositoryInterface.php
│   └── EntityNameRepository.php
├── Services/
│   └── EntityNameService.php
├── Providers/
│   └── RepositoryServiceProvider.php

database/
├── factories/
│   └── EntityNameFactory.php

routes/
└── api.php

tests/
└── Feature/
    └── EntityNameTest.php
🧠 Notes
Maintain separation of concerns between the different components.

Ensure single responsibility per class.

Add PHPDoc comments and annotations for method/documentation clarity and also add necessary comments in parts of the code for understanding.

Ensure API Resource formatting aligns with frontend expectations.
Let's follow best and modern laravel practices.
These are my migration files:

---Place here some two migration files.
      
