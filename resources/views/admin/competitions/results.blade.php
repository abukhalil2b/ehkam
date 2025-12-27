<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" dir="rtl">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $competition->title }}</h1>
                <p class="text-gray-600 mt-1">نتائج المسابقة</p>
            </div>
            <a href="{{ route('admin.competitions.show', $competition) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                العودة للمسابقة
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="mr-4">
                    <h3 class="text-sm font-medium text-gray-600">إجمالي الأسئلة</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_questions'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div class="mr-4">
                    <h3 class="text-sm font-medium text-gray-600">إجمالي المشاركين</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_participants'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div class="mr-4">
                    <h3 class="text-sm font-medium text-gray-600">معدل الإكمال</h3>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $stats['total_participants'] > 0 ? round(($participants->count() / $stats['total_participants']) * 100) : 0 }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">🏆 أفضل المؤدين</h2>
                <div class="space-y-3">
                    @foreach($leaderboard as $index => $participant)
                        <div class="flex items-center p-3 rounded-lg {{ $index === 0 ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50' }}">
                            <div class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-full font-bold
                                {{ $index === 0 ? 'bg-yellow-400 text-yellow-900' : '' }}
                                {{ $index === 1 ? 'bg-gray-300 text-gray-700' : '' }}
                                {{ $index === 2 ? 'bg-orange-300 text-orange-900' : '' }}
                                {{ $index > 2 ? 'bg-gray-200 text-gray-600' : '' }}">
                                {{ $index + 1 }}
                            </div>
                            <div class="mr-3 flex-1">
                                <div class="font-semibold text-gray-900">{{ $participant['name'] }}</div>
                                <div class="text-sm text-gray-600">
                                    {{ $participant['correct'] }}/{{ $stats['total_questions'] }} إجابات صحيحة
                                </div>
                            </div>
                            <div class="text-xl font-bold text-blue-600">{{ $participant['score'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">جميع المشاركين</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الترتيب</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">النقاط</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">صحيح</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">خاطئ</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">الدقة</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($participants as $index => $participant)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $participant['name'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-lg font-bold text-blue-600">{{ $participant['score'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-green-600 font-medium">{{ $participant['correct'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-red-600 font-medium">{{ $participant['incorrect'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $stats['total_questions'] > 0 ? round(($participant['correct'] / $stats['total_questions']) * 100) : 0 }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>