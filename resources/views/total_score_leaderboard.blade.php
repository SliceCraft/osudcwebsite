<!doctype html>
<html lang="en">
    <head>
        <title>Cover Template · Bootstrap v5.0</title>

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="text-center text-white bg-dark">
        <div class="cover-container d-flex p-3 mx-auto flex-column">
            <header class="mb-auto">
                <div>
                    <h3 class="float-md-start mb-0">osu!dailychallenge stats</h3>
                </div>
            </header>
            @if($players_running_in_job > 0)
                <div class="alert alert-warning main-margin" role="alert">
                    Player data is currently being recalculated, data might not be accurate! Players left being recalculated: {{ $players_running_in_job }}
                </div>
            @endif
            @if($placements_running_in_job > 0)
                <div class="alert alert-warning main-margin" role="alert">
                    Placement data is currently being recalculated, placement data might not be accurate! Scores left being recalculated: {{ $placements_running_in_job }}
                </div>
            @endif
        </div>

        <main class="px-3 main-margin seventy-percent center">
            <table class="table text-white table-bordered shadow p-3 mb-5 rounded">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">Total attempts</th>
                </tr>
                </thead>
                <tbody>
                @foreach($leaderboard as $entry)
                    <tr>
                        <th scope="row">{{$entry->placement}}</th>
                        <td><a href="https://osu.ppy.sh/users/{{$entry->user_id}}" style="color: white;">{{$entry->username}}</a></td>
                        <td>{{$entry->total_attempts}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="center mb-5" style="max-width: fit-content; max-height: fit-content;">
                {{$leaderboard->links()}}
            </div>
        </main>
        <footer class="py-3">
            <!-- To the people that find this, you don't want to know how many things we tried to get the bottom to work
             Nothing seemed to work except this, so deal with it -->
        </footer>
    </body>
</html>
