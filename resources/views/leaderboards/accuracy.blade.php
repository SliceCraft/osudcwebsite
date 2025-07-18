<x-mainlayout>
    {{--  The style="align-items: center" is temporary  --}}
    <div class="relative overflow-x-auto shadow-md rounded-lg fixed flex flex-col justify-between lg:max-w-7xl -translate-x-1/2 w-[calc(100%-2rem)] left-1/2 top-28" style="align-items: center">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <caption
                class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                Average accuracy Leaderboard
                <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">The average accuracy across all daily challenges. Your average accuracy will be lower if you missed a daily challenge.</p>
            </caption>
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    #
                </th>
                <th scope="col" class="px-6 py-3">
                    Username
                </th>
                <th scope="col" class="px-6 py-3">
                    Average accuracy
                </th>
            </tr>
            </thead>
            <tbody>
                @foreach($leaderboard as $entry)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{$entry->placement}}
                    </th>
                    <td class="px-6 py-4">
                        <a href="{{route('userinfo', ['username' => $entry->user_id])}}">{{$entry->username}}</a>
                    </td>
                    <td class="px-6 py-4">
                        {{round($entry->average_accuracy, 3)}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <x-paginator :model="$leaderboard"></x-paginator>
    </div>
</x-mainlayout>
