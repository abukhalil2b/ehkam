# Ehkam Documentation Index

Welcome to the Ehkam documentation. This index provides an overview of all available documentation and helps you find the right guide for your needs.

---

## Quick Navigation

| Document | Audience | Language | Description |
|----------|----------|----------|-------------|
| [Workflow Architecture](workflow_architecture.md) | Developers | English | Technical guide for the workflow engine |
| [Step Workflow](step-workflow.md) | Users & Developers | Arabic | سير عمل الخطوات والإجراءات |
| [Appointments System](appointments.md) | Users & Developers | Arabic | Complete guide for appointment requests |
| [Roles & Permissions](roles-permissions.md) | Administrators | Arabic | Managing user roles and permissions |
| [Developer Guide](developer-guide.md) | Developers | English | Service classes and development patterns |

---

## For Users (للمستخدمين)

### Getting Started
- **[Roles & Permissions](roles-permissions.md)** - Understanding your role and what you can do in the system
- **[Appointments](appointments.md)** - How to create and track appointment requests

### Daily Operations
- Workflow pending items: Navigate to **سير العمل** > **الخطوات المعلقة لي**
- Calendar events: Navigate to **التقويم** > **عرض التقويم**

---

## For Developers (للمطورين)

### Architecture Overview

The Ehkam system is built on Laravel and follows a service-oriented architecture:

```
app/
├── Contracts/          # Interfaces (e.g., HasWorkflow)
├── Http/Controllers/   # Request handling
├── Models/             # Eloquent models
├── Services/           # Business logic (this is where the magic happens)
│   ├── AppointmentService.php   # Appointment request management
│   ├── SidebarService.php       # Navigation and search
│   └── WorkflowService.php      # Core workflow engine
├── Events/             # Event classes for workflows
└── Traits/             # Reusable model traits
```

### Core Systems

1. **Workflow Engine** - [workflow_architecture.md](workflow_architecture.md)
   - Polymorphic workflow system
   - Works with any model implementing `HasWorkflow`
   - Team-based authorization
   - Full audit trail

2. **Appointment System** - [appointments.md](appointments.md)
   - Built on top of the workflow engine
   - Calendar integration
   - Multi-step approval process

3. **Service Classes** - [developer-guide.md](developer-guide.md)
   - Detailed PHPDoc documentation
   - Usage examples
   - Best practices

### Service Dependencies

```
AppointmentService
    └── WorkflowService (injected)
            └── HasWorkflow interface (models)

SidebarService (standalone)
    └── Auth facade (for permissions)
```

---

## Documentation Standards

### File Organization

| Path | Purpose |
|------|---------|
| `docs/` | Markdown documentation files |
| `docs/README.md` | This index file |
| Service classes | PHPDoc comments inline |
| Route files | Grouped with comments |

### Language Convention

- **Arabic (العربية)**: User-facing documentation and UI
- **English**: Technical/developer documentation and code comments

### Version Information

- **Current Version**: 1.0.0
- **Last Updated**: January 2026
- **Laravel Version**: 10.x

---

## Contributing to Documentation

When adding new features:

1. **Update relevant docs** - Add new sections or update existing ones
2. **Add PHPDoc comments** - Every public method should have documentation
3. **Include examples** - Show how to use new functionality
4. **Keep it bilingual** - User docs in Arabic, technical docs in English

### Documentation Template

For new service classes:
```php
/**
 * ServiceName - Brief Description
 * 
 * Detailed description of what this service does.
 * 
 * Key responsibilities:
 * - First responsibility
 * - Second responsibility
 * 
 * Dependencies:
 * - List any injected services
 * 
 * Usage example:
 * ```php
 * $service = app(ServiceName::class);
 * $result = $service->doSomething();
 * ```
 */
```

---

## Need Help?

- **Technical Support**: Contact the development team
- **User Support**: Contact the IT helpdesk
- **Bug Reports**: Use the internal ticketing system

---

**Maintained by**: Development Team  
**Last Review**: January 2026
