<x-mainlayout>
    <a href="https://osu.ppy.sh/users/{{$player->user_id}}" style="color: white;">
        <h1 class="mb-5">{{$player->username}}</h1>
    </a>
    <ul class="list-group mb-5 transparent-list-group">
        <li class="list-group-item" data-bs-theme="dark">Completed daily challenges: {{number_format($player->completed_daily_challenges)}}</li>
        <li class="list-group-item">Average accuracy: {{$player->average_accuracy}}</li>
        <li class="list-group-item">Total attempts: {{number_format($player->total_attempts)}}</li>
        <li class="list-group-item">Current streak: {{number_format($player->current_streak)}}</li>
        <li class="list-group-item">Total score: {{number_format($player->total_score)}}</li>
        <li class="list-group-item">Average placement: {{number_format($player->average_placement)}}</li>
    </ul>
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
