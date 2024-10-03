<x-mainlayout>
    <h1>Some of the recorded stats</h1>
    <p class="lead">Total amount of users completing a daily challenge: {{ number_format($player_count) }}</p>
    <p class="lead">Total amount of leaderboard entries: {{ number_format($total_lb_entries) }}</p>
    <p class="lead">Total amount of players that played every day: {{ number_format($total_maxed_dc) }}</p>
    <p class="lead">Total attempts across all players: {{ number_format($total_attempts) }}</p>
    <p class="lead">Total score across all players: {{ number_format($total_score) }}</p>
</x-mainlayout>
