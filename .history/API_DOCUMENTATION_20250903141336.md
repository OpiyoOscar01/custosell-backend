# Workspace and Team API Documentation

This document provides examples of how to use the newly implemented Workspace and Team APIs.

## Authentication

All endpoints require authentication using Laravel Sanctum. First, you need to register/login to get an access token.

### 1. Register a User
```bash
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe", 
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Login
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

Save the `access_token` from the response for subsequent requests.

## Workspace APIs

### 3. Create a Workspace
```bash
curl -X POST http://127.0.0.1:8000/api/workspaces \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "name": "My Awesome Workspace",
    "description": "A workspace for our development team",
    "industry": "Technology",
    "employees_count": 50,
    "monthly_budget": 10000.00,
    "goals": ["Increase productivity", "Improve collaboration"],
    "features": ["Project management", "Time tracking"]
  }'
```

### 4. List Workspaces
```bash
curl -X GET http://127.0.0.1:8000/api/workspaces \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### 5. Get Specific Workspace
```bash
curl -X GET http://127.0.0.1:8000/api/workspaces/{workspace_id} \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### 6. Update Workspace
```bash
curl -X PUT http://127.0.0.1:8000/api/workspaces/{workspace_id} \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "name": "Updated Workspace Name",
    "monthly_budget": 15000.00
  }'
```

### 7. Add Member to Workspace
```bash
curl -X POST http://127.0.0.1:8000/api/workspaces/{workspace_id}/members \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "user_id": 2,
    "role": "admin"
  }'
```

### 8. Remove Member from Workspace
```bash
curl -X DELETE http://127.0.0.1:8000/api/workspaces/{workspace_id}/members \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "user_id": 2
  }'
```

## Team APIs

### 9. Create a Team
```bash
curl -X POST http://127.0.0.1:8000/api/teams \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "name": "Development Team",
    "description": "Our core development team",
    "workspace_id": 1,
    "leader_id": 1,
    "departments": ["Engineering", "Design"]
  }'
```

### 10. List Teams
```bash
curl -X GET http://127.0.0.1:8000/api/teams \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### 11. List Teams by Workspace
```bash
curl -X GET "http://127.0.0.1:8000/api/teams?workspace_id=1" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### 12. Get Specific Team
```bash
curl -X GET http://127.0.0.1:8000/api/teams/{team_id} \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
```

### 13. Update Team
```bash
curl -X PUT http://127.0.0.1:8000/api/teams/{team_id} \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "name": "Updated Team Name",
    "description": "Updated team description"
  }'
```

### 14. Add Member to Team
```bash
curl -X POST http://127.0.0.1:8000/api/teams/{team_id}/members \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "user_id": 2,
    "role": "member"
  }'
```

### 15. Remove Member from Team
```bash
curl -X DELETE http://127.0.0.1:8000/api/teams/{team_id}/members \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -d '{
    "user_id": 2
  }'
```

## Permission System

### Workspace Permissions:
- **Owner**: Can do everything (update, delete, add/remove members)
- **Admin**: Can update workspace and manage members
- **Member**: Can view workspace and teams

### Team Permissions:
- **Team Leader**: Can update team and manage team members
- **Workspace Owner/Admin**: Can manage any team in their workspace
- **Team Member**: Can view team information

## Database Schema

### Workspaces Table:
- `id` (Primary Key)
- `name` (Required)
- `description` (Optional)
- `owner_id` (Foreign Key to users)
- `industry` (Optional)
- `employees_count` (Optional)
- `monthly_budget` (Optional, Decimal)
- `goals` (JSON Array)
- `features` (JSON Array)
- `created_at`, `updated_at`

### Teams Table:
- `id` (Primary Key)
- `name` (Required)
- `description` (Optional)
- `workspace_id` (Foreign Key to workspaces)
- `leader_id` (Foreign Key to users)
- `departments` (JSON Array)
- `created_at`, `updated_at`

### Pivot Tables:
- `workspace_user`: Links users to workspaces with roles
- `team_user`: Links users to teams with roles

## Response Format

All API responses follow this format:
```json
{
  "success": true/false,
  "data": {}, // Response data
  "message": "Descriptive message"
}
```

Error responses (4xx/5xx) include:
```json
{
  "success": false,
  "message": "Error description",
  "error": "Detailed error message" // In development
}
```
