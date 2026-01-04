<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                إعدادات المشاريع للسنة {{ $selectedYear }}
            </h2>

            <!-- Year Selector -->
            <div class="flex flex-wrap gap-2">
                @foreach ($availableYears as $year)
                    <a href="{{ route('admin_setting.project.index', ['year' => $year]) }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition
                            {{ $year == $selectedYear ? 'bg-blue-600 text-white shadow' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ $year }}
                    </a>
                @endforeach
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg">
                <div class="p-6">

                    @forelse ($projects as $project)
                        <div class="border-b last:border-b-0 py-4 flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $project->title }}
                                </h3>
                                <div class="text-xs">
                                    {{ $project->indicator->title }}  - {{ $project->current_year }}
                                </div>
                            </div>

                            <!-- Placeholder for future actions -->
                            <div class="flex gap-2">
                                <a href="{{  route('project.edit',$project->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                    تعديل
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            لا توجد مؤشرات مسجلة لهذه السنة
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
