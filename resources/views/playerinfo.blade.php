<x-mainlayout>
    <h1>{{$player->username}} {{$player->user_id}}</h1>
    <table class="table text-white table-bordered shadow p-3 mb-5 rounded">
        <thead>
        <tr>
            <th scope="col">Daily challenge</th>
            <th scope="col">Score</th>
            <th scope="col">Accuracy</th>
            <th scope="col">Attempts</th>
            <th scope="col">Final placement</th>
        </tr>
        </thead>
        <tbody>
        @foreach($dailychallenges as $entry)
            <tr>
                <th scope="row">{{$entry->daily_challenge + 1}}</th>
                <th scope="row">{{$entry->score}}</th>
                <th scope="row">{{$entry->accuracy}}</th>
                <th scope="row">{{$entry->attempts}}</th>
                <th scope="row">{{$entry->placement}}</th>
            </tr>
        @endforeach
        </tbody>
    </table>
</x-mainlayout>
