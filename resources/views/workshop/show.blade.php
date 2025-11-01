<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $workshop->title }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-xl p-8 border border-gray-100">

                <h3 class="text-2xl font-bold text-blue-700 mb-6 border-b pb-2">
                    البيانات الأساسية للورشة
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 text-gray-700">

                    {{-- 1. Date --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> التاريخ:</strong>
                        {{ \Carbon\Carbon::parse($workshop->date)->isoFormat('dddd، D MMMM YYYY') }}
                    </p>

                    {{-- 2. Place --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> المكان:</strong>
                        {{ $workshop->place ?? 'غير محدد' }}
                    </p>

                    {{-- 3. Written By --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> كتب بواسطة:</strong>
                        <span class="text-blue-600">{{ $workshop->writtenBy->name ?? '—' }}</span>
                    </p>

                    {{-- 4. Creation Date (Optional but useful) --}}
                    <p>
                        <strong class="font-semibold text-gray-900"> تاريخ التسجيل:</strong>
                        {{ \Carbon\Carbon::parse($workshop->created_at)->diffForHumans() }}
                    </p>

                </div>

                <hr class="my-8 border-gray-200">

                <div class="mt-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">
                        قائمة الحضور ({{ $workshop->attendances->count() }})
                    </h3>

                    @if ($workshop->attendances->isEmpty())
                        <div class="bg-yellow-50 border-r-4 border-yellow-400 p-4 rounded-lg text-yellow-800">
                            لا يوجد حضور مسجل لهذه الورشة حتى الآن.
                        </div>
                    @else
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 list-disc pr-6 marker:text-blue-500">
                            @foreach ($workshop->attendances as $att)
                                <li class="text-gray-700 hover:text-gray-900 transition duration-150">
                                    <span class="font-medium">{{ $att->name }}</span>
                                    @if ($att->job_title)
                                        <span class="text-sm text-gray-500"> ({{ $att->job_title }})</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="mt-8 pt-4 border-t flex justify-end">
                    <a href="{{ route('workshop.edit', $workshop->id) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                        تعديل الورشة
                    </a>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
