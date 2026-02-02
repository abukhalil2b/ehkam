# خطة تنفيذ إعدادات تقرير المؤشرات الاستراتيجية

## الملفات المطلوبة

### 1. قاعدة البيانات
- **ملف:** `database/migrations/xxxx_xx_xx_xxxxxx_create_kpi_report_settings_table.php`
- إنشاء جدول `kpi_report_settings` لتخزين إعدادات التقرير

### 2. المتحكم (StatisticController)
- دالة `reportSettings()` - عرض صفحة الإعدادات
- دالة `saveReportSettings()` - حفظ الإعدادات
- دالة `getReportSettings()` - جلب الإعدادات (API)

### 3. الروت
```php
Route::get('statistic/settings', [StatisticController::class, 'reportSettings'])
    ->middleware('permission:statistic.bsc')
    ->name('statistic.settings');

Route::post('statistic/settings/save', [StatisticController::class, 'saveReportSettings'])
    ->middleware('permission:statistic.bsc')
    ->name('statistic.settings.save');

Route::get('statistic/settings/data', [StatisticController::class, 'getReportSettings'])
    ->middleware('permission:statistic.bsc')
    ->name('statistic.settings.data');
```

### 4. Views
- **ملف:** `resources/views/statistic/settings.blade.php`
  - صفحة إعدادات التقرير
  - خانات اختيار للسنوات (2023، 2024، 2025)
  - خانات اختيار للأرباع (Q1، Q2، Q3، Q4)
  - قائمة اختيار المؤشرات

### 5. تحديث bsc.blade.php
- إضافة رابط "إعدادات التقرير" في شريط التنقل
- تعديل دالة `renderCharts()` لتصفية البيانات حسب الإعدادات
- تعديل بطاقات السنوات لتعرض فقط السنوات المحددة
- إضافة متغيرات JavaScript للإعدادات

## بنية جدول kpi_report_settings

| الحقل | النوع | الوصف |
|-------|-------|-------|
| id | bigint | المفتاح الأساسي |
| user_id | bigint | معرف المستخدم (nullable للإنشاء الجماعي) |
| setting_key | string | مفتاح الإعداد |
| setting_value | text | قيمة الإعداد (JSON) |
| created_at | timestamp | تاريخ الإنشاء |
| updated_at | timestamp | تاريخ التحديث |

## أمثلة على الإعدادات

- `years`: `["2023", "2024", "2025"]`
- `quarters`: `["1", "2", "3", "4"]`
- `indicators`: `["1", "2", "3"]`
