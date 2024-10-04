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

    <body class="text-center text-white bg-dark min-100-height">
        <nav class="navbar navbar-expand-lg navbar-light bg-dark">
            <a class="navbar-brand" href="{{route('home')}}" style="color: white">osu!daily challenge stats</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route("userinfo")}}" style="color: white">User lookup</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white">
                            Leaderboards
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="{{route('leaderboard', ['leaderboard' => 'score'])}}">Total score</a>
                            <a class="dropdown-item" href="{{route('leaderboard', ['leaderboard' => 'attempts'])}}">Total attempts</a>
                            <a class="dropdown-item" href="{{route('leaderboard', ['leaderboard' => 'placement'])}}">Average placement</a>
                            <a class="dropdown-item" href="{{route('leaderboard', ['leaderboard' => 'accuracy'])}}">Average accuracy</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="px-3 main-margin seventy-percent center">
            {{ $slot }}
        </main>
        <footer class="py-3">
            <!-- To the people that find this, you don't want to know how many things we tried to get the bottom to work
             Nothing seemed to work except this, so deal with it -->
        </footer>
    </body>
</html>
