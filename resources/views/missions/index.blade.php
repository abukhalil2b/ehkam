<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المهمات</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body @class(['bg-slate-50', 'min-h-screen']) x-data="missionDrawer(@js($users), @js($missions))">



    <!-- HEADER -->
    <nav @class(['bg-white', 'border-b', 'shadow-sm'])>
        <div @class([
            'max-w-7xl',
            'mx-auto',
            'px-6',
            'py-4',
            'flex',
            'justify-between',
            'items-center',
        ])>
            <h1 @class(['text-2xl', 'font-bold'])>إدارة المهمات</h1>
            <button @click="openCreate()" @class(['bg-indigo-600', 'text-white', 'px-6', 'py-2', 'rounded-xl'])>+ مهمة</button>
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                <span class="hidden sm:inline">العودة إلى الرئيسية</span>
                <span class="sm:hidden">عودة</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>

            </a>
        </div>
    </nav>

    <!-- MISSIONS -->
    <main @class([
        'max-w-7xl',
        'mx-auto',
        'px-6',
        'py-10',
        'grid',
        'md:grid-cols-3',
        'gap-8',
    ])>
        @foreach ($missions as $mission)
            <div @class(['bg-white', 'rounded-3xl', 'border', 'shadow-sm', 'p-6'])>
                <div @class(['flex', 'justify-between', 'mb-3'])>
                    <span @class([
                        'text-xs',
                        'bg-emerald-100',
                        'text-emerald-700',
                        'px-3',
                        'py-1',
                        'rounded-full',
                    ])>
                        {{ __($mission->status) }}
                    </span>
                    <span @class(['text-slate-400', 'text-sm'])>#{{ $mission->id }}</span>
                </div>

                <h2 @class(['font-bold', 'text-lg', 'mb-2'])>{{ $mission->title }}</h2>
                <div @class([
                    'flex',
                    'items-center',
                    'gap-2',
                    'text-xs',
                    'text-slate-500',
                    'mb-4',
                ])>
                    <svg @class(['w-4', 'h-4']) fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>{{ $mission->start_date ? $mission->start_date->format('Y-m-d') : '---' }}</span>
                    <span>→</span>
                    <span>{{ $mission->end_date ? $mission->end_date->format('Y-m-d') : '---' }}</span>
                </div>
                <!-- MEMBERS -->
                <div class="flex -space-x-2 mb-6">
                    @foreach ($mission->members as $m)
                        <div class="relative group">

                            <!-- AVATAR -->
                            <div
                                class="w-10 h-10 rounded-full border-2 border-white flex items-center justify-center
                   font-bold shadow-sm transition-transform
                   group-hover:scale-110 z-0 group-hover:z-10
                   {{ $m->role === 'leader' ? 'bg-indigo-600 text-white' : 'bg-indigo-100 text-indigo-700' }}">

                                {{ mb_substr($m->user->name, 0, 1) }}

                                {{-- leader badge --}}
                                @if ($m->role === 'leader')
                                    <span
                                        class="absolute -top-1 -right-1 bg-amber-400
                             w-3 h-3 rounded-full border border-white"></span>
                                @endif
                            </div>

                            <!-- TOOLTIP -->
                            <div
                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2
                   hidden group-hover:block
                   bg-slate-900 text-white text-[10px]
                   rounded-lg px-2 py-1.5 whitespace-nowrap
                   shadow-xl z-50">

                                <div class="font-bold {{ $m->role === 'leader' ? 'text-amber-400' : '' }}">
                                    {{ $m->user->name }}
                                    @if ($m->role === 'leader')
                                        (القائد)
                                    @endif
                                </div>

                                <div class="opacity-80 pt-1 border-t border-slate-700 mt-1">
                                    {{ $m->can_create_tasks ? '✔ مهام' : '✖ مهام' }}
                                    |
                                    {{ $m->can_view_all_tasks ? '✔ رؤية' : '✖ رؤية' }}
                                </div>

                                <!-- arrow -->
                                <div
                                    class="w-2 h-2 bg-slate-900 rotate-45
                        absolute -bottom-1 left-1/2 -translate-x-1/2">
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>


                <a href="{{ route('missions.task.show', $mission->id) }}" @class([
                    'block',
                    'text-center',
                    'bg-slate-900',
                    'text-white',
                    'py-3',
                    'rounded-xl',
                    'font-bold',
                ])>
                    فتح الكانبان
                </a>
                <button @click="openEdit({{ $mission->id }})" @class(['text-sm', 'text-indigo-600', 'font-bold'])>تعديل</button>

            </div>
        @endforeach
    </main>

    <!-- DRAWER -->
    <div x-show="open" x-cloak @class(['fixed', 'inset-0', 'z-50', 'flex', 'justify-end'])>
        <div @class(['fixed', 'inset-0', 'bg-slate-900/40', 'backdrop-blur-sm']) @click="open=false"></div>

        <div @class([
            'relative',
            'w-full',
            'max-w-lg',
            'bg-white',
            'shadow-2xl',
            'h-full',
            'flex',
            'flex-col',
            'transform',
            'transition-transform',
        ]) x-transition:enter="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="translate-x-0" x-transition:leave-end="translate-x-full">

            <div @class(['p-6', 'border-b', 'flex', 'justify-between', 'items-center'])>
                <h2 @class(['text-xl', 'font-bold']) x-text="mode === 'create' ? 'إنشاء مهمة جديدة' : 'تعديل المهمة'"></h2>
                <button @click="open=false" @class(['text-slate-400', 'hover:text-red-500', 'transition'])>
                    <svg @class(['w-7', 'h-7']) fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form method="POST"
                :action="mode === 'create' ? '{{ route('mission.store') }}' : '{{ route('mission.update') }}'"
                @class(['flex-1', 'overflow-y-auto'])>
                @csrf
                <input type="hidden" name="mission_id" :value="form.id">
                <input type="hidden" name="leader_id" :value="leaderId">

                <template x-for="m in members" :key="m.id">
                    <input type="hidden" name="member_ids[]" :value="m.id">
                </template>

                <template x-for="(perm, id) in permissions" :key="'perm-' + id">
                    <div>
                        <input type="hidden" :name="`permissions[${id}][can_create_tasks]`"
                            :value="perm.can_create_tasks">
                        <input type="hidden" :name="`permissions[${id}][can_view_all_tasks]`"
                            :value="perm.can_view_all_tasks">
                    </div>
                </template>

                <div @class(['p-6', 'pb-24'])>
                    <div @class(['flex', 'gap-2', 'mb-8'])>
                        <template x-for="i in [1,2,3]" :key="i">
                            <div @class([
                                'flex-1',
                                'h-1.5',
                                'rounded-full',
                                'transition-colors',
                                'duration-300',
                            ]) :class="step >= i ? 'bg-indigo-600' : 'bg-slate-100'">
                            </div>
                        </template>
                    </div>

                    <div x-show="step===1">
                        <label @class(['font-bold', 'block', 'mb-1'])>اسم المهمة</label>
                        <input name="title" x-model="form.title" required @class(['w-full', 'border', 'rounded-xl', 'p-3', 'mb-4'])>

                        <label @class(['font-bold', 'block', 'mb-1'])>الوصف</label>
                        <textarea name="description" x-model="form.description" @class(['w-full', 'border', 'rounded-xl', 'p-3', 'mb-4'])></textarea>

                        <div @class(['grid', 'grid-cols-2', 'gap-4', 'mb-4'])>
                            <div>
                                <label @class(['font-bold', 'text-sm', 'block', 'mb-1'])>تاريخ البدء</label>
                                <input type="date" name="start_date" x-model="form.start_date"
                                    @class(['w-full', 'border', 'rounded-xl', 'p-3'])>
                            </div>
                            <div>
                                <label @class(['font-bold', 'text-sm', 'block', 'mb-1'])>تاريخ الانتهاء</label>
                                <input type="date" name="end_date" x-model="form.end_date"
                                    @class(['w-full', 'border', 'rounded-xl', 'p-3'])>
                            </div>
                        </div>
                    </div>

                    <div x-show="step===2">
                        <label @class(['font-bold', 'block', 'mb-2'])>اختر قائد المهمة</label>
                        <input x-model="leaderSearch" placeholder="ابحث عن اسم..." @class([
                            'w-full',
                            'border',
                            'rounded-xl',
                            'p-3',
                            'mb-3',
                            'bg-slate-50',
                        ])>
                        <div @class([
                            'border',
                            'rounded-xl',
                            'max-h-64',
                            'overflow-y-auto',
                            'divide-y',
                            'shadow-sm',
                        ])>
                            <template x-for="u in filteredLeaders" :key="u.id">
                                <div @click="selectLeader(u)" :class="leader?.id === u.id ? 'bg-indigo-50' : ''"
                                    @class([
                                        'px-4',
                                        'py-3',
                                        'hover:bg-slate-50',
                                        'cursor-pointer',
                                        'flex',
                                        'justify-between',
                                        'items-center',
                                        'transition',
                                    ])>
                                    <span x-text="u.name"></span>
                                    <span x-show="leader?.id === u.id" @class(['text-indigo-600'])>✔</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div x-show="step===3">
                        <label @class(['font-bold', 'block', 'mb-2'])>إضافة أعضاء وصلاحيات</label>
                        <input x-model="memberSearch" placeholder="ابحث عن أعضاء..." @class([
                            'w-full',
                            'border',
                            'rounded-xl',
                            'p-3',
                            'mb-4',
                            'bg-slate-50',
                        ])>

                        <div @class([
                            'border',
                            'rounded-xl',
                            'max-h-40',
                            'overflow-y-auto',
                            'mb-6',
                            'bg-white',
                        ])>
                            <template x-for="u in filteredMembers" :key="u.id">
                                <div @click="addMember(u)" @class([
                                    'px-4',
                                    'py-2',
                                    'hover:bg-indigo-50',
                                    'cursor-pointer',
                                    'border-b',
                                    'last:border-0',
                                ])>
                                    <span x-text="u.name"></span>
                                </div>
                            </template>
                        </div>

                        <div @class(['space-y-3'])>
                            <template x-for="m in members" :key="m.id">
                                <div @class([
                                    'border',
                                    'border-slate-200',
                                    'rounded-2xl',
                                    'p-4',
                                    'bg-slate-50/50',
                                ])>
                                    <div @class(['flex', 'justify-between', 'items-center', 'mb-3'])>
                                        <span @class(['font-bold', 'text-indigo-900']) x-text="m.name"></span>
                                        <button type="button" @click="removeMember(m.id)"
                                            @class(['text-red-400', 'hover:text-red-600'])>✕</button>
                                    </div>
                                    <div @class(['flex', 'gap-4'])>
                                        <label @class([
                                            'flex',
                                            'items-center',
                                            'gap-2',
                                            'text-sm',
                                            'cursor-pointer',
                                            'select-none',
                                        ])>
                                            <input type="checkbox" x-model="permissions[m.id].can_create_tasks"
                                                @class(['rounded', 'text-indigo-600'])>
                                            إنشاء مهام
                                        </label>
                                        <label @class([
                                            'flex',
                                            'items-center',
                                            'gap-2',
                                            'text-sm',
                                            'cursor-pointer',
                                            'select-none',
                                        ])>
                                            <input type="checkbox" x-model="permissions[m.id].can_view_all_tasks"
                                                @class(['rounded', 'text-indigo-600'])>
                                            رؤية الجميع
                                        </label>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div @class([
                    'sticky',
                    'bottom-0',
                    'left-0',
                    'right-0',
                    'p-6',
                    'bg-white',
                    'border-t',
                    'flex',
                    'items-center',
                    'justify-between',
                    'gap-4',
                ])>

                    <div @class(['flex-none'])>
                        <button type="button" x-show="step > 1" @click="step--" @class([
                            'px-6',
                            'py-2.5',
                            'bg-slate-100',
                            'text-slate-600',
                            'rounded-xl',
                            'font-bold',
                            'hover:bg-slate-200',
                            'transition',
                        ])>
                            السابق
                        </button>
                    </div>

                    <div @class(['flex-none'])>
                        <button type="button" x-show="step < 3" @click="step++" @class([
                            'px-8',
                            'py-2.5',
                            'bg-indigo-600',
                            'text-white',
                            'rounded-xl',
                            'font-bold',
                            'shadow-lg',
                            'shadow-indigo-100',
                            'hover:bg-indigo-700',
                            'transition',
                        ])>
                            التالي
                        </button>

                        <button type="submit" x-show="step === 3" @class([
                            'px-8',
                            'py-2.5',
                            'bg-emerald-600',
                            'text-white',
                            'rounded-xl',
                            'font-bold',
                            'shadow-lg',
                            'shadow-emerald-100',
                            'hover:bg-emerald-700',
                            'transition',
                        ])>
                            <span x-text="mode === 'create' ? 'إنشاء المهمة' : 'تحديث المهمة'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ALPINE -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('missionDrawer', (users, missions) => ({
                open: false,
                step: 1,
                mode: 'create',
                users,
                missions,
                form: {
                    id: null,
                    title: '',
                    description: '',
                    leader: null
                },
                members: [],
                permissions: {},
                leaderSearch: '',
                memberSearch: '',
                leader: null,
                openCreate() {
                    this.reset()
                    this.open = true
                    this.mode = 'create'
                },
                get leaderId() {
                    return this.leader ? this.leader.id : ''
                },

                openEdit(id) {
                    this.reset();
                    let m = this.missions.find(x => x.id === id);

                    // Fill the form object including dates
                    this.form = {
                        id: m.id,
                        title: m.title,
                        description: m.description,
                        // Slice to get only YYYY-MM-DD from the ISO string
                        start_date: m.start_date ? m.start_date.split('T')[0] : '',
                        end_date: m.end_date ? m.end_date.split('T')[0] : ''
                    };

                    this.leader = m.leader;
                    this.members = m.members.filter(mm => mm.role === 'member').map(mm => mm.user);

                    m.members.forEach(mm => {
                        this.permissions[mm.user.id] = {
                            can_create_tasks: mm.can_create_tasks == 1,
                            can_view_all_tasks: mm.can_view_all_tasks == 1
                        };
                    });

                    this.mode = 'edit';
                    this.open = true;
                    this.step = 1; // Always start at step 1
                },

                reset() {
                    this.step = 1
                    this.form = {
                        id: null,
                        title: '',
                        description: ''
                    }
                    this.leader = null
                    this.members = []
                    this.permissions = {}
                    this.leaderSearch = ''
                    this.memberSearch = ''
                },

                get filteredLeaders() {
                    return this.users.filter(u => u.name.includes(this.leaderSearch))
                },

                get filteredMembers() {
                    return this.users.filter(u =>
                        (!this.leader || u.id !== this.leader.id) &&
                        !this.members.find(m => m.id === u.id) &&
                        (this.memberSearch === '' || u.name.toLowerCase().includes(this
                            .memberSearch.toLowerCase()))
                    )
                },

                selectLeader(u) {
                    this.leader = u
                    this.members = this.members.filter(m => m.id !== u.id)
                },

                addMember(u) {
                    this.members.push(u)
                    this.permissions[u.id] = {
                        can_create_tasks: true,
                        can_view_all_tasks: false
                    }
                },

                removeMember(id) {
                    this.members = this.members.filter(m => m.id !== id)
                    delete this.permissions[id]
                }
            }))
        })
    </script>

</body>

</html>
