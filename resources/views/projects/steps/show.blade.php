<x-app-layout>
    <div class="container py-8 mx-auto px-4">
        <div class="grid grid-cols-1 gap-4">
            <div class="bg-white rounded-xl shadow p-4 border">
                <div class="mb-2">
                    <h3 class="text-base font-semibold text-gray-900">
                        إعداد قاعدة بيانات الأنظمة الألكترونية والخدمات
                    </h3>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2 text-sm text-gray-700">
                    <div>
                        <span class="font-medium text-gray-500">من:</span>
                        <div class="mt-1">01-01-2025</div>
                    </div>
                    <div>
                        <span class="font-medium text-gray-500">إلى:</span>
                        <div class="mt-1">29-03-2025</div>
                    </div>
                    <div>
                        <span class="font-medium text-gray-500">المستهدف:</span>
                        <div class="mt-1">12.54%</div>
                    </div>
                    <div>
                        <span class="font-medium text-gray-500">الحالة:</span>
                        <div class="mt-1">
                            <span
                                class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                متأخر
                            </span>
                        </div>
                    </div>
                    <div>
                        <span class="font-medium text-gray-500">عدد المهام:</span>
                        <div class="mt-1">0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<div class="bg-gray-50 p-6" x-data="{ showModal: false }">

    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-700">📋 قائمة المهام</h2>
            <button @click="showModal = true"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                + إضافة مهمة
            </button>
        </div>

        <!-- 🗂️ Dummy Task Cards -->
        <div class="grid gap-4">
            <div class="bg-white rounded-xl shadow p-4 border flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <p class="font-semibold text-gray-800">تصميم قاعدة بيانات للمشروع</p>
                    <p class="text-sm text-gray-500 mt-1">المستخدم: علي</p>
                    <p class="text-sm text-gray-500">من: 2025-06-10 إلى: 2025-06-20</p>
                </div>
                <div class="mt-3 sm:mt-0">
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">جاري التنفيذ</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-4 border flex flex-col sm:flex-row justify-between items-start sm:items-center">
                <div>
                    <p class="font-semibold text-gray-800">إعداد تقرير الأداء الشهري</p>
                    <p class="text-sm text-gray-500 mt-1">المستخدم: ناصر</p>
                    <p class="text-sm text-gray-500">من: 2025-06-01 إلى: 2025-06-15</p>
                </div>
                <div class="mt-3 sm:mt-0">
                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">متأخر</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 📝 Modal for New Task -->
    <div x-show="showModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50" x-cloak>
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">إضافة مهمة جديدة</h3>
                <button @click="showModal = false" class="text-gray-500 hover:text-red-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form class="space-y-4">
                <div>
                    <label class="block mb-1 text-sm text-gray-600">المهمة</label>
                    <input type="text" name="task" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" placeholder="مثال: إعداد خطة العمل">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1 text-sm text-gray-600">تاريخ البداية</label>
                        <input type="date" name="start_at" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block mb-1 text-sm text-gray-600">تاريخ الانتهاء</label>
                        <input type="date" name="due_date" class="w-full border rounded px-3 py-2">
                    </div>
                </div>

                <div>
                    <label class="block mb-1 text-sm text-gray-600">المنفذ</label>
                    <select name="user_id" class="w-full border rounded px-3 py-2">
                        <option value="1">علي</option>
                        <option value="2">ناصر</option>
                        <option value="3">خليفة</option>
                    </select>
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">إسناد المهمة</button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
