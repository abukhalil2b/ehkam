<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة جدول الأعمال</title>
    @vite(['resources/css/app.css'])
    @vite('resources/js/app.js')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Cairo:wght@300;400;600;800&display=swap');

        body {
            font-family: 'Cairo', sans-serif;
            background: #0f172a;
            background-image:
                radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%),
                radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
        }

        .mono { font-family: 'JetBrains Mono', monospace; }

        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .glass-hover:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-white min-h-screen p-4 md:p-8">

<div x-data="agendaBoard()" x-init="init()" class="max-w-6xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="flex items-center gap-3">
            <div x-show="!titleEditing" class="flex items-center gap-2">
                <h1 x-text="boardTitle" class="py-6 text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-purple-500 cursor-pointer hover:opacity-80 transition-opacity" @click="titleEditing = true"></h1>
                <button @click="titleEditing = true" class="p-2 hover:bg-white/10 rounded-lg text-slate-400 hover:text-white transition-colors" title="تعديل العنوان">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </button>
            </div>
            <div x-show="titleEditing" x-transition class="flex items-center gap-2">
                <input x-model="boardTitle" type="text" 
                       class="text-4xl font-bold bg-transparent border-b-2 border-cyan-500 focus:outline-none px-2 py-1"
                       @keydown.enter="titleEditing = false" @blur="titleEditing = false">
                <button @click="titleEditing = false" class="p-2 hover:bg-green-500/20 rounded-lg text-green-400 transition-colors" title="حفظ">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </button>
                <button @click="boardTitle = 'لوحة جدول الأعمال'; titleEditing = false" class="p-2 hover:bg-red-500/20 rounded-lg text-red-400 transition-colors" title="إلغاء">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-slate-400 mt-1">إدارة جدول اجتماعاتك بدقة</p>
        </div>

        <div class="flex gap-3">
            <button @click="showSoundSettings = !showSoundSettings"
                    class="glass px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/>
                </svg>
                <span>الصوت</span>
            </button>
            <button @click="openAddModal()"
                    class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 px-6 py-2 rounded-lg font-semibold shadow-lg shadow-cyan-500/25 transition-all transform hover:scale-105">
                + إضافة عنصر جديد
            </button>
        </div>
    </div>

    <!-- Sound Settings Panel -->
    <div x-show="showSoundSettings" x-transition class="glass rounded-xl p-4 mb-6">
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="flex-1">
                <label class="block text-sm text-slate-400 mb-2">رابط صوت الإشعار (MP3/WAV)</label>
                <input x-model="soundUrl" type="text" placeholder="https://..."
                       class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-500">
            </div>
            <button @click="testSound()" class="mt-6 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                اختبار الصوت
            </button>
        </div>
    </div>

    <!-- Current Session Banner -->
    <template x-if="activeItem">
        <div class="mb-8">
            <div class="glass rounded-2xl p-6 border-l-4 border-cyan-500 bg-gradient-to-r from-cyan-500/10 to-transparent">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <div class="text-cyan-400 text-sm font-bold uppercase tracking-wider mb-1">النشط حالياً</div>
                        <h2 x-text="activeItem?.title" class="text-2xl font-bold"></h2>
                    </div>
                    <div class="flex items-center gap-6">
                        <!-- Countdown Display -->
                        <div class="text-center">
                            <div class="text-6xl font-bold mono tabular-nums"
                                 :class="getActiveCountdownColor()"
                                 x-text="formatCountdown(activeCountdown)"></div>
                        </div>
                        <!-- Countdown Controls -->
                        <div class="flex flex-col gap-2">
                            <!-- Play/Pause Button -->
                            <button @click="toggleCountdown()" 
                                    class="p-3 rounded-lg transition-all transform hover:scale-105"
                                    :class="countdownRunning ? 'bg-yellow-500/20 text-yellow-400 hover:bg-yellow-500/30' : 'bg-green-500/20 text-green-400 hover:bg-green-500/30'"
                                    :title="countdownRunning ? 'إيقاف' : 'بدء'">
                                <svg x-show="!countdownRunning" class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                                <svg x-show="countdownRunning" x-cloak class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                                </svg>
                            </button>
                            <!-- Reset Button -->
                            <button @click="resetCountdown()" 
                                    class="p-2 rounded-lg bg-slate-700 text-slate-300 hover:bg-slate-600 transition-colors"
                                    title="إعادة تعيين">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Agenda List -->
    <div class="space-y-3">
        <template x-for="(item, index) in sortedAgenda" :key="item.id">
            <div class="glass glass-hover rounded-xl p-4 transition-all duration-300 relative overflow-hidden group"
                 :class="{
                     'border-l-4 border-green-500': isPast(item) && !isActive(item),
                     'border-l-4 border-cyan-500': isActive(item),
                     'border-l-4 border-slate-600': !isActive(item) && !isPast(item),
                     'opacity-50': isPast(item) && !isActive(item)
                 }">

                <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                    <!-- Time Column -->
                    <div class="w-32 shrink-0">
                        <div class="mono text-lg font-bold" x-text="item.startTime || '--:--'"></div>
                        <div class="text-slate-500 text-sm mono" x-text="item.endTime ? '→ ' + item.endTime : ''"></div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <h3 x-text="item.title" class="font-semibold text-lg truncate"></h3>
                        <div class="flex items-center gap-2 mt-1">
                            <span x-show="isActive(item)" class="px-2 py-0.5 bg-cyan-500/20 text-cyan-400 text-xs rounded-full font-medium flex items-center gap-1">
                                <span class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></span>
                                نشط
                            </span>
                            <span x-show="isPast(item) && !isActive(item)" class="px-2 py-0.5 bg-slate-700 text-slate-400 text-xs rounded-full">مكتمل</span>
                            <span x-show="!isActive(item) && !isPast(item)" class="px-2 py-0.5 bg-slate-700 text-slate-400 text-xs rounded-full">
                                <span x-text="(item.countdownMinutes || 30) + ' دقيقة'"></span>
                            </span>
                        </div>
                    </div>

                    <!-- Active Item Countdown -->
                    <div x-show="isActive(item)" class="mono text-2xl font-bold tabular-nums"
                         :class="getActiveCountdownColor()"
                         x-text="formatCountdown(activeCountdown)"></div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <!-- Activate Button -->
                        <button x-show="!isActive(item)" 
                                @click="activateItem(item)"
                                class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 rounded-lg text-sm font-medium transition-colors">
                                <span x-text="isPast(item) ? 'إعادة تفعيل' : 'تفعيل'"></span>
                        </button>
                        <!-- Stop Button (when active) -->
                        <button x-show="isActive(item)" 
                                @click="stopItem()"
                                class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded-lg text-sm font-medium transition-colors">
                                إيقاف
                        </button>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button @click="editItem(item)" class="p-2 hover:bg-white/10 rounded-lg text-slate-400 hover:text-white" title="تعديل">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button @click="deleteItem(item.id)" class="p-2 hover:bg-red-500/20 rounded-lg text-slate-400 hover:text-red-400" title="حذف">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Progress Bar for active item -->
                <div x-show="isActive(item)" class="absolute bottom-0 left-0 h-1 bg-gradient-to-r from-cyan-500 to-purple-500 transition-all duration-1000"
                     :style="'width: ' + getActiveProgress() + '%'"></div>
            </div>
        </template>

        <!-- Empty State -->
        <div x-show="agenda.length === 0" class="text-center py-16 text-slate-500">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-lg">لا توجد عناصر في جدول الأعمال بعد</p>
            <button @click="openAddModal()" class="mt-4 text-cyan-400 hover:text-cyan-300 font-medium">إنشاء أول عنصر ←</button>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4" style="display: none;">
        <div @click.away="closeModal()" x-transition.scale class="glass rounded-2xl p-6 w-full max-w-md border border-slate-700 shadow-2xl">
            <h2 x-text="editingId ? 'تعديل عنصر جدول الأعمال' : 'عنصر جدول أعمال جديد'" class="text-2xl font-bold mb-6"></h2>

            <form @submit.prevent="saveItem()">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-slate-400 mb-2">العنوان</label>
                        <input x-model="form.title" type="text" required
                               class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2 focus:outline-none focus:border-cyan-500 transition-colors"
                               placeholder="مثلاً: كلمة الافتتاحية">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-slate-400 mb-2">وقت البدء (اختياري)</label>
                            <input x-model="form.startTime" type="time"
                                   class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2 focus:outline-none focus:border-cyan-500">
                        </div>
                        <div>
                            <label class="block text-sm text-slate-400 mb-2">وقت الانتهاء (اختياري)</label>
                            <input x-model="form.endTime" type="time"
                                   class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2 focus:outline-none focus:border-cyan-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-slate-400 mb-2">مدة العد التنازلي (بالدقائق)</label>
                        <input x-model.number="form.countdownMinutes" type="number" min="1" 
                               class="w-full bg-slate-800/50 border border-slate-700 rounded-lg px-4 py-2 focus:outline-none focus:border-cyan-500"
                               placeholder="30">
                        <p class="text-xs text-slate-500 mt-1">أدخل المدة بالدقائق للعد التنازلي</p>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <input x-model="form.playSound" type="checkbox" id="soundCheck"
                               class="w-5 h-5 rounded border-slate-600 bg-slate-800 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-slate-900">
                        <label for="soundCheck" class="text-sm text-slate-300 cursor-pointer">تشغيل صوت الإشعار عند البدء</label>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" @click="closeModal()"
                            class="flex-1 px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg font-medium transition-colors">
                        إلغاء
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 rounded-lg font-medium shadow-lg shadow-cyan-500/25 transition-all">
                        حفظ
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function agendaBoard() {
    return {
        agenda: [],
        showModal: false,
        showSoundSettings: false,
        editingId: null,
        soundUrl: '/assets/sounds/notification1.mp3',
        audio: null,
        countdownRunning: false,
        titleEditing: false,
        boardTitle: 'لوحة جدول الأعمال',
        activeItem: null,
        activeCountdown: 0,
        initialCountdown: 0,
        countdownInterval: null,
        form: {
            title: '',
            startTime: '',
            endTime: '',
            countdownMinutes: 30,
            playSound: true
        },

        init() {
            // Load sample data if empty
            this.agenda = [
                { id: 1, title: 'الافتتاحية', startTime: '09:00', endTime: '09:30', countdownMinutes: 30, playSound: true },
                { id: 2, title: 'كلمة المتحدث الرئيسي', startTime: '09:30', endTime: '10:30', countdownMinutes: 60, playSound: true },
                { id: 3, title: 'استراحة قهوة', startTime: '10:30', endTime: '11:00', countdownMinutes: 30, playSound: true },
                { id: 4, title: 'مناقشة مفتوحة', startTime: '11:00', endTime: '12:30', countdownMinutes: 90, playSound: true }
            ];

            this.audio = new Audio(this.soundUrl);
            this.audio.preload = 'auto';
        },

        activateItem(item) {
            // Stop current countdown if running
            this.stopCountdown();
            
            this.activeItem = item;
            this.initialCountdown = (item.countdownMinutes || 30) * 60;
            this.activeCountdown = this.initialCountdown;
            this.countdownRunning = false;
            
            // Play notification sound
            if (item.playSound) {
                this.playNotification();
            }
        },

        stopItem() {
            this.stopCountdown();
            this.activeItem = null;
        },

        toggleCountdown() {
            if (this.countdownRunning) {
                // Pause
                this.countdownRunning = false;
                if (this.countdownInterval) {
                    clearInterval(this.countdownInterval);
                    this.countdownInterval = null;
                }
            } else {
                // Start
                this.countdownRunning = true;
                this.countdownInterval = setInterval(() => {
                    if (this.activeCountdown > 0) {
                        this.activeCountdown--;
                    } else {
                        this.stopCountdown();
                        this.playNotification();
                    }
                }, 1000);
            }
        },

        stopCountdown() {
            this.countdownRunning = false;
            if (this.countdownInterval) {
                clearInterval(this.countdownInterval);
                this.countdownInterval = null;
            }
        },

        resetCountdown() {
            this.stopCountdown();
            if (this.activeItem) {
                this.initialCountdown = (this.activeItem.countdownMinutes || 30) * 60;
                this.activeCountdown = this.initialCountdown;
            } else {
                this.initialCountdown = 0;
                this.activeCountdown = 0;
            }
        },

        getActiveCountdownColor() {
            if (!this.activeItem || typeof this.activeCountdown === 'undefined') return 'text-white';
            if (this.activeCountdown <= 60) return 'text-red-400 animate-pulse';
            if (this.activeCountdown <= 300) return 'text-yellow-400';
            return 'text-white';
        },

        getActiveProgress() {
            if (!this.activeItem || !this.initialCountdown || this.initialCountdown === 0) return 0;
            const elapsed = this.initialCountdown - this.activeCountdown;
            return Math.min(100, Math.max(0, (elapsed / this.initialCountdown) * 100));
        },

        formatCountdown(seconds) {
            if (seconds === null || seconds === undefined || seconds < 0) return '00:00';
            const m = Math.floor(seconds / 60);
            const s = seconds % 60;
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        },

        isActive(item) {
            return this.activeItem && this.activeItem.id === item.id;
        },

        isPast(item) {
            return this.activeItem && this.activeItem.id !== item.id;
        },

        get sortedAgenda() {
            return [...this.agenda].sort((a, b) => {
                return this.timeToMinutes(a.startTime) - this.timeToMinutes(b.startTime);
            });
        },

        timeToMinutes(time) {
            if (!time) return 0;
            const [h, m] = time.split(':').map(Number);
            return h * 60 + m;
        },

        openAddModal() {
            this.editingId = null;
            this.form = { title: '', startTime: '', endTime: '', countdownMinutes: 30, playSound: true };
            this.showModal = true;
        },

        editItem(item) {
            this.editingId = item.id;
            this.form = {
                title: item.title,
                startTime: item.startTime || '',
                endTime: item.endTime || '',
                countdownMinutes: item.countdownMinutes || 30,
                playSound: item.playSound || false
            };
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.editingId = null;
        },

        saveItem() {
            // Ensure countdownMinutes is a number
            this.form.countdownMinutes = parseInt(this.form.countdownMinutes) || 30;
            
            if (this.editingId) {
                const index = this.agenda.findIndex(i => i.id === this.editingId);
                if (index !== -1) {
                    this.agenda[index] = {
                        ...this.agenda[index],
                        title: this.form.title,
                        startTime: this.form.startTime,
                        endTime: this.form.endTime,
                        countdownMinutes: this.form.countdownMinutes,
                        playSound: this.form.playSound
                    };
                    
                    // Update active item if it's the one being edited
                    if (this.activeItem && this.activeItem.id === this.editingId) {
                        this.activeItem = { ...this.agenda[index] };
                        this.initialCountdown = this.activeItem.countdownMinutes * 60;
                        this.activeCountdown = this.initialCountdown;
                    }
                }
            } else {
                this.agenda.push({
                    id: Date.now(),
                    title: this.form.title,
                    startTime: this.form.startTime,
                    endTime: this.form.endTime,
                    countdownMinutes: this.form.countdownMinutes,
                    playSound: this.form.playSound
                });
            }
            this.closeModal();
        },

        deleteItem(id) {
            if (confirm('حذف هذا العنصر من جدول الأعمال؟')) {
                this.agenda = this.agenda.filter(i => i.id !== id);
                if (this.activeItem && this.activeItem.id === id) {
                    this.activeItem = null;
                    this.stopCountdown();
                }
            }
        },

        playNotification() {
            if (this.audio) {
                this.audio.currentTime = 0;
                this.audio.play().catch(e => console.log('Audio play failed:', e));
            }
        },

        testSound() {
            this.audio = new Audio(this.soundUrl);
            this.audio.play().catch(e => alert('Failed to play sound. Check the URL.'));
        }
    }
}
</script>

</body>
</html>
