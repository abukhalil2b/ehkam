@php
$userPermissions = auth()->user()->getPermissions();

$sidebarSections = [
    'indicator' => [
        'title' => 'المؤشرات',
        'links' => [
            ['route' => 'indicator.index', 'permission' => 'indicator.index', 'label' => 'قائمة المؤشرات'],

        ]
    ],
    'activity' => [
        'title' => 'الأنشطة',
        'links' => [
            ['route' => 'activity.index', 'permission' => 'activity.index', 'label' => 'قائمة الأنشطة'],
            ['route' => 'activity.create', 'permission' => 'activity.create', 'label' => 'جديد'],
        ]
    ],
    'assessment_questions' => [
        'title' => 'أسئلة التقييم',
        'links' => [
            ['route' => 'assessment_questions.index', 'permission' => 'assessment_questions.index', 'label' => 'قائمة الأسئلة'],
            ['route' => 'assessment_questions.create', 'permission' => 'assessment_questions.create', 'label' => 'إضافة سؤال'],
        ]
    ],
    'project_assessment_report' => [
        'title' => 'تقرير الأنشطة',
        'links' => [
            ['route' => 'project_assessment_report', 'permission' => 'project_assessment_report', 'label' => 'تقرير أداء'],
        ]
    ],
    'statistics' => [
        'title' => 'إحصائيات',
        'links' => [
            ['route' => 'statistic.index', 'permission' => 'statistic.index', 'label' => 'كل القطاعات'],
            ['route' => 'statistic.quran', 'permission' => 'statistic.quran', 'label' =>'القرآن الكريم' ],
        ]
    ],'questionnaire' => [
        'title' => 'إستبانات',
        'links' => [
            ['route' => 'questionnaire.index', 'permission' => 'questionnaire.index', 'label' => 'قائمة الإستبانات'],
            ['route' => 'questionnaire.create', 'permission' => 'questionnaire.create', 'label' => 'أكتب إستبانة'],
        ]
    ],
    'meeting_minute' => [
        'title' => 'محضر الاجتماعات',
        'links' => [
            ['route' => 'meeting_minute.index', 'permission' => 'meeting_minute.index', 'label' => 'قائمة المحاضر'],
        ]
    ],
    'workshop' => [
        'title' => 'الورش',
        'links' => [
            ['route' => 'workshop.index', 'permission' => 'workshop.index', 'label' => 'قائمة الورش'],
        ]
    ],
    'qr' => [
        'title' => 'الكيوآر',
        'links' => [
            ['route' => 'qr.index', 'permission' => 'qr.index', 'label' => 'قائمة الكيوآر'],
        ]
    ],
    'admin_structure' => [
        'title' => 'الهيكل',
        'links' => [
            ['route' => 'organizational_unit.index', 'permission' => 'organizational_unit.index', 'label' => 'عرض هيكل الوحدة'],
            ['route' => 'organizational_unit.create', 'permission' => 'organizational_unit.store', 'label' => 'اضافة هيكل الوحدة'],
            ['route' => 'admin_position.index', 'permission' => 'admin_position.index', 'label' => 'عرض الهيكل الوظيفي'],
            ['route' => 'admin_position.create', 'permission' => 'admin_position.store', 'label' => 'إضافة الهيكل الوظيفي'],
            ['route' => 'admin_users.create', 'permission' => 'admin_users.create', 'label' => 'موظف جديد'],
            ['route' => 'admin_users.index', 'permission' => 'admin_users.index', 'label' => 'الموظفين'],
        ]
    ],
    'permission' => [
        'title' => 'صلاحيات',
        'links' => [
            ['route' => 'permission.index', 'permission' => 'permission.index', 'label' => 'كل الصلاحيات'],
        ]
    ],
];
@endphp

<div :class="{ 'dark text-white-dark': $store.app.semidark }">
    <nav x-data="sidebar"
        class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] z-50 transition-all duration-300">
        <div class="bg-white dark:bg-[#0e1726] h-full overflow-y-scroll">
            <div class="flex justify-between items-center px-4 py-3">
                <a href="{{ route('dashboard') }}" class="main-logo flex items-center shrink-0">
                    <img class="w-8 flex-none" src="/assets/images/logo.png" alt="image" />
                </a>
                <span class="text-[10px]">
                    متابعة المشاريع والمؤشرات
                </span>
                <a href="javascript:;"
                    class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light transition duration-300 rtl:rotate-180"
                    @click="$store.app.toggleSidebar()">
                    <svg class="w-5 h-5 m-auto" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>

            <ul>
                @foreach($sidebarSections as $section)
                    @php
                        // check if user has at least one permission for this section
                        $showSection = false;
                        foreach($section['links'] as $link) {
                            if(!$link['permission'] || in_array($link['permission'], $userPermissions) || auth()->id() == 1) {
                                $showSection = true;
                                break;
                            }
                        }
                    @endphp

                    @if($showSection)
                        <x-nav-header title="{{ $section['title'] }}" />
                        <li class="nav-item">
                            <ul>
                                @foreach($section['links'] as $link)
                                    @if(!$link['permission'] || in_array($link['permission'], $userPermissions) || auth()->id() == 1)
                                        <li class="menu nav-item">
                                            <a href="{{ isset($link['params']) ? route($link['route'], $link['params']) : route($link['route']) }}" class="nav-link group">
                                                <div class="flex items-center">
                                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <circle opacity="0.5" cx="15" cy="6" r="3" fill="currentColor"></circle>
                                                        <ellipse opacity="0.5" cx="16" cy="17" rx="5" ry="3" fill="currentColor"></ellipse>
                                                        <circle cx="9.00098" cy="6" r="4" fill="currentColor"></circle>
                                                        <ellipse cx="9.00098" cy="17.001" rx="7" ry="4" fill="currentColor"></ellipse>
                                                    </svg>
                                                    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">
                                                        {{ $link['label'] }}
                                                    </span>
                                                </div>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
                <li>
                    <div class="py-16">

                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>

<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("sidebar", () => ({
            init() {
                const selector = document.querySelector('.sidebar ul a[href="' + window.location.pathname + '"]');
                if (selector) {
                    selector.classList.add('active');
                    const ul = selector.closest('ul.sub-menu');
                    if (ul) {
                        let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                        if (ele) {
                            ele = ele[0];
                            setTimeout(() => {
                                ele.click();
                            });
                        }
                    }
                }
            },
        }));
    });
</script>
