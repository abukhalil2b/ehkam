<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            إدارة تعيين الأنشطة للموظفين
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">تعيين نشاط لموظف</h3>

                    <form action="{{ route('activity_user_assign.store') }}" method="POST"
                        class="flex flex-col md:flex-row gap-4">
                        @csrf
                        <div class="flex-1">
                            <label for="activity_id" class="block text-sm font-medium text-gray-700 mb-1">النشاط</label>
                            <select name="activity_id" id="activity_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach($activities as $activity)
                                    <option value="{{ $activity->id }}">{{ $activity->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex-1">
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">الموظف (دائرة
                                التخطيط)</label>
                            <select name="user_id" id="user_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @if(empty($users))
                                    <option disabled>لا يوجد موظفين متاحين في دائرة التخطيط</option>
                                @else
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 h-10">
                                تعيين
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">قائمة الأنشطة والموظفين المعينين</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        النشاط
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        المشروع
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        الموظفين المعينين
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        إجراءات
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($activities as $activity)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">
                                                {{ $activity->project ? $activity->project->title : '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                @forelse($activity->employees as $employee)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $employee->name }}
                                                        <form action="{{ route('activity_user_assign.destroy') }}" method="POST"
                                                            class="inline ml-1"
                                                            onsubmit="return confirm('هل أنت متأكد من حذف هذا التعيين؟');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="activity_id" value="{{ $activity->id }}">
                                                            <input type="hidden" name="user_id" value="{{ $employee->id }}">
                                                            <button type="submit"
                                                                class="text-red-500 hover:text-red-700 focus:outline-none mr-1">
                                                                &times;
                                                            </button>
                                                        </form>
                                                    </span>
                                                @empty
                                                    <span class="text-sm text-gray-400">لا يوجد موظفين معينين</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('activity.show', $activity->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">عرض النشاط</a>
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