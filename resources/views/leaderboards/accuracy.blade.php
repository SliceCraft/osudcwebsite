<x-mainlayout>
    <h1>Average accuracy Leaderboard</h1>
    <p>Your average accuracy will be lower if you missed a daily challenge</p>
    <table class="table text-white table-bordered shadow p-3 mb-5 rounded">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Username</th>
            <th scope="col">Average accuracy</th>
        </tr>
        </thead>
        <tbody>
        @foreach($leaderboard as $entry)
            <tr>
                <th scope="row">{{$entry->placement}}</th>
                <td><a href="{{route('userinfo', ['username' => $entry->user_id])}}" style="color: white;">{{$entry->username}}</a></td>
                <td>{{round($entry->average_accuracy, 3)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="center mb-5" style="max-width: fit-content; max-height: fit-content;">
        {{$leaderboard->links()}}
    </div>
</x-mainlayout>
