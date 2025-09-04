# Execute these commands in order

# 1. Create Workspace files
php artisan make:model Workspace -m
php artisan make:request CreateWorkspaceRequest
php artisan make:request UpdateWorkspaceRequest
php artisan make:controller Api/WorkspaceController
php artisan make:policy WorkspacePolicy --model=Workspace
php artisan make:resource WorkspaceResource
php artisan make:factory WorkspaceFactory --model=Workspace
php artisan make:test WorkspaceTest

# 2. Create Team files
php artisan make:model Team -m
php artisan make:request CreateTeamRequest
php artisan make:request UpdateTeamRequest
php artisan make:controller Api/TeamController
php artisan make:policy TeamPolicy --model=Team
php artisan make:resource TeamResource
php artisan make:factory TeamFactory --model=Team
php artisan make:test TeamTest

# 3. Create Repository Service Provider
php artisan make:provider RepositoryServiceProvider

# 4. Create Repository structure manually (no artisan command)
mkdir -p app/Repositories/Contracts
mkdir -p app/Services

# 5. Run migrations
php artisan migrate

# 6. Run tests
php artisan test
