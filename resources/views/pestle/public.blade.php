<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $project->title }} - تحليل PESTLE</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div x-data="pestleBoard()" x-init="init()" class="min-h-screen bg-gray-50 flex flex-col">

        <!-- Welcome Modal -->
        <div x-show="!participantName" x-cloak
            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 backdrop-blur-sm p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full mx-auto">
                <div class="text-center mb-6">
                    <div class="bg-indigo-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">مرحبًا بك</h2>
                    <p class="text-gray-500 text-sm">{{ $project->title }}</p>
                </div>

                <form @submit.prevent="initSession">
                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم</label>
                        <input type="text" x-model="nameInput" @input="nameInput = nameInput.slice(0, 16)"
                            maxlength="16" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 text-right"
                            placeholder="أدخل اسمك للمشاركة">
                    </div>
                    <button type="submit"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-transform active:scale-95">
                        دخول اللوحة
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Interface -->
        <div x-show="participantName" x-cloak class="container mx-auto px-4 py-4 md:py-8 max-w-7xl pb-40 md:pb-8">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6 md:mb-8">
                <div>
                    <h1 class="text-xl md:text-3xl font-bold text-gray-900">{{ $project->title }}</h1>
                    <p class="text-xs md:text-base text-gray-500 mt-1">مرحباً، <span class="font-bold text-gray-800"
                            x-text="participantName"></span></p>
                </div>
                <div
                    class="w-8 h-8 md:w-10 md:h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold border border-indigo-200 text-sm md:text-base">
                    <span x-text="participantName.charAt(0)"></span>
                </div>
            </div>

            <!-- Add Item Section (Responsive: Sticky Bottom on Mobile) -->
            <div
                class="fixed bottom-0 left-0 right-0 z-40 bg-white border-t border-gray-200 p-3 shadow-[0_-4px_20px_rgba(0,0,0,0.1)] md:static md:bg-white md:rounded-2xl md:shadow-sm md:border md:p-6 md:mb-8 md:transform-none transition-all duration-300">
                <div class="container mx-auto max-w-7xl">
                    <h2 class="hidden md:flex text-lg font-bold text-gray-800 mb-4 items-center">
                        <svg class="w-5 h-5 ml-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        إضافة عنصر جديد
                    </h2>

                    <form @submit.prevent="addItem" class="space-y-3 md:space-y-4">

                        <!-- PESTLE Categories: 6 items -->
                        <div
                            class="flex overflow-x-auto gap-2 pb-2 md:pb-0 md:grid md:grid-cols-6 md:gap-3 no-scrollbar">
                            <template x-for="type in types" :key="type.key">
                                <button type="button" @click="newItem.type = type.key"
                                    class="flex-shrink-0 flex items-center justify-center gap-2 p-2 md:p-3 rounded-xl border transition-all duration-200 min-w-[100px] md:min-w-0"
                                    :class="newItem.type === type.key ? type.activeClass + ' ring-2 ring-offset-1 ' + type.ringColor : 'border-gray-200 bg-gray-50 text-gray-400 grayscale filter hover:grayscale-0'">
                                    <span class="text-lg md:text-xl" x-text="type.icon"></span>
                                    <span class="font-bold text-xs md:text-sm whitespace-nowrap"
                                        x-text="type.title"></span>
                                </button>
                            </template>
                        </div>

                        <!-- Input & Submit -->
                        <div class="flex gap-2 md:gap-3 items-center">
                            <div class="relative flex-grow">
                                <input type="text" x-model="newItem.content"
                                    @input="newItem.content = newItem.content.slice(0, 55)" maxlength="55" required
                                    class="w-full pl-12 pr-4 py-2.5 md:py-3 text-sm md:text-base border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    placeholder="اكتب فكرتك هنا..." :disabled="!newItem.type">
                                <div class="absolute top-1 md:top-3 left-3 text-[10px] md:text-xs px-1.5 py-0.5 md:py-1 bg-gray-100 rounded text-gray-500"
                                    x-text="`${newItem.content.length}/55`"></div>
                            </div>

                            <button type="submit" :disabled="submitting || !newItem.type || !newItem.content.trim()"
                                class="w-11 h-11 md:w-auto md:h-auto md:px-6 md:py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-md flex-shrink-0 flex items-center justify-center">
                                <span class="hidden md:inline" x-show="!submitting">إضافة</span>
                                <svg x-show="!submitting" class="md:hidden w-5 h-5 transform -rotate-90" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                <span x-show="submitting"><svg class="w-5 h-5 animate-spin" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg></span>
                            </button>
                        </div>
                        <p x-show="!newItem.type" class="text-[10px] md:text-sm text-red-500 -mt-2 px-1">* اختر المجال
                        </p>
                    </form>
                </div>
            </div>

            <!-- Dashboard Grid (3 columns on Desktop) -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <template x-for="block in types" :key="block.key">
                    <div :class="block.boxClass">
                        <div class="flex justify-between items-center mb-4 pb-2 border-b border-black/5">
                            <h3 class="font-bold text-lg flex items-center gap-2" :class="block.titleClass">
                                <span x-text="block.icon"></span>
                                <span x-text="block.title"></span>
                            </h3>
                            <span class="bg-white/50 px-2 py-1 rounded text-xs font-bold" :class="block.titleClass"
                                x-text="items[block.key].length"></span>
                        </div>

                        <div class="space-y-3 min-h-[200px]">
                            <template x-for="item in items[block.key]" :key="item.id">
                                <div
                                    class="bg-white p-3 rounded-lg shadow-sm border border-gray-100 animate-fade-in hover:shadow-md transition-shadow">
                                    <p class="text-gray-800 text-sm font-medium leading-relaxed" x-text="item.content">
                                    </p>
                                    <p class="text-xs text-gray-400 mt-2 text-left" x-text="item.participant_name"></p>
                                </div>
                            </template>

                            <div x-show="items[block.key].length === 0"
                                class="flex flex-col items-center justify-center h-40 opacity-50">
                                <span class="text-4xl grayscale mb-2" x-text="block.icon"></span>
                                <p class="text-sm">لا توجد عناصر</p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fade-in 0.3s ease-out forwards;
        }

        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }
    </style>

    <script>
        function pestleBoard() {
            return {
                participantName: localStorage.getItem('pestle_participant_{{ $project->id }}') || '',
                nameInput: '',
                submitting: false,
                newItem: {
                    type: '',
                    content: ''
                },
                token: '{{ $project->public_token }}',
                items: {
                    political: [],
                    economic: [],
                    social: [],
                    technological: [],
                    legal: [],
                    environmental: []
                },
                // Unified definition for PESTLE types (used for buttons and grid blocks)
                types: [
                    {
                        key: 'political',
                        title: 'السياسية',
                        icon: '⚖️',
                        ringColor: 'ring-red-400',
                        activeClass: 'border-red-500 bg-red-50 text-red-700 shadow-sm',
                        boxClass: 'border border-red-200 bg-red-50 rounded-2xl p-4',
                        titleClass: 'text-red-800'
                    },
                    {
                        key: 'economic',
                        title: 'الاقتصادية',
                        icon: '💰',
                        ringColor: 'ring-blue-400',
                        activeClass: 'border-blue-500 bg-blue-50 text-blue-700 shadow-sm',
                        boxClass: 'border border-blue-200 bg-blue-50 rounded-2xl p-4',
                        titleClass: 'text-blue-800'
                    },
                    {
                        key: 'social',
                        title: 'الاجتماعية',
                        icon: '👥',
                        ringColor: 'ring-yellow-400',
                        activeClass: 'border-yellow-500 bg-yellow-50 text-yellow-700 shadow-sm',
                        boxClass: 'border border-yellow-200 bg-yellow-50 rounded-2xl p-4',
                        titleClass: 'text-yellow-800'
                    },
                    {
                        key: 'technological',
                        title: 'التقنية',
                        icon: '💻',
                        ringColor: 'ring-purple-400',
                        activeClass: 'border-purple-500 bg-purple-50 text-purple-700 shadow-sm',
                        boxClass: 'border border-purple-200 bg-purple-50 rounded-2xl p-4',
                        titleClass: 'text-purple-800'
                    },
                    {
                        key: 'legal',
                        title: 'القانونية',
                        icon: '📜',
                        ringColor: 'ring-gray-400',
                        activeClass: 'border-gray-500 bg-gray-50 text-gray-700 shadow-sm',
                        boxClass: 'border border-gray-200 bg-gray-50 rounded-2xl p-4',
                        titleClass: 'text-gray-800'
                    },
                    {
                        key: 'environmental',
                        title: 'البيئية',
                        icon: '🌱',
                        ringColor: 'ring-emerald-400',
                        activeClass: 'border-emerald-500 bg-emerald-50 text-emerald-700 shadow-sm',
                        boxClass: 'border border-emerald-200 bg-emerald-50 rounded-2xl p-4',
                        titleClass: 'text-emerald-800'
                    }
                ],

                init() {
                    if (this.participantName) {
                        this.nameInput = this.participantName;
                        this.loadItems();
                        setInterval(() => this.loadItems(), 5000);
                    }
                },

                async initSession() {
                    if (!this.nameInput.trim()) return;

                    const response = await fetch(`/pestle/board/${this.token}/init`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            participant_name: this.nameInput
                        })
                    });

                    let data;
                    try {
                        data = await response.json();
                    } catch (e) {
                        alert('Server Error'); return;
                    }

                    if (data.success) {
                        this.participantName = data.participant_name;
                        localStorage.setItem('pestle_participant_{{ $project->id }}', this.participantName);
                        this.loadItems();
                        setInterval(() => this.loadItems(), 5000);
                    } else {
                        alert(data.error || 'فشل في تسجيل الدخول');
                    }
                },

                async addItem() {
                    if (!this.newItem.type || !this.newItem.content.trim()) return;

                    this.submitting = true;
                    try {
                        const response = await fetch(`/pestle/board/${this.token}/add`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.newItem)
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.newItem.content = '';
                            this.loadItems();
                        } else {
                            alert(data.error);
                        }
                    } catch (e) { console.error(e); }
                    finally { this.submitting = false; }
                },

                async loadItems() {
                    try {
                        const response = await fetch(`/pestle/board/${this.token}/items`);
                        const data = await response.json();
                        if (data.success) this.items = data.items;
                    } catch (e) { console.error(e); }
                }
            }
        }
    </script>
</body>

</html>