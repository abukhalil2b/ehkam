<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">المسابقات</h1>
        <a href="{{ route('admin.competitions.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            جديد
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">العنوان</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">الأسئلة</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">المشاركون</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">جديد</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراء</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($competitions as $competition)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $competition->title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $competition->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $competition->status === 'started' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $competition->status === 'finished' ? 'bg-blue-100 text-blue-800' : '' }}">
                                {{ ucfirst($competition->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $competition->questions_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $competition->participants_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $competition->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.competitions.show', $competition) }}" 
                               class="text-blue-600 hover:text-blue-900 mr-3">عرض</a>
                            |
                            <a href="{{ route('admin.competitions.edit', $competition) }}" 
                               class="text-yellow-600 hover:text-yellow-900 mr-3">تعديل العنوان</a>
                            @if($competition->status === 'finished')
                                |
                                <a href="{{ route('admin.competitions.results', $competition) }}" 
                                   class="text-green-600 hover:text-green-900">النتائج</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                           لم يتم العثور على أي مسابقات. أنشئ مسابقتك الأولى!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</x-app-layout>