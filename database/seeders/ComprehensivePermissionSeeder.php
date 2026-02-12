<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class ComprehensivePermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // RBAC / Roles
            ['slug' => 'roles.index', 'title' => 'عرض الأدوار', 'category' => 'permission'],
            ['slug' => 'roles.create', 'title' => 'إنشاء الأدوار', 'category' => 'permission'],
            ['slug' => 'roles.edit', 'title' => 'تعديل الأدوار', 'category' => 'permission'],
            ['slug' => 'roles.delete', 'title' => 'حذف الأدوار', 'category' => 'permission'],
            ['slug' => 'roles.assign', 'title' => 'تعيين الأدوار للمستخدمين', 'category' => 'permission'],

            // Workflows
            ['slug' => 'workflow.pending', 'title' => 'عرض الخطوات المعلقة', 'category' => 'workflow'],
            ['slug' => 'workflow.approve', 'title' => 'الموافقة على الخطوات', 'category' => 'workflow'],
            ['slug' => 'workflow.return', 'title' => 'إرجاع الخطوات', 'category' => 'workflow'],
            ['slug' => 'workflow.reject', 'title' => 'رفض الخطوات', 'category' => 'workflow'],
            ['slug' => 'workflow.history', 'title' => 'عرض سجل سير العمل', 'category' => 'workflow'],

            ['slug' => 'workflow_team.index', 'title' => 'عرض فرق سير العمل', 'category' => 'workflow'],
            ['slug' => 'workflow_team.create', 'title' => 'إنشاء فريق سير عمل', 'category' => 'workflow'],
            ['slug' => 'workflow_team.edit', 'title' => 'تعديل فريق سير عمل', 'category' => 'workflow'],
            ['slug' => 'workflow_team.delete', 'title' => 'حذف فريق سير عمل', 'category' => 'workflow'],

            ['slug' => 'workflow_definition.index', 'title' => 'عرض تعريفات سير العمل', 'category' => 'workflow'],
            ['slug' => 'workflow_definition.create', 'title' => 'إنشاء تعريف سير عمل', 'category' => 'workflow'],
            ['slug' => 'workflow_definition.edit', 'title' => 'تعديل تعريف سير عمل', 'category' => 'workflow'],
            ['slug' => 'workflow_definition.delete', 'title' => 'حذف تعريف سير عمل', 'category' => 'workflow'],

            ['slug' => 'admin.workflow.index', 'title' => 'لوحة تحكم سير العمل', 'category' => 'workflow'],

            // Indicator Workflows
            ['slug' => 'indicator.report', 'title' => 'رفع تقرير إنجاز المؤشر', 'category' => 'indicator'],
            ['slug' => 'indicator.verify', 'title' => 'التحقق من إنجاز المؤشر', 'category' => 'indicator'],
            ['slug' => 'indicator.approve', 'title' => 'اعتماد تقرير المؤشر', 'category' => 'indicator'],

            // Indicators
            ['slug' => 'indicator.index', 'title' => 'عرض قائمة المؤشرات', 'category' => 'indicator'],
            ['slug' => 'indicator.create', 'title' => 'إضافة مؤشر جديد', 'category' => 'indicator'],
            ['slug' => 'indicator.edit', 'title' => 'تعديل المؤشرات', 'category' => 'indicator'],
            ['slug' => 'indicator.delete', 'title' => 'حذف المؤشرات', 'category' => 'indicator'],

            // Activities
            ['slug' => 'assessment_stages.index', 'title' => 'عرض قائمة مراحل التقييم', 'category' => 'activity'],
            ['slug' => 'activity.create', 'title' => 'إضافة نشاط جديد', 'category' => 'activity'],
            ['slug' => 'assessment_questions.index', 'title' => 'عرض قائمة الأسئلة', 'category' => 'activity'],
            ['slug' => 'assessment_questions.edit', 'title' => 'تعديل أسئلة التقييم', 'category' => 'activity'],
            ['slug' => 'project_assessment_report', 'title' => 'عرض تقرير الأداء', 'category' => 'activity'],
            ['slug' => 'assessment_result.create', 'title' => 'إضافة نتائج التقييم', 'category' => 'activity'],
            ['slug' => 'assessment_result.edit', 'title' => 'تعديل نتائج التقييم', 'category' => 'activity'],

            // Aims
            ['slug' => 'admin.aim.index', 'title' => 'عرض أهداف القطاعات', 'category' => 'aim'],
            ['slug' => 'admin.aim.store', 'title' => 'إضافة هدف قطاع', 'category' => 'aim'],
            ['slug' => 'admin.aim.edit', 'title' => 'تعديل هدف قطاع', 'category' => 'aim'],
            ['slug' => 'admin.aim.destroy', 'title' => 'حذف هدف قطاع', 'category' => 'aim'],
            ['slug' => 'admin.aim_sector_feedback.index', 'title' => 'عرض المحقق للأهداف', 'category' => 'aim'],

            // SWOT
            ['slug' => 'swot.index', 'title' => 'عرض تحليل SWOT', 'category' => 'swot'],
            ['slug' => 'swot.create', 'title' => 'إضافة تحليل SWOT', 'category' => 'swot'],

            // Calendar
            ['slug' => 'calendar.index', 'title' => 'عرض التقويم', 'category' => 'calendar'],
            ['slug' => 'calendar.create', 'title' => 'إضافة حدث للتقويم', 'category' => 'calendar'],
            ['slug' => 'timeline.index', 'title' => 'عرض الجدول الزمني', 'category' => 'calendar'],
            ['slug' => 'timeline.show', 'title' => 'تفاصيل الجدول الزمني', 'category' => 'calendar'],

            // Missions
            ['slug' => 'mission.index', 'title' => 'عرض قائمة المهام', 'category' => 'mission'],
            ['slug' => 'mission.store', 'title' => 'إضافة مهمة', 'category' => 'mission'],

            // Meeting Minutes
            ['slug' => 'meeting_minute.index', 'title' => 'عرض محاضر الاجتماعات', 'category' => 'meeting_minute'],
            ['slug' => 'meeting_minute.create', 'title' => 'إضافة محضر اجتماع', 'category' => 'meeting_minute'],
            ['slug' => 'meeting_minute.show', 'title' => 'عرض تفاصيل المحضر', 'category' => 'meeting_minute'],
            ['slug' => 'meeting_minute.edit', 'title' => 'تعديل محضر اجتماع', 'category' => 'meeting_minute'],
            ['slug' => 'meeting_minute.delete', 'title' => 'حذف محضر اجتماع', 'category' => 'meeting_minute'],

            // Workshops
            ['slug' => 'workshop.index', 'title' => 'عرض قائمة الورش', 'category' => 'workshop'],
            ['slug' => 'workshop.create', 'title' => 'إضافة ورشة عمل', 'category' => 'workshop'],
            ['slug' => 'workshop.show', 'title' => 'عرض تفاصيل الورشة', 'category' => 'workshop'],
            ['slug' => 'workshop.edit', 'title' => 'تعديل ورشة عمل', 'category' => 'workshop'],
            ['slug' => 'workshop.delete', 'title' => 'حذف ورشة عمل', 'category' => 'workshop'],

            // Questionnaires
            ['slug' => 'questionnaire.index', 'title' => 'عرض قائمة الاستبانات', 'category' => 'questionnaire'],
            ['slug' => 'questionnaire.create', 'title' => 'إضافة استبانة جديدة', 'category' => 'questionnaire'],
            ['slug' => 'questionnaire.edit', 'title' => 'تعديل استبانة', 'category' => 'questionnaire'],
            ['slug' => 'questionnaire.delete', 'title' => 'حذف استبانة', 'category' => 'questionnaire'],
            ['slug' => 'questionnaire.answer_index', 'title' => 'عرض إجابات الاستبانة', 'category' => 'questionnaire'],
            ['slug' => 'questionnaire.answer_edit', 'title' => 'تعديل إجابات الاستبانة', 'category' => 'questionnaire'],
            ['slug' => 'questionnaire.export', 'title' => 'تصدير نتائج الاستبانة', 'category' => 'questionnaire'],

            // Competitions
            ['slug' => 'admin.competitions.index', 'title' => 'عرض المسابقات', 'category' => 'competition'],

            // Finance
            ['slug' => 'admin.finance_form.index', 'title' => 'عرض الاستمارات المالية', 'category' => 'finance'],
            ['slug' => 'admin.finance_need.index', 'title' => 'عرض قائمة الاحتياجات', 'category' => 'finance'],

            // Statistics
            ['slug' => 'statistic.index', 'title' => 'عرض الإحصائيات', 'category' => 'statistic'],

            // QR
            ['slug' => 'qr.index', 'title' => 'عرض قائمة QR', 'category' => 'qr'],
            ['slug' => 'qr.create', 'title' => 'إنشاء QR code', 'category' => 'qr'],
            ['slug' => 'qr.show', 'title' => 'عرض تفاصيل QR', 'category' => 'qr'],
            ['slug' => 'qr.delete', 'title' => 'حذف QR code', 'category' => 'qr'],

            // Settings (Admin Settings)
            ['slug' => 'project.index', 'title' => 'إعدادات المشاريع', 'category' => 'setting'],
            ['slug' => 'project.create', 'title' => 'إضافة مشروع', 'category' => 'setting'],
            ['slug' => 'project.edit', 'title' => 'تعديل مشروع', 'category' => 'setting'],

            // Structure (Admin Structure)
            ['slug' => 'org_unit.index', 'title' => 'عرض الهيكل التنظيمي', 'category' => 'structure'],
            ['slug' => 'org_unit.store', 'title' => 'إضافة وحدة تنظيمية', 'category' => 'structure'], // Used in create route
            ['slug' => 'org_unit.create', 'title' => 'إنشاء وحدة تنظيمية', 'category' => 'structure'],
            ['slug' => 'org_unit.edit', 'title' => 'تعديل وحدة تنظيمية', 'category' => 'structure'],

            ['slug' => 'admin_position.index', 'title' => 'عرض الهيكل الوظيفي', 'category' => 'structure'],
            ['slug' => 'admin_position.create', 'title' => 'إضافة منصب وظيفي', 'category' => 'structure'],
            ['slug' => 'admin_position.store', 'title' => 'حفظ منصب وظيفي', 'category' => 'structure'],

            ['slug' => 'admin_users.index', 'title' => 'عرض قائمة الموظفين', 'category' => 'structure'],
            ['slug' => 'admin_users.create', 'title' => 'إضافة موظف جديد', 'category' => 'structure'],
            ['slug' => 'admin_users.assign', 'title' => 'تعيين صلاحيات/مناصب للموظف', 'category' => 'structure'],
            ['slug' => 'admin_structure.index', 'title' => 'إدارة الهيكل (عام)', 'category' => 'structure'],

            // General Permissions
            ['slug' => 'permission.index', 'title' => 'عرض الصلاحيات', 'category' => 'permission'],
            ['slug' => 'task.index', 'title' => 'عرض المهام العامة', 'category' => 'task'],
        ];

        // Create Permissions
        foreach ($permissions as $perm) {
            Permission::updateOrCreate(
                ['slug' => $perm['slug']],
                [
                    'title' => $perm['title'],
                    'category' => $perm['category'],
                    'description' => $perm['title'] // Use title as description for now
                ]
            );
        }

        // Assign all to Admin Role (slug: admin)
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['title' => 'Admin', 'description' => 'Administrator with full access']
        );

        $allPermissionIds = Permission::pluck('id');
        $adminRole->permissions()->syncWithoutDetaching($allPermissionIds);
    }
}
