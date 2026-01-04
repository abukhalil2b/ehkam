<x-sect-layout title="بيانات المؤشر">

    <!-- Main Content -->
    <div class="container py-4 mx-auto px-4 sm:px-4">
        <div class="text-xl font-bold mb-4">
            حصر إسهامات (المديريات والإدارات الإقليمية للأوقاف والشؤون الدينية) في تحقيق مستهدفات المؤشرات الرئيسية
            للوزارة
        </div>
        <div class="text-2xl font-bold mb-4">
            {{ $sector->name }}
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            @foreach ($aims as $aim)
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="text-blue-600 mb-1">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="text-xl text-gray-600 font-medium">
                        {{ $aim->title }}
                    </div>
                    <a class="block mt-4 px-3 py-2 text-center text-sm rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 transition-colors"
                        href="{{ route('aim_sector_feedback.index', $aim->id) }}">
                        بيانات المؤشر
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</x-sect-layout>
