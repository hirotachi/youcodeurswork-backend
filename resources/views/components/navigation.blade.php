<header class="navigation">
    @push("styles")
        <link rel="stylesheet" href="{{asset("css/navigation.css")}}">
    @endpush
    <p class="logo">logo</p>
    <div class="links">
        <a class="link" href="/">projects</a>
        <a class="link" href="/jobs">jobs</a>
    </div>
        <div class="controls">
            <div>search</div>
            <div>notifications</div>
            <div>account dropdown</div>
            <a class="create" href="/create">create</a>
        </div>
</header>
