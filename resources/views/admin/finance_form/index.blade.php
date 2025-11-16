        <x-app-layout>
            <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

            <div class="p-6" x-data="financeForm()">

                <!-- Form Header -->
                <div class="mb-4">
                    <label>اسم المشروع</label>
                    <input type="text" class="w-full border p-2" x-model="title">
                </div>

                <div class="grid grid-cols-2 gap-4">

                    <!-- LEFT: Available needs -->
                    <div>
                        <h3 class="text-lg font-bold mb-2">قائمة الاحتياجات</h3>
                        <ul id="needsList" class="border p-2 min-h-[200px]">
                            @foreach ($needs as $need)
                                <li class="p-2 bg-gray-100 mb-1 cursor-move" :data-id="{{ $need->id }}">
                                    {{ $need->name }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- RIGHT: Items added to this form -->
                    <div>
                        <h3 class="text-lg font-bold mb-2">احتياجات المشروع</h3>
                        <ul id="formItems" class="border p-2 min-h-[200px]"></ul>
                    </div>

                </div>

                <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded" @click="saveForm">
                    حفظ النموذج
                </button>

            </div>


            <script>
                function financeForm() {
                    return {
                        title: "",
                        selectedItems: [],

                        init() {
                            // Make lists draggable
                            new Sortable(needsList, {
                                group: 'shared',
                                animation: 150,
                            });

                            new Sortable(formItems, {
                                group: 'shared',
                                animation: 150,
                                onAdd: evt => {
                                    let needId = evt.item.dataset.id;
                                    this.addNeed(needId, evt.item);
                                }
                            });
                        },

                        addNeed(needId, element) {
                            // prevent duplicates
                            if (this.selectedItems.find(i => i.id == needId)) {
                                element.remove();
                                return;
                            }

                            this.selectedItems.push({
                                id: needId,
                                quantity: 1,
                                unit_price: 0
                            });

                            element.innerHTML = `
                <div class="flex items-center justify-between">
                    <span> ${element.innerText} </span>
                    <input type="number" class="border w-20 p-1" placeholder="الكمية">
                    <input type="number" class="border w-24 p-1" placeholder="السعر">
                </div>
            `;
                        },

                        saveForm() {
                            fetch("{{ route('admin.finance_form.store') }}", {
                                method: "POST",
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    title: this.title,
                                    items: this.selectedItems
                                })
                            }).then(() => {
                                alert("Saved!");
                                location.reload();
                            });
                        }
                    }
                }
            </script>

        </x-app-layout>
