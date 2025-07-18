<x-mainlayout>
    <div
        class="fixed flex flex-col md:flex-row justify-between w-[calc(100%-2rem)] p-4 -translate-x-1/2 lg:max-w-7xl left-1/2 top-[178px] bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700" style="margin-top: 50px">
        <h5 class="mb-3 text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
            Some of the recorded stats
        </h5>
        <dl class="grid max-w-screen-xl grid-cols-3 gap-8 p-4 mx-auto text-gray-900 dark:text-white sm:p-8">
            <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">{{ number_format($player_count) }}</dt>
                <dd class="text-gray-500 dark:text-gray-400">Total amount of users completing a daily challenge</dd>
            </div>
            <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">{{ number_format($total_lb_entries) }}</dt>
                <dd class="text-gray-500 dark:text-gray-400">Total amount of leaderboard entries</dd>
            </div>
            <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">{{ number_format($total_maxed_dc) }}</dt>
                <dd class="text-gray-500 dark:text-gray-400">Total amount of players that played every day</dd>
            </div>
            <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">{{ number_format($total_attempts) }}</dt>
                <dd class="text-gray-500 dark:text-gray-400">Total attempts across all players</dd>
            </div>
            <div class="flex flex-col items-center justify-center">
                <dt class="mb-2 text-3xl font-extrabold">{{ number_format($total_score) }}</dt>
                <dd class="text-gray-500 dark:text-gray-400">Total score across all players</dd>
            </div>
        </dl>
    </div>
</x-mainlayout>
