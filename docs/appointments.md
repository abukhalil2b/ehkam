# نظام طلبات المواعيد (Appointment Requests System)

## نظرة عامة

نظام طلبات المواعيد هو نظام متكامل لإدارة طلبات المواعيد مع الوزراء والمسؤولين. يتكامل النظام مع محرك سير العمل (Workflow Engine) لتوفير عملية موافقة منظمة، ويتكامل مع نظام التقويم لعرض المواعيد المحجوزة.

---

## للمستخدمين (Stakeholders)

### ما هو نظام طلبات المواعيد؟

نظام طلبات المواعيد يسمح للموظفين بطلب مواعيد مع الوزراء أو المسؤولين. يمر الطلب عبر مراحل متعددة من الموافقة قبل أن يتم جدولته في التقويم.

### كيف يعمل النظام؟

1. **إنشاء الطلب**: يقوم الموظف بإنشاء طلب موعد مع تحديد الوزير والموضوع والأولوية.
2. **مراجعة المدير**: يمر الطلب أولاً على المدير للموافقة أو الرفض.
3. **جدولة السكرتير**: بعد الموافقة، يقوم السكرتير باختيار موعد مناسب من المواعيد المتاحة.
4. **إتمام الحجز**: يتم حجز الموعد في التقويم وإشعار جميع الأطراف.

### المميزات الرئيسية

- ✅ **واجهة سهلة الاستخدام**: إنشاء طلبات المواعيد بخطوات بسيطة
- ✅ **تتبع مرئي**: عرض مرئي لمسار سير العمل مع الأيقونات والوقت المستغرق
- ✅ **تكامل مع التقويم**: المواعيد المحجوزة تظهر تلقائياً في التقويم
- ✅ **إشعارات تلقائية**: إشعارات عند كل مرحلة من مراحل العملية
- ✅ **تتبع الوقت**: عرض الوقت المستغرق في كل مرحلة والوقت المتبقي

### كيفية استخدام النظام

#### إنشاء طلب موعد جديد

1. انتقل إلى **طلبات المواعيد** من القائمة الرئيسية
2. انقر على **إضافة طلب موعد جديد**
3. املأ البيانات:
   - اختر الوزير
   - أدخل الموضوع (مطلوب)
   - أدخل الوصف (اختياري)
   - حدد الأولوية
4. انقر على **إنشاء الطلب**

#### متابعة طلب الموعد

- يمكنك عرض تفاصيل الطلب من صفحة القائمة
- ستجد:
  - حالة الطلب الحالية
  - المرحلة الحالية في سير العمل
  - الوقت المستغرق والوقت المتبقي
  - سجل كامل لجميع الإجراءات

#### الموافقة على الطلبات (للمديرين والسكرتير)

- انتقل إلى صفحة تفاصيل الطلب
- انقر على **الموافقة** في الشريط الجانبي
- يمكنك إضافة تعليقات (اختياري)
- انقر على **تأكيد الموافقة**

#### اختيار الموعد (للسكرتير)

- بعد الموافقة من المدير، ستظهر المواعيد المتاحة
- اختر الموعد المناسب
- سيتم حجز الموعد تلقائياً في التقويم

---

## للمطورين (Technical Documentation)

### البنية المعمارية

```
app/
├── Http/Controllers/
│   └── AppointmentRequestController.php    # Controller الرئيسي
├── Models/
│   ├── AppointmentRequest.php              # نموذج طلب الموعد
│   └── CalendarSlotProposal.php          # نموذج المواعيد المقترحة
├── Services/
│   └── AppointmentService.php             # منطق العمل (Business Logic)
└── resources/views/appointments/
    ├── index.blade.php                     # قائمة الطلبات
    ├── create.blade.php                    # إنشاء طلب جديد
    ├── show.blade.php                      # تفاصيل الطلب
    └── partials/
        ├── actions.blade.php               # أزرار الإجراءات
        └── workflow-visualization.blade.php # عرض مرئي لسير العمل
```

### قاعدة البيانات

#### جدول `appointment_requests`

```sql
- id (PK)
- requester_id (FK -> users)
- minister_id (FK -> users)
- subject (string)
- description (text, nullable)
- priority (enum: low, normal, high, urgent)
- current_stage_id (FK -> workflow_stages, nullable)
- status (enum: draft, in_progress, rejected, booked)
- timestamps
```

#### جدول `calendar_slot_proposals`

```sql
- id (PK)
- appointment_request_id (FK -> appointment_requests)
- start_date (timestamp)
- end_date (timestamp)
- location (string, nullable)
- status (enum: proposed, accepted, rejected)
- created_by (FK -> users)
- selected_by (FK -> users, nullable)
- timestamps
```

### التكامل مع سير العمل

النظام يستخدم محرك سير العمل المدمج. كل طلب موعد يمر عبر المراحل التالية:

1. **مراجعة المدير** (Manager Review)
   - الفريق: فريق المديرين
   - المدة المسموحة: 3 أيام
   - الإجراءات: موافقة، رفض، إعادة

2. **جدولة السكرتير** (Secretary Scheduling)
   - الفريق: فريق السكرتارية
   - المدة المسموحة: 5 أيام
   - الإجراءات: موافقة فقط

### الخدمات (Services)

#### `AppointmentService`

```php
// إنشاء طلب جديد
public function createRequest(array $data, User $user): AppointmentRequest

// الموافقة على الطلب
public function approveRequest(AppointmentRequest $request, User $manager, ?string $comments = null)

// اختيار الموعد
public function selectSlot(AppointmentRequest $request, User $secretary, CalendarSlotProposal $slot)

// الحصول على الطلبات المعلقة للمستخدم
public function pendingForUser(User $user)
```

**ملاحظة**: جميع العمليات محمية بـ `DB::transaction` لضمان سلامة البيانات.

### المسارات (Routes)

```php
// Route Name                    // HTTP Method & URI

// قائمة الطلبات (List)
'appointments.index'            GET  /appointments

// إنشاء طلب جديد (Create)
'appointments.create'           GET  /appointments/create
'appointments.store'            POST /appointments

// عرض التفاصيل (Show)
'appointments.show'             GET  /appointments/{appointmentRequest}

// الموافقة على الطلب (Approve)
'appointments.approve'          POST /appointments/{appointmentRequest}/approve

// إعادة الطلب (Return)
'appointments.return'           POST /appointments/{appointmentRequest}/return

// رفض الطلب (Reject)
'appointments.reject'           POST /appointments/{appointmentRequest}/reject

// اختيار الموعد (Select Slot)
'appointments.select-slot'      POST /appointments/{appointmentRequest}/select-slot
```

> **ملاحظة**: جميع المسارات تتطلب تسجيل الدخول (`auth` middleware).

### التكامل مع التقويم

النظام متكامل مع نظام التقويم (`AnnualCalendarController`). المواعيد المحجوزة تظهر تلقائياً في:

- **تقويم المستخدم**: المواعيد التي يكون المستخدم فيها وزيراً أو طالباً
- **تقويم القسم**: جميع المواعيد لموظفي القسم

المواعيد تظهر بلون أخضر (#10b981) ويمكن تمييزها عن الأحداث العادية.

### العلاقات التنظيمية (Org Units)

النظام يدعم عرض المواعيد على مستوى:

- **المستخدم الفردي**: مواعيد مستخدم محدد
- **القسم (Department)**: جميع مواعيد موظفي القسم
- **الفرع (Division)**: جميع مواعيد موظفي الفرع
- **القسم الفرعي (Section)**: جميع مواعيد موظفي القسم الفرعي

العلاقات تعتمد على جدول `org_units` و `employee_assignments`:

```php
OrgUnit -> hasMany -> EmployeeAssignment -> belongsTo -> User
```

### البذور (Seeders)

لإنشاء بيانات تجريبية:

```bash
php artisan db:seed --class=AppointmentRequestSeeder
```

البذور تنشئ:
- 20 طلب موعد
- سير عمل مع مرحلتين
- فرق عمل (مديرين وسكرتارية)
- مواعيد مقترحة للطلبات قيد المعالجة

### الأمان والصلاحيات

- جميع المسارات محمية بـ `auth` middleware
- التحقق من الصلاحيات يتم في:
  - `AppointmentService::selectSlot()` - يتحقق من أن المستخدم هو السكرتير المعين
  - `WorkflowService::verifyUserCanAct()` - يتحقق من أن المستخدم في الفريق المسؤول

### معالجة الأخطاء

جميع العمليات محمية بـ `try-catch` blocks وتستخدم `DB::transaction`:

```php
try {
    DB::transaction(function () {
        // العملية
    });
    return redirect()->with('success', '...');
} catch (\Exception $e) {
    return back()->with('error', $e->getMessage());
}
```

### التصور المرئي لسير العمل

المكون `workflow-visualization.blade.php` يعرض:

- **الأيقونات**: لكل مرحلة (✓ مكتملة، ⏰ جارية، ○ معلقة)
- **شريط التقدم**: يوضح نسبة الإنجاز
- **الوقت المستغرق**: في كل مرحلة
- **الوقت المتبقي**: قبل انتهاء المدة المسموحة
- **إحصائيات**: إجمالي المراحل، عدد الموافقات، الأيام في النظام

### الاختبار

```bash
# تشغيل البذور
php artisan db:seed --class=AppointmentRequestSeeder

# الوصول إلى القائمة
GET /appointments/index

# إنشاء طلب جديد
GET /appointments/create
POST /appointments/store

# عرض التفاصيل
GET /appointments/show/{id}
```

### التطوير المستقبلي

- [ ] إضافة إشعارات بريد إلكتروني
- [ ] إضافة تذكيرات قبل الموعد
- [ ] إضافة إمكانية إلغاء المواعيد
- [ ] إضافة تقارير إحصائية
- [ ] إضافة تصدير إلى PDF
- [ ] إضافة API endpoints

---

## الوثائق ذات الصلة (Related Documentation)

| الوثيقة | الوصف |
|---------|-------|
| [Developer Guide](developer-guide.md) | دليل المطورين لخدمة AppointmentService |
| [Workflow Architecture](workflow_architecture.md) | البنية الفنية لنظام سير العمل |
| [Roles & Permissions](roles-permissions.md) | إدارة الأدوار والصلاحيات |
| [Documentation Index](README.md) | فهرس الوثائق الكامل |

---

## الدعم والمساعدة

للمساعدة التقنية، يرجى التواصل مع فريق التطوير.

للمساعدة في الاستخدام، يرجى الرجوع إلى دليل المستخدم أو التواصل مع قسم الدعم الفني.

---

**آخر تحديث**: يناير 2026  
**الإصدار**: 1.0.0
