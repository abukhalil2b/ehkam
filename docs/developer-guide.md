# Developer Guide - Service Classes

This guide provides comprehensive documentation for developers working with Ehkam's service layer. Each service is documented with its purpose, methods, dependencies, and usage examples.

---

## Table of Contents

1. [Service Architecture](#service-architecture)
2. [WorkflowService](#workflowservice)
3. [AppointmentService](#appointmentservice)
4. [SidebarService](#sidebarservice)
5. [Creating New Services](#creating-new-services)
6. [Testing Services](#testing-services)
7. [Common Patterns](#common-patterns)

---

## Service Architecture

### Design Principles

Ehkam services follow these principles:

1. **Single Responsibility**: Each service handles one domain (workflows, appointments, etc.)
2. **Dependency Injection**: Services receive dependencies via constructor injection
3. **Transaction Safety**: Database operations use `DB::transaction()` for atomicity
4. **Event-Driven**: Services fire events for notifications and extensibility
5. **Authorization**: Permission checks are performed within service methods

### Service Location

All services are located in `app/Services/`:

```
app/Services/
├── AppointmentService.php   # Appointment request business logic
├── SidebarService.php       # Navigation and search
└── WorkflowService.php      # Core workflow engine
```

### Dependency Graph

```
┌─────────────────────┐
│ AppointmentService  │
└─────────┬───────────┘
          │ depends on
          ▼
┌─────────────────────┐
│  WorkflowService    │
└─────────┬───────────┘
          │ works with
          ▼
┌─────────────────────┐
│ HasWorkflow Models  │
│ (AppointmentRequest,│
│  Step, etc.)        │
└─────────────────────┘
```

---

## WorkflowService

### Overview

The `WorkflowService` is the core engine that manages workflow transitions for any model implementing the `HasWorkflow` interface.

**Location**: `app/Services/WorkflowService.php`

### Methods

#### `assignWorkflow(Model $model, int $workflowId, User $actor, bool $autoSubmit = false): Model`

Assigns a workflow to a model and optionally submits it.

**Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `$model` | `Model` | Model implementing `HasWorkflow` |
| `$workflowId` | `int` | ID of the workflow to assign |
| `$actor` | `User` | User performing the action |
| `$autoSubmit` | `bool` | Auto-submit after assignment (default: false) |

**Returns:** The updated model instance

**Example:**
```php
$service = app(WorkflowService::class);
$step = Step::find(1);

// Assign and auto-submit
$step = $service->assignWorkflow($step, $workflowId = 1, $user, autoSubmit: true);

// Or assign without submitting
$step = $service->assignWorkflow($step, $workflowId = 1, $user);
$step = $service->submitStep($step, $user, 'Ready for review');
```

---

#### `submitStep(Model $model, User $actor, ?string $comments = null): Model`

Submits a model from draft state to the first workflow stage.

**Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `$model` | `Model` | Model in draft status |
| `$actor` | `User` | User submitting |
| `$comments` | `string\|null` | Optional submission comments |

**Throws:**
- `Exception` if model not assigned to workflow
- `Exception` if model is not in draft status
- `Exception` if workflow has no stages

**Events Fired:** `StepSubmitted`

---

#### `approveStep(Model $model, User $actor, ?string $comments = null): Model`

Approves a model and moves it to the next stage (or completes the workflow).

**Authorization:** User must be in the team assigned to the current stage.

**Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `$model` | `Model` | Model to approve |
| `$actor` | `User` | User approving |
| `$comments` | `string\|null` | Optional approval comments |

**Throws:**
- `Exception` if user not authorized
- `Exception` if approval not allowed at current stage

**Events Fired:** `StepApproved`

---

#### `returnStep(Model $model, User $actor, ?int $targetStageId = null, ?string $comments = null, array $stepFeedbacks = []): Model`

Returns a model to a previous stage.

**Parameters:**
| Parameter | Type | Description |
|-----------|------|-------------|
| `$model` | `Model` | Model to return |
| `$actor` | `User` | User returning |
| `$targetStageId` | `int\|null` | Specific stage to return to (null = previous) |
| `$comments` | `string\|null` | Return reason |
| `$stepFeedbacks` | `array` | Feedback for specific steps |

**Events Fired:** `StepReturned`

---

#### `rejectStep(Model $model, User $actor, ?string $comments = null): Model`

Rejects a model and terminates the workflow.

**Note:** Rejection is terminal - the model cannot re-enter the workflow.

**Events Fired:** `StepRejected`

---

#### `getPendingStepsForUser(User $user)`

Retrieves all workflow items pending action from the specified user.

**Returns:** Collection of pending items

---

### Authorization Flow

```
verifyUserCanAct()
    │
    ├─► Check: Is instance in terminal state?
    │   └─► If yes: throw Exception
    │
    ├─► Check: Does item have current stage?
    │   └─► If no: throw Exception
    │
    └─► Check assignment type:
        ├─► 'team': Is user in assigned team?
        ├─► 'user': Is user the assigned user?
        └─► 'role': Does user have assigned role?
```

---

## AppointmentService

### Overview

Handles business logic for appointment requests, built on top of `WorkflowService`.

**Location**: `app/Services/AppointmentService.php`

**Dependencies**: `WorkflowService` (injected via constructor)

### Methods

#### `createRequest(array $data, User $user): AppointmentRequest`

Creates a new appointment request with automatic workflow assignment.

**Parameters:**
```php
$data = [
    'minister_id' => 5,           // Required: Target minister
    'subject' => 'Budget Review', // Required: Meeting subject
    'description' => '...',       // Optional: Details
    'priority' => 'high'          // Required: low|normal|high|urgent
];
```

**Throws:**
- `Exception` if no workflow defined for `AppointmentRequest` model

**Example:**
```php
$service = app(AppointmentService::class);

$request = $service->createRequest([
    'minister_id' => 5,
    'subject' => 'Quarterly Budget Discussion',
    'description' => 'Review Q1 spending and Q2 projections',
    'priority' => 'high'
], auth()->user());

// Request is now in workflow, pending manager approval
```

---

#### `approveRequest(AppointmentRequest $request, User $manager, ?string $comments = null): AppointmentRequest`

Processes manager approval, moving request to secretary stage.

**Example:**
```php
$service->approveRequest($request, $manager, 'Approved - please schedule ASAP');
```

---

#### `selectSlot(AppointmentRequest $request, User $secretary, CalendarSlotProposal $slot): AppointmentRequest`

Secretary selects a time slot for the appointment.

**Authorization:**
- For team assignment: User must be in assigned workflow team
- For user assignment: User must be the assigned secretary

**Example:**
```php
$slot = CalendarSlotProposal::where('appointment_request_id', $request->id)
    ->where('status', 'proposed')
    ->first();

$service->selectSlot($request, $secretary, $slot);
// Appointment is now booked and appears on calendar
```

---

#### `pendingForUser(User $user)`

Returns all pending workflow items for a user.

**Note:** Returns all workflow items, not just appointments. Filter if needed:

```php
$pending = $service->pendingForUser($user);
$appointmentsOnly = $pending->filter(fn($item) => $item instanceof AppointmentRequest);
```

---

## SidebarService

### Overview

Provides navigation structure and search functionality for the application.

**Location**: `app/Services/SidebarService.php`

**Dependencies**: None (standalone utility service)

### Methods

#### `getSidebarSections(): array`

Returns the complete sidebar navigation structure.

**Structure:**
```php
[
    'section_key' => [
        'title' => 'Section Title (Arabic)',
        'links' => [
            [
                'route' => 'route.name',
                'permission' => 'permission.name', // or null for public
                'label' => 'Link Label (Arabic)',
                'params' => [optional, route, params],
                'keywords' => 'english search keywords'
            ]
        ]
    ]
]
```

**Example Usage in Blade:**
```blade
@inject('sidebar', 'App\Services\SidebarService')

@foreach($sidebar->getSidebarSections() as $key => $section)
    <div class="sidebar-section" id="section-{{ $key }}">
        <h3>{{ $section['title'] }}</h3>
        @foreach($section['links'] as $link)
            @if(!$link['permission'] || auth()->user()->can($link['permission']))
                <a href="{{ route($link['route'], $link['params'] ?? []) }}">
                    {{ $link['label'] }}
                </a>
            @endif
        @endforeach
    </div>
@endforeach
```

---

#### `getSearchableLinks(): array`

Returns a flat, permission-filtered list for global search.

**Returns:**
```php
[
    [
        'label' => 'Link Label',
        'category' => 'Section Title',
        'url' => 'https://...',  // Pre-generated URL
        'keywords' => 'search keywords'
    ]
]
```

**Example - Building Search:**
```javascript
// In your search component
const links = @json(app('App\Services\SidebarService')->getSearchableLinks());

function search(query) {
    return links.filter(link => 
        link.label.includes(query) || 
        link.keywords.includes(query.toLowerCase())
    );
}
```

---

## Creating New Services

### Step 1: Create Service Class

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

/**
 * YourService - Brief Description
 * 
 * Detailed description of what this service does.
 * 
 * Key responsibilities:
 * - First responsibility
 * - Second responsibility
 * 
 * Dependencies:
 * - OtherService: Why it's needed
 * 
 * @see \App\Models\YourModel
 */
class YourService
{
    protected OtherService $other;

    public function __construct(OtherService $other)
    {
        $this->other = $other;
    }

    /**
     * Method description.
     * 
     * @param Type $param Description
     * @return Type Description
     * @throws ExceptionType When/why it's thrown
     */
    public function yourMethod(Type $param): ReturnType
    {
        return DB::transaction(function () use ($param) {
            // Your logic here
        });
    }
}
```

### Step 2: Register in Service Provider (if needed)

Most services auto-resolve via Laravel's container. For interfaces or complex bindings:

```php
// app/Providers/AppServiceProvider.php
public function register()
{
    $this->app->bind(YourInterface::class, YourService::class);
}
```

### Step 3: Document in Developer Guide

Add a new section to this file following the same format.

---

## Testing Services

### Unit Test Example

```php
<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\AppointmentService;
use App\Services\WorkflowService;
use App\Models\AppointmentRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentServiceTest extends TestCase
{
    use RefreshDatabase;

    private AppointmentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(AppointmentService::class);
    }

    public function test_create_request_assigns_workflow(): void
    {
        $user = User::factory()->create();
        $minister = User::factory()->create();
        
        // Seed workflow for AppointmentRequest
        $this->artisan('db:seed', ['--class' => 'AppointmentRequestSeeder']);

        $request = $this->service->createRequest([
            'minister_id' => $minister->id,
            'subject' => 'Test Meeting',
            'priority' => 'normal'
        ], $user);

        $this->assertNotNull($request->workflowInstance);
        $this->assertEquals('in_progress', $request->workflowInstance->status);
    }
}
```

---

## Common Patterns

### Transaction Pattern

Always wrap database operations in transactions:

```php
return DB::transaction(function () use ($data) {
    $model = Model::create($data);
    $model->related()->create([...]);
    return $model->fresh();
});
```

### Authorization Pattern

Check permissions before actions:

```php
public function doSomething(Model $model, User $user): void
{
    if (!$user->can('permission.name')) {
        abort(403, 'Unauthorized action.');
    }
    
    // Proceed with action
}
```

### Event Pattern

Fire events for extensibility:

```php
use App\Events\SomethingHappened;

public function doSomething(): void
{
    // Do the thing
    
    event(new SomethingHappened($model, $actor));
}
```

### Logging Pattern

Log errors and important actions:

```php
use Illuminate\Support\Facades\Log;

try {
    // Risky operation
} catch (\Exception $e) {
    Log::error('Operation failed', [
        'model_id' => $model->id,
        'user_id' => $user->id,
        'error' => $e->getMessage()
    ]);
    throw $e;
}
```

---

## Environment Configuration

### Required Environment Variables

```env
# Database
DB_CONNECTION=mysql
DB_DATABASE=ehkam

# Workflow Settings
WORKFLOW_DEFAULT_TIMEOUT_DAYS=7

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

### Optional Configuration

```env
# Calendar Integration
CALENDAR_TIMEZONE=Asia/Riyadh
CALENDAR_DEFAULT_DURATION_MINUTES=30
```

---

**Last Updated**: January 2026  
**Maintainer**: Development Team
