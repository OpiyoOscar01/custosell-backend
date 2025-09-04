# Workspace and Team Management API Documentation

## Overview
The CustoSell backend now includes comprehensive workspace and team management functionality. These controllers provide full CRUD operations along with member management and statistics.

## Workspace Management

### Available Endpoints

#### 1. List Workspaces
- **Endpoint:** `GET /api/workspaces`
- **Description:** Get paginated list of workspaces with filtering and search
- **Query Parameters:**
  - `search` - Search in name, description, industry
  - `industry` - Filter by industry
  - `sort_by` - Sort field (default: created_at)
  - `sort_order` - Sort direction (default: desc)
  - `per_page` - Items per page (default: 15)
- **Response:** Paginated workspace list with owner and users

#### 2. Create Workspace
- **Endpoint:** `POST /api/workspaces`
- **Request Class:** `StoreWorkspaceRequest`
- **Required Fields:** `name`
- **Optional Fields:** `description`, `industry`, `employees_count`, `monthly_budget`, `goals[]`, `features[]`
- **Response:** Created workspace with relationships

#### 3. Get Workspace Details
- **Endpoint:** `GET /api/workspaces/{workspace}`
- **Description:** Get detailed workspace information including teams
- **Response:** Workspace with owner, users, and teams data

#### 4. Update Workspace
- **Endpoint:** `PUT /api/workspaces/{workspace}`
- **Request Class:** `UpdateWorkspaceRequest`
- **Description:** Update workspace information
- **Response:** Updated workspace with relationships

#### 5. Delete Workspace
- **Endpoint:** `DELETE /api/workspaces/{workspace}`
- **Description:** Permanently delete a workspace
- **Response:** Success confirmation

#### 6. Add Member to Workspace
- **Endpoint:** `POST /api/workspaces/{workspace}/members`
- **Required Fields:** `user_id`, `role` (admin|member|viewer)
- **Description:** Add a user to the workspace with specified role
- **Response:** Updated workspace with members

#### 7. Update Member Role
- **Endpoint:** `PUT /api/workspaces/{workspace}/members/{user}`
- **Required Fields:** `role` (admin|member|viewer)
- **Description:** Update a member's role in the workspace
- **Response:** Updated workspace with members

#### 8. Remove Member
- **Endpoint:** `DELETE /api/workspaces/{workspace}/members/{user}`
- **Description:** Remove a member from the workspace (cannot remove owner)
- **Response:** Success confirmation

#### 9. Get Workspace Statistics
- **Endpoint:** `GET /api/workspaces/{workspace}/statistics`
- **Description:** Get comprehensive workspace statistics
- **Response:** Statistics including member count, teams, projects, tasks, budget, role distribution

## Team Management

### Available Endpoints

#### 1. List Teams
- **Endpoint:** `GET /api/teams`
- **Description:** Get paginated list of teams with filtering and search
- **Query Parameters:**
  - `workspace_id` - Filter by workspace
  - `search` - Search in name and description
  - `leader_id` - Filter by team leader
  - `sort_by` - Sort field (default: created_at)
  - `sort_order` - Sort direction (default: desc)
  - `per_page` - Items per page (default: 15)
- **Response:** Paginated team list with workspace, leader, and members

#### 2. Create Team
- **Endpoint:** `POST /api/teams`
- **Request Class:** `StoreTeamRequest`
- **Required Fields:** `name`, `workspace_id`, `leader_id`
- **Optional Fields:** `description`, `departments[]`
- **Response:** Created team with relationships

#### 3. Get Team Details
- **Endpoint:** `GET /api/teams/{team}`
- **Description:** Get detailed team information
- **Response:** Team with workspace, leader, and members data

#### 4. Update Team
- **Endpoint:** `PUT /api/teams/{team}`
- **Request Class:** `UpdateTeamRequest`
- **Description:** Update team information (handles leader changes)
- **Response:** Updated team with relationships

#### 5. Delete Team
- **Endpoint:** `DELETE /api/teams/{team}`
- **Description:** Permanently delete a team
- **Response:** Success confirmation

#### 6. Add Member to Team
- **Endpoint:** `POST /api/teams/{team}/members`
- **Required Fields:** `user_id`, `role` (admin|member)
- **Description:** Add a workspace member to the team
- **Response:** Updated team with members

#### 7. Update Member Role
- **Endpoint:** `PUT /api/teams/{team}/members/{user}`
- **Required Fields:** `role` (admin|member)
- **Description:** Update a member's role in the team
- **Response:** Updated team with members

#### 8. Remove Member
- **Endpoint:** `DELETE /api/teams/{team}/members/{user}`
- **Description:** Remove a member from the team (cannot remove leader)
- **Response:** Success confirmation

#### 9. Transfer Leadership
- **Endpoint:** `POST /api/teams/{team}/transfer-leadership`
- **Required Fields:** `new_leader_id`
- **Description:** Transfer team leadership to another workspace member
- **Response:** Updated team with new leadership structure

#### 10. Get Team Statistics
- **Endpoint:** `GET /api/teams/{team}/statistics`
- **Description:** Get comprehensive team statistics
- **Response:** Statistics including member count, role distribution, departments, projects, tasks

## Data Models

### Workspace Model
```php
- id: Primary key
- name: Workspace name (required)
- description: Workspace description
- owner_id: User who owns the workspace
- industry: Business industry
- employees_count: Number of employees
- monthly_budget: Monthly budget amount
- goals: Array of workspace goals
- features: Array of enabled features
- created_at/updated_at: Timestamps
```

### Team Model
```php
- id: Primary key
- name: Team name (required)
- description: Team description
- workspace_id: Parent workspace (required)
- leader_id: Team leader user (required)
- departments: Array of departments
- created_at/updated_at: Timestamps
```

### Pivot Tables
- **workspace_user:** Links users to workspaces with roles
- **team_user:** Links users to teams with roles

## Authorization & Security

### Permission System
- Uses policy-based authorization (WorkspacePolicy, TeamPolicy)
- Role-based access control integrated with Spatie Permission package
- Workspace-level permissions (admin, member, viewer)
- Team-level permissions (admin, member)

### Access Rules
- **Workspace Owner:** Full control over workspace and all teams
- **Workspace Admin:** Can manage teams and members
- **Workspace Member:** Can view and participate in assigned teams
- **Workspace Viewer:** Read-only access
- **Team Leader:** Full control over team
- **Team Admin:** Can manage team members
- **Team Member:** Can view team information

### Security Features
- Validates user membership in workspace before team operations
- Prevents removal of workspace owners and team leaders
- Ensures proper role transitions during leadership changes
- Filters data based on user's workspace access

## Business Logic

### Workspace Management
1. **Creation:** User becomes owner and admin member automatically
2. **Member Management:** Only admins can add/remove members
3. **Role Changes:** Cannot change owner role without ownership transfer
4. **Deletion:** Only owner can delete workspace

### Team Management
1. **Creation:** Leader becomes team admin automatically
2. **Leadership:** Leaders have admin privileges in team
3. **Workspace Dependency:** Team members must be workspace members
4. **Leadership Transfer:** Automatically handles role transitions
5. **Member Management:** Only team admins can manage members

### Data Relationships
- Workspaces contain multiple teams
- Users can be members of multiple workspaces and teams
- Teams are scoped to their parent workspace
- All team members must be workspace members

## Error Handling
- Comprehensive validation with custom error messages
- Business rule enforcement (workspace membership, leadership constraints)
- Proper HTTP status codes (422 for validation, 403 for authorization)
- Consistent JSON response format with success/error indicators

## Performance Features
- Eager loading of relationships to reduce database queries
- Pagination for large datasets
- Efficient filtering and search functionality
- Scoped queries based on user permissions
