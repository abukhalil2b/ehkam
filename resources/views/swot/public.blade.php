<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $project->title }} - تحليل SWOT</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100">

    <div x-data="swotBoard()" x-init="init()" class="min-h-screen">

        <!-- نافذة الترحيب -->
        <div x-show="!participantName" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">

            <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">
                    مرحبًا بك في لوحة SWOT
                </h2>

                <p class="text-gray-600 mb-6">
                    {{ $project->title }}
                </p>

                <form @submit.prevent="initSession">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            أدخل اسمك (حد أقصى 16 حرفًا)
                        </label>

                        <input type="text" x-model="nameInput" @input="nameInput = nameInput.slice(0, 16)"
                            maxlength="16" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="اسمك">

                        <p class="text-xs text-gray-500 mt-1" x-text="`${nameInput.length}/16 حرف`"></p>
                    </div>

                    <button type="submit"
                        class="w-full px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        دخول اللوحة
                    </button>
                </form>
            </div>
        </div>

        <!-- اللوحة الرئيسية -->
        <div x-show="participantName" class="container mx-auto px-4 py-8">

            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">
                    {{ $project->title }}
                </h1>
                <p class="text-gray-600 mt-2">
                    مرحبًا،
                    <span class="font-semibold" x-text="participantName"></span>
                </p>
            </div>

            <!-- إضافة عنصر -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">
                    إضافة عنصر
                </h2>

                <form @submit.prevent="addItem" class="space-y-4">

                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                التصنيف
                            </label>

                            <select x-model="newItem.type" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                                <option value="">اختر التصنيف</option>
                                <option value="strength">نقاط القوة</option>
                                <option value="weakness">نقاط الضعف</option>
                                <option value="opportunity">الفرص</option>
                                <option value="threat">التهديدات</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                المحتوى (حد أقصى 55 حرفًا)
                            </label>

                            <input type="text" x-model="newItem.content"
                                @input="newItem.content = newItem.content.slice(0, 55)" maxlength="55" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg"
                                placeholder="اكتب الفكرة هنا">

                            <p class="text-xs text-gray-500 mt-1" x-text="`${newItem.content.length}/55 حرف`"></p>
                        </div>

                    </div>

                    <button type="submit" :disabled="submitting"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">

                        <span x-show="!submitting">إضافة</span>
                        <span x-show="submitting">جاري الإضافة...</span>
                    </button>
                </form>
            </div>

            <!-- شبكة SWOT -->
            <div class="grid md:grid-cols-2 gap-4">

                <!-- القوة -->
                <template x-for="block in blocks" :key="block.key">
                    <div :class="block.boxClass">
                        <h3 class="font-bold mb-3 text-lg flex justify-between" :class="block.titleClass">
                            <span x-text="block.title"></span>
                            <span class="text-sm font-normal" x-text="items[block.key].length + ' عنصر'"></span>
                        </h3>

                        <div class="space-y-2">
                            <template x-for="item in items[block.key]" :key="item.id">
                                <div class="bg-white p-3 rounded shadow-sm">
                                    <p class="text-sm text-gray-800" x-text="item.content"></p>
                                    <p class="text-xs text-gray-500 mt-1" x-text="item.participant_name"></p>
                                </div>
                            </template>

                            <p x-show="items[block.key].length === 0" class="text-gray-500 text-sm italic">
                                لا توجد عناصر بعد
                            </p>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    </div>

    <script>
        function swotBoard() {
            return {
                participantName: localStorage.getItem('swot_participant_{{ $project->id }}') || '',
                nameInput: '',
                submitting: false,
                newItem: {
                    type: '',
                    content: ''
                },
                token: '{{ $project->public_token }}',
                items: {
                    strength: [],
                    weakness: [],
                    opportunity: [],
                    threat: []
                },
                blocks: [{
                        key: 'strength',
                        title: 'نقاط القوة',
                        boxClass: 'border-2 border-green-200 bg-green-50 rounded-lg p-4 min-h-[300px]',
                        titleClass: 'text-green-700'
                    },
                    {
                        key: 'weakness',
                        title: 'نقاط الضعف',
                        boxClass: 'border-2 border-red-200 bg-red-50 rounded-lg p-4 min-h-[300px]',
                        titleClass: 'text-red-700'
                    },
                    {
                        key: 'opportunity',
                        title: 'الفرص',
                        boxClass: 'border-2 border-blue-200 bg-blue-50 rounded-lg p-4 min-h-[300px]',
                        titleClass: 'text-blue-700'
                    },
                    {
                        key: 'threat',
                        title: 'التهديدات',
                        boxClass: 'border-2 border-yellow-200 bg-yellow-50 rounded-lg p-4 min-h-[300px]',
                        titleClass: 'text-yellow-700'
                    },
                ],

                init() {
                    if (this.participantName) {
                        this.nameInput = this.participantName;
                        this.loadItems();
                        setInterval(() => this.loadItems(), 10000);
                    }
                },

                async initSession() {
                    if (!this.nameInput.trim()) return;

                    const response = await fetch(`/swot/board/${this.token}/init`, {
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
                        console.error('Invalid JSON response', await response.text());
                        alert('حدث خطأ في الاتصال بالخادم، يرجى المحاولة مرة أخرى');
                        return;
                    }

                    if (data.success) {
                        this.participantName = data.participant_name;
                        localStorage.setItem('swot_participant_{{ $project->id }}', this.participantName);
                        this.loadItems();
                        setInterval(() => this.loadItems(), 10000);
                    } else {
                        alert(data.error || 'فشل في تسجيل الدخول');
                    }

                },

                async addItem() {
                    if (!this.newItem.type || !this.newItem.content.trim()) return;

                    this.submitting = true;

                    try {
                        const response = await fetch(`/swot/board/${this.token}/add`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(this.newItem)
                        });

                        let data;
                        try {
                            data = await response.json();
                        } catch (e) {
                            const text = await response.text();
                            console.error('Invalid JSON:', text);
                            alert('حدث خطأ أثناء الإضافة، يرجى المحاولة مرة أخرى');
                            return;
                        }

                        if (response.ok && data.success) {
                            this.newItem = {
                                type: '',
                                content: ''
                            };
                            this.loadItems();
                        } else {
                            alert(data.error || 'حدث خطأ أثناء الإضافة');
                        }

                    } catch (e) {
                        console.error(e);
                        alert('حدث خطأ في الاتصال بالخادم');
                    } finally {
                        this.submitting = false;
                    }
                },

                async loadItems() {
                    try {
                        const response = await fetch(`/swot/board/${this.token}/items`);
                        const data = await response.json();
                        if (data.success) this.items = data.items;
                    } catch (e) {
                        console.error(e);
                    }
                }
            }
        }
    </script>

</body>

</html>
