<x-mainlayout>
    @if(isset($error))
        <div id="marketing-banner"
             class="relative fixed z-50 flex flex-col md:flex-row justify-between w-[calc(100%-2rem)] p-4 -translate-x-1/2 bg-white border border-gray-100 rounded-lg shadow-xs lg:max-w-7xl left-1/2 top-[88px] dark:bg-gray-700 dark:border-gray-600">
            <div class="flex flex-col items-start mb-3 me-4 md:items-center md:flex-row md:mb-0">
                <a class="flex items-center mb-2 border-gray-200 md:pe-4 md:me-4 md:border-e md:mb-0 dark:border-gray-600">
                    <span class="self-center text-lg font-semibold whitespace-nowrap dark:text-white">Error</span>
                </a>
                <p class="flex items-center text-sm font-normal text-gray-500 dark:text-gray-400">{{$error}}</p>
            </div>
        </div>
        <div class="alert alert-danger" role="alert">

        </div>
    @endif
    <div
        class="fixed flex flex-col justify-between p-4 -translate-x-1/2 w-[calc(100%-2rem)] max-w-sm left-1/2 top-[178px] bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700" style="margin-top: 130px">
        <form class="space-y-6" method="POST">
            @csrf
            <h5 class="text-xl font-medium text-gray-900 dark:text-white">osu! user lookup</h5>
            <div>
                <input type="text" name="username" id="text"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                       placeholder="Enter an osu userid or username" required />
            </div>
            <button type="submit"
                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
        </form>
    </div>
</x-mainlayout>
