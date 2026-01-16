<?php

namespace App\Services;

class SidebarService
{
    public function getSidebarSections()
    {
        return [
            'workflow' => [
                'title' => 'سير العمل',
                'links' => [
                    ['route' => 'workflow.pending', 'permission' => 'workflow.pending', 'label' => 'الخطوات المعلقة لي', 'keywords' => 'workflow pending steps my tasks'],
                    ['route' => 'admin.workflow.teams.index', 'permission' => 'workflow_team.index', 'label' => 'إدارة الفرق', 'keywords' => 'workflow teams management'],
                    ['route' => 'admin.workflow.definitions.index', 'permission' => 'workflow_definition.index', 'label' => 'تعريفات سير العمل', 'keywords' => 'workflow definitions stages'],
                ]
            ],
            'indicator' => [
                'title' => 'المؤشرات',
                'links' => [
                    ['route' => 'indicator.index', 'permission' => 'indicator.index', 'label' => 'قائمة المؤشرات', 'keywords' => 'indicators list kpi'],
                ]
            ],
            'activity' => [
                'title' => 'الأنشطة',
                'links' => [
                    ['route' => 'activity.index', 'permission' => 'activity.index', 'label' => 'قائمة الأنشطة', 'params' => [date('Y')], 'keywords' => 'activities list'],
                    ['route' => 'assessment_questions.index', 'permission' => 'assessment_questions.index', 'label' => 'قائمة الأسئلة', 'keywords' => 'assessment questions list'],
                    ['route' => 'assessment_questions.create', 'permission' => 'assessment_questions.create', 'label' => 'إضافة سؤال', 'keywords' => 'add assessment question create'],
                    ['route' => 'project_assessment_report', 'permission' => 'project_assessment_report', 'label' => 'تقرير أداء', 'keywords' => 'performance report project assessment'],
                ]
            ],
            'aim' => [
                'title' => 'الأهداف',
                'links' => [
                    ['route' => 'admin.aim.index', 'permission' => 'admin.aim.index', 'label' => 'أهداف القطاعات', 'keywords' => 'sector aims goals'],
                    ['route' => 'admin.aim_sector_feedback.index', 'permission' => 'admin.aim_sector_feedback.index', 'label' => 'المحقق للأهداف', 'params' => [date('Y')], 'keywords' => 'aim achievement feedback sector'],
                ]
            ],
            'swot' => [
                'title' => 'swot',
                'links' => [
                    ['route' => 'swot.index', 'permission' => 'swot.index', 'label' => 'swot', 'keywords' => 'swot analysis'],
                ]
            ],
            'calendar' => [
                'title' => 'calendar',
                'links' => [
                    ['route' => 'calendar.index', 'permission' => 'calendar.index', 'label' => 'index', 'keywords' => 'calendar index schedule'],
                    ['route' => 'calendar.create', 'permission' => 'calendar.create', 'label' => 'create', 'keywords' => 'calendar create event add'],
                    ['route' => 'timeline.index', 'permission' => 'timeline.index', 'label' => 'timeline', 'keywords' => 'timeline view'],
                ]
            ],
            'mission' => [
                'title' => 'المهام',
                'links' => [
                    ['route' => 'mission.index', 'permission' => 'mission.index', 'label' => 'قائمة المهام', 'keywords' => 'missions list tasks'],
                ]
            ],
            'meeting_minute' => [
                'title' => 'محضر الاجتماعات',
                'links' => [
                    ['route' => 'meeting_minute.index', 'permission' => 'meeting_minute.index', 'label' => 'قائمة المحاضر', 'keywords' => 'meeting minutes list'],
                ]
            ],
            'workshop' => [
                'title' => 'الورش',
                'links' => [
                    ['route' => 'workshop.index', 'permission' => 'workshop.index', 'label' => 'قائمة الورش', 'keywords' => 'workshops list'],
                ]
            ],
            'questionnaire' => [
                'title' => 'إستبانات',
                'links' => [
                    ['route' => 'questionnaire.index', 'permission' => 'questionnaire.index', 'label' => 'قائمة الإستبانات', 'keywords' => 'questionnaires list surveys'],
                    ['route' => 'questionnaire.create', 'permission' => 'questionnaire.create', 'label' => 'أكتب إستبانة', 'keywords' => 'create questionnaire survey add'],
                ]
            ],
            'competitions' => [
                'title' => 'competitions',
                'links' => [
                    ['route' => 'admin.competitions.index', 'permission' => 'admin.competitions.index', 'label' => 'المسابقات', 'keywords' => 'competitions list'],
                ]
            ],
            'finance' => [
                'title' => 'استمارات المالية',
                'links' => [
                    ['route' => 'admin.finance_form.index', 'permission' => 'admin.finance_form.index', 'label' => 'قائمة الاستمارات المالية', 'keywords' => 'finance forms list'],
                    ['route' => 'admin.finance_need.index', 'permission' => 'admin.finance_need.index', 'label' => 'قائمة الاحتياجات', 'keywords' => 'finance needs list'],
                ]
            ],
            'statistics' => [
                'title' => 'إحصائيات',
                'links' => [
                    ['route' => 'statistic.index', 'permission' => 'statistic.index', 'label' => 'كل القطاعات', 'keywords' => 'statistics all sectors'],
                    ['route' => 'statistic.quran', 'permission' => 'statistic.index', 'label' => 'القرآن الكريم', 'params' => [1], 'keywords' => 'statistics quran'],
                    ['route' => 'statistic.zakah', 'permission' => 'statistic.index', 'label' => 'الزكاة', 'params' => [1], 'keywords' => 'statistics zakah'],
                ]
            ],
            'qr' => [
                'title' => 'الكيوآر',
                'links' => [
                    ['route' => 'qr.index', 'permission' => 'qr.index', 'label' => 'قائمة الكيوآر', 'keywords' => 'qr code list'],
                ]
            ],
            'settings' => [
                'title' => 'الإعدادات',
                'links' => [
                    ['route' => 'admin_setting.indicator.index', 'permission' => 'indicator.index', 'label' => ' المؤشرات', 'params' => [date('Y')], 'keywords' => 'settings indicators'],
                    ['route' => 'admin_setting.project.index', 'permission' => 'project.index', 'label' => ' المشاريع', 'params' => [date('Y')], 'keywords' => 'settings projects'],
                ]
            ],
            'admin_structure' => [
                'title' => 'الهيكل',
                'links' => [
                    ['route' => 'org_unit.index', 'permission' => 'org_unit.index', 'label' => 'عرض هيكل الوحدة', 'keywords' => 'org unit structure view'],
                    ['route' => 'org_unit.create', 'permission' => 'org_unit.store', 'label' => 'اضافة هيكل الوحدة', 'keywords' => 'org unit structure create add'],
                    ['route' => 'positions.index', 'permission' => 'admin_position.index', 'label' => 'إدارة الهيكل الوظيفي', 'keywords' => 'positions management structure'],
                    ['route' => 'positions.create', 'permission' => 'admin_position.create', 'label' => 'إضافة منصب جديد', 'keywords' => 'create position add new'],
                    ['route' => 'admin_users.create', 'permission' => 'admin_users.create', 'label' => 'موظف جديد', 'keywords' => 'new user employee create'],
                    ['route' => 'admin_users.index', 'permission' => 'admin_users.index', 'label' => 'الموظفين', 'keywords' => 'users employees list'],
                    ['route' => 'admin_users.create_for_sector', 'permission' => 'admin_users.create', 'label' => 'إنشاء موظف لحصر الاسهامات', 'keywords' => 'create user sector contribution'],
                ]
            ],
            'permission' => [
                'title' => 'الصلاحيات والأدوار',
                'links' => [
                    ['route' => 'admin.roles.index', 'permission' => 'permission.index', 'label' => 'إدارة الأدوار', 'keywords' => 'roles management rbac'],
                    ['route' => 'permission.index', 'permission' => 'permission.index', 'label' => 'كل الصلاحيات', 'keywords' => 'permissions all list'],
                ]
            ],
        ];
    }

    /**
     * Returns a flat list of all searchable links available to the current user.
     */
    public function getSearchableLinks()
    {
        $sections = $this->getSidebarSections();
        $userPermissions = auth()->check() ? auth()->user()->getPermissions() : [];
        $links = [];
        $isAdmin = auth()->check() && auth()->id() == 1;

        // Add Profile Link Manually as it's not in sidebar but requested (example: "profile")
        $links[] = [
            'label' => 'الملف الشخصي',
            'category' => 'المستخدم',
            'url' => route('profile.edit'),
            'keywords' => 'profile user account',
        ];

        foreach ($sections as $section) {
            foreach ($section['links'] as $link) {
                // Check Permission
                if (!$link['permission'] || in_array($link['permission'], $userPermissions) || $isAdmin) {
                    $links[] = [
                        'label' => $link['label'],
                        'category' => $section['title'],
                        'url' => isset($link['params']) ? route($link['route'], $link['params']) : route($link['route']),
                        'keywords' => $link['keywords'] ?? '',
                    ];
                }
            }
        }

        return $links;
    }
}
