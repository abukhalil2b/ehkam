# خطة إنشاء جدول السنوات المؤشرات الاستراتيجية

## الهدف
إنشاء جدول جديد `kpi_years` لإدارة السنوات المعروضة في نظام المؤشرات الاستراتيجية بدلاً من الاعتماد على قيم افتراضية أو إعدادات مخزنة يدوياً.

## الملفات المطلوبة

### 1. قاعدة البيانات
- **ملف:** `database/migrations/xxxx_xx_xx_xxxxxx_create_kpi_years_table.php`
- إنشاء جدول `kpi_years` لتخزين السنوات

### بنية الجدول

| الحقل | النوع | الوصف |
|-------|-------|-------|
| id | bigint | المفتاح الأساسي |
| year | integer | السنة (مثل 2023، 2024، 2025) |
| name | string | اسم السنة (مثل "سنة 2023") |
| is_active | boolean | هل السنة نشطة |
| display_order | integer | ترتيب العرض |
| created_at | timestamp | تاريخ الإنشاء |
| updated_at | timestamp | تاريخ التحديث |

### 2. النموذج (Model)
- **ملف:** `app/Models/KpiYear.php`
- دوال للتعامل مع السنوات

### 3. المتحكم (Controller)
- **ملف:** `app/Http/Controllers/KpiYearController.php`
- دوال: index, create, store, edit, update, destroy

### 4. الروت
```php
Route::resource('kpi-years', KpiYearController::class);
```

### 5. Views
- **ملف:** `resources/views/admin/kpi-years/index.blade.php` - قائمة السنوات
- **ملف:** `resources/views/admin/kpi-years/form.blade.php` - نموذج إضافة/تعديل

### 6. تحديث الإعدادات
- تحديث صفحة `settings.blade.php` لجلب السنوات من الجدول الجديد
- تحديث `StatisticController` لاستخدام `KpiYear::active()->ordered()`

## مثال على البيانات
```php
// Seed
KpiYear::create(['year' => 2023, 'name' => 'سنة 2023', 'is_active' => true, 'display_order' => 1]);
KpiYear::create(['year' => 2024, 'name' => 'سنة 2024', 'is_active' => true, 'display_order' => 2]);
KpiYear::create(['year' => 2025, 'name' => 'سنة 2025', 'is_active' => true, 'display_order' => 3]);
```
