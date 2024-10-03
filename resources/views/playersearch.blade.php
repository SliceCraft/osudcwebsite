<x-mainlayout>
    @if(isset($error))
        <div class="alert alert-danger" role="alert">
            {{$error}}
        </div>
    @endif
    <form method="POST">
        @csrf
        <div class="form-group">
            <label for="usernameInput">osu! user lookup</label>
            <input type="text" class="form-control" id="usernameInput" name="username" placeholder="Enter an osu userid or username">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</x-mainlayout>
