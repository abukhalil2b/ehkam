<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8" x-data="{ photoName: null, photoPreview: null }">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            {{-- Header with gradient --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 h-32 md:h-48 relative">
                <div class="absolute -bottom-16 right-6 md:right-12">
                    <div class="relative">
                        {{-- Profile Photo --}}
                        <div
                            class="h-32 w-32 md:h-40 md:w-40 rounded-full border-4 border-white bg-white shadow-lg overflow-hidden relative group">
                            <!-- Current Profile Photo -->
                            <img x-show="!photoPreview" src="{{ auth()->user()->avatar_url }}"
                                alt="{{ auth()->user()->name }}" class="h-full w-full object-cover">
                            <!-- New Profile Photo Preview -->
                            <span x-show="photoPreview" class="block h-full w-full bg-cover bg-no-repeat bg-center"
                                x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                            </span>

                            <!-- Overlay for upload -->
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                                @click="document.getElementById('photo').click()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-20 px-6 md:px-12 pb-10">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">{{ auth()->user()->name }}</h1>
                    <p class="text-gray-500">{{ auth()->user()->email }}</p>
                    @if(auth()->user()->activeRole)
                        <span
                            class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800 mt-2">
                            {{ auth()->user()->activeRole->name }}
                        </span>
                    @endif
                </div>

                <form action="{{ route('user_profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Hidden File Input --}}
                    <input type="file" id="photo" name="avatar" class="hidden" x-ref="photo" x-on:change="
                                photoName = $refs.photo.files[0].name;
                                const reader = new FileReader();
                                reader.onload = (e) => {
                                    photoPreview = e.target.result;
                                    // Update the preview image in the header as well
                                    // (This is a simplified way, essentially we duplicate logic or rely on a shared store if complex)
                                    // ideally the top component should listen to this change. 
                                    // For simplicity, we assume generic Alpine scope or just reload on save.
                                    // Actually, let's just make the top part part of this form x-data scope. Yes.
                                };
                                reader.readAsDataURL($refs.photo.files[0]);
                           ">

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        {{-- Name --}}
                        <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">الاسم الكامل</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Email --}}
                        <div class="sm:col-span-3">
                            <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                            <div class="mt-1">
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', auth()->user()->email) }}"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Password Section (Optional) --}}
                        <div class="sm:col-span-6 pt-6 border-t border-gray-100 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">تغيير كلمة المرور</h3>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="password" class="block text-sm font-medium text-gray-700">كلمة المرور
                                الجديدة</label>
                            <div class="mt-1">
                                <input type="password" name="password" id="password"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">اتركه فارغاً إذا كنت لا تريد تغييره.</p>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">تأكيد
                                كلمة المرور</label>
                            <div class="mt-1">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 flex justify-end">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            حفـــظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>