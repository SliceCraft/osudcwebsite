<x-mainlayout>
    <div
        class="relative overflow-x-auto shadow-md rounded-lg fixed flex flex-col justify-between lg:max-w-7xl -translate-x-1/2 w-[calc(100%-2rem)] left-1/2 top-28 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 mb-5">
        <div class="flex justify-end px-4 pt-4">
        </div>
        <div class="flex flex-col items-center pb-10">
            <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="https://a.ppy.sh/{{$player->user_id}}" />
            <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white"><a href="https://osu.ppy.sh/users/{{$player->user_id}}" target="_blank">{{$player->username}}</a></h5>
        </div>
    </div>

    <div
        class="relative overflow-x-auto shadow-md rounded-lg fixed flex flex-col justify-between lg:max-w-7xl -translate-x-1/2 w-[calc(100%-2rem)] left-1/2 top-28 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700 mb-5">
        <dl
            class="grid max-w-screen-xl grid-cols-2 gap-8 p-4 mx-auto text-gray-900 sm:grid-cols-3 xl:grid-cols-6 dark:text-white sm:p-8">
            <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">{{number_format($player->completed_daily_challenges)}}</dt>
                <dd class="text-gray-500 dark:text-gray-400">Completed daily challenges</dd>
            </div>
            <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">{{round($player->average_accuracy, 3)}}</dt>
                <dd class="text-gray-500 dark:text-gray-400">Average accuracy</dd>
            </div>
            <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">{{number_format($player->total_attempts)}}</dt>
                <dd class="text-gray-500 dark:text-gray-400">Total attempts</dd>
            </div>
            <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">{{number_format($player->current_streak)}}</dt>
                <dd class="text-gray-500 dark:text-gray-400">Current streak</dd>
            </div>
            <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">{{number_format($player->total_score)}}</dt>
                <dd class="text-gray-500 dark:text-gray-400">Total score</dd>
            </div>
            <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">{{number_format($player->average_placement)}}</dt>
                <dd class="text-gray-500 dark:text-gray-400">Average placement</dd>
            </div>
        </dl>
    </div>


    <div
        class="relative overflow-x-auto shadow-md rounded-lg fixed flex flex-col justify-between lg:max-w-7xl -translate-x-1/2 w-[calc(100%-2rem)] left-1/2 top-28">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Daily challenge
                </th>
                <th scope="col" class="px-6 py-3">
                    Score
                </th>
                <th scope="col" class="px-6 py-3">
                    Accuracy
                </th>
                <th scope="col" class="px-6 py-3">
                    Attempts
                </th>
                <th scope="col" class="px-6 py-3">
                    Final placement
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($dailychallenges as $entry)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{number_format($entry->daily_challenge)}}
                    </th>
                    <td class="px-6 py-4">
                        {{number_format($entry->score)}}
                    </td>
                    <td class="px-6 py-4">
                        {{$entry->accuracy}}
                    </td>
                    <td class="px-6 py-4">
                        {{number_format($entry->attempts)}}
                    </td>
                    <td class="px-6 py-4">
                        {{number_format($entry->placement)}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-mainlayout>
