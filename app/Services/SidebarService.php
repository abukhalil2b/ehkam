<?php

namespace App\Services;

/**
 * SidebarService - Navigation Structure and Search Functionality
 * 
 * This service provides the navigation structure for the application sidebar
 * and enables the global search functionality by returning searchable links.
 * 
 * Key responsibilities:
 * - Defining the sidebar navigation structure with sections and links
 * - Managing permission-based link visibility
 * - Providing searchable links with keywords for quick navigation
 * 
 * Structure:
 * The sidebar is organized into sections (e.g., 'workflow', 'calendar'), each containing:
 * - title: Display name for the section (Arabic)
 * - links: Array of navigation links with:
 *   - route: Laravel route name
 *   - permission: Required permission to view (null = public)
 *   - label: Display text (Arabic)
 *   - params: Optional route parameters
 *   - keywords: Search keywords (English) for quick navigation
 * 
 * Usage in Blade templates:
 * ```php
 * @inject('sidebar', 'App\Services\SidebarService')
 * @foreach($sidebar->getSidebarSections() as $key => $section)
 *     <h3>{{ $section['title'] }}</h3>
 *     @foreach($section['links'] as $link)
 *         @can($link['permission'] ?? 'always')
 *             <a href="{{ route($link['route'], $link['params'] ?? []) }}">
 *                 {{ $link['label'] }}
 *             </a>
 *         @endcan
 *     @endforeach
 * @endforeach
 * ```
 * 
 * For global search:
 * ```php
 * $searchableLinks = app(SidebarService::class)->getSearchableLinks();
 * // Returns flat array of links with URLs, labels, categories, and keywords
 * ```
 * 
 * @see resources/views/layouts/sidebar.blade.php
 */
class SidebarService
{
    /**
     * Get the complete sidebar navigation structure.
     * 
     * Returns an associative array of sections, each containing a title
     * and array of links. Links include route names, required permissions,
     * display labels, optional parameters, and search keywords.
     * 
     * Section keys are used as identifiers and for CSS/JS targeting:
     * - workflow: Workflow management
     * - indicator: KPI indicators
     * - activity: Activities and assessments
     * - aim: Sector aims and goals
     * - swot: SWOT analysis
     * - calendar: Calendar and appointments
     * - mission: Tasks and missions
     * - meeting_minute: Meeting minutes
     * - workshop: Workshop management
     * - questionnaire: Surveys and questionnaires
     * - competitions: Competition management
     * - finance: Financial forms and needs
     * - statistics: Statistical reports
     * - qr: QR code management
     * - settings: System settings
     * - admin_structure: Organizational structure
     * - permission: Roles and permissions
     * - documentation: Technical documentation
     * 
     * @return array<string, array{title: string, links: array}> Sidebar sections
     */
    public function getSidebarSections(): array
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
            'calendar' => [
                'title' => 'التقويم',
                'links' => [
                    ['route' => 'appointments.index', 'permission' => 'appointments.index', 'label' => 'عرض المواعيد', 'keywords' => 'appointments index schedule'],
                    ['route' => 'calendar.index', 'permission' => 'calendar.index', 'label' => 'عرض التقويم', 'keywords' => 'calendar index schedule'],
                    ['route' => 'calendar.create', 'permission' => 'calendar.create', 'label' => 'إضافة حدث', 'keywords' => 'calendar create event add'],
                    ['route' => 'timeline.index', 'permission' => 'timeline.index', 'label' => 'الجدول الزمني', 'keywords' => 'timeline view'],
                    ['route' => 'calendar.permissions.index', 'permission' => 'calendar.permissions.index', 'label' => 'صلاحيات الوحدات الادارية', 'keywords' => 'calendar permissions view'],
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
            'swot' => [
                'title' => 'التحليل الرباعي',
                'links' => [
                    ['route' => 'swot.index', 'permission' => 'swot.index', 'label' => 'تحليل سوات', 'keywords' => 'swot analysis'],
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
                'title' => 'المسابقات',
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
                    ['route' => 'statistic.bsc', 'permission' => 'statistic.bsc', 'label' => 'المؤشرات الاستراتيجية', 'params' => [1], 'keywords' => 'statistics bsc'],
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
                    ['route' => 'org_unit.index', 'permission' => 'org_unit.index', 'label' => 'عرض الهيكل الكامل', 'keywords' => 'org unit structure view all'],
                    ['route' => 'org_unit.directorates', 'permission' => 'org_unit.index', 'label' => 'إدارة المديريات', 'keywords' => 'directorates structure management'],
                    ['route' => 'org_unit.departments', 'permission' => 'org_unit.index', 'label' => 'إدارة الدوائر', 'keywords' => 'departments structure management'],
                    ['route' => 'org_unit.sections', 'permission' => 'org_unit.index', 'label' => 'إدارة الأقسام', 'keywords' => 'sections structure management'],
                    ['route' => 'org_unit.create', 'permission' => 'org_unit.store', 'label' => 'إضافة وحدة جديدة', 'keywords' => 'org unit structure create add'],
                    ['route' => 'positions.index', 'permission' => 'admin_position.index', 'label' => 'إدارة الوظائف', 'keywords' => 'positions management structure'],
                    ['route' => 'positions.create', 'permission' => 'admin_position.create', 'label' => 'إضافة وظيفة جديدة', 'keywords' => 'create position add new'],
                    ['route' => 'admin_users.index', 'permission' => 'admin_users.index', 'label' => 'الموظفين', 'keywords' => 'users employees list'],
                    ['route' => 'admin_users.create', 'permission' => 'admin_users.create', 'label' => 'موظف جديد', 'keywords' => 'new user employee create'],
                ]
            ],
            'permission' => [
                'title' => 'الصلاحيات والأدوار',
                'links' => [
                    ['route' => 'admin.roles.index', 'permission' => 'permission.index', 'label' => 'إدارة الأدوار', 'keywords' => 'roles management rbac'],
                    ['route' => 'permission.index', 'permission' => 'permission.index', 'label' => 'كل الصلاحيات', 'keywords' => 'permissions all list'],
                ]
            ],
            'documentation' => [
                'title' => 'الوثائق التقنية',
                'links' => [
                    ['route' => 'docs.show', 'params' => ['step-workflow'], 'permission' => null, 'label' => 'سير عمل الخطوات', 'keywords' => 'step workflow documentation guide steps'],
                    ['route' => 'docs.show', 'params' => ['workflow-architecture'], 'permission' => null, 'label' => 'بنية سير العمل', 'keywords' => 'workflow architecture documentation guide'],
                    ['route' => 'docs.show', 'params' => ['roles-permissions'], 'permission' => 'permission.index', 'label' => 'شرح الصلاحيات', 'keywords' => 'roles permissions documentation guide'],
                    ['route' => 'docs.index', 'permission' => null, 'label' => 'كل الوثائق', 'keywords' => 'documentation docs technical markdown all'],
                ]
            ],
        ];
    }

    /**
     * Get a flat list of all searchable links available to the current user.
     * 
     * This method flattens the sidebar structure into a searchable array,
     * filtering links based on the current user's permissions. Each link
     * includes pre-generated URLs for immediate use in search results.
     * 
     * The returned array is optimized for search functionality:
     * - label: Display text (Arabic) shown in search results
     * - category: Section title for grouping results
     * - url: Pre-generated full URL (using Laravel's route helper)
     * - keywords: English keywords for matching search queries
     * 
     * Permission filtering:
     * - Links with null permission are always included
     * - User ID 1 (admin) sees all links regardless of permissions
     * - Other users only see links they have permission for
     * 
     * Special additions:
     * - User profile link is always added (not in sidebar but searchable)
     * 
     * @return array<int, array{label: string, category: string, url: string, keywords: string}>
     */
    public function getSearchableLinks(): array
    {
        $sections = $this->getSidebarSections();
        $userPermissions = auth()->check() ? auth()->user()->getPermissions() : [];
        $links = [];
        $isAdmin = auth()->check() && auth()->id() == 1;

        // Add Profile Link Manually as it's not in sidebar but requested (example: "profile")
        $links[] = [
            'label' => 'الملف الشخصي',
            'category' => 'المستخدم',
            'url' => route('user_profile.edit'),
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
