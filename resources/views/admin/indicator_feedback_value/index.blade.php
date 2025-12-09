<x-app-layout>
<div class="p-6">

    <h2 class="text-xl font-bold mb-4">قيم المؤشرات لسنة {{ $current_year }}</h2>

    <table class="w-full border text-center">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">المؤشر</th>

                @foreach($sectors as $sector)
                    <th class="border p-2">{{ $sector->short_name }}</th>
                @endforeach
            </tr>
        </thead>

        <tbody>
            @foreach($indicators as $indicator)
                <tr>
                    <td class="border p-2 font-semibold">{{ $indicator->title }}</td>

                    @foreach($sectors as $sector)
                        @php
                            // Find achieved value for this indicator + sector + year
                            $value = $indicator->indicatorFeedbackValues
                                ->where('sector_id', $sector->id)
                                ->first()
                                ->achieved ?? 0;
                        @endphp
                        <td class="border p-2">{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
</x-app-layout>
