<x-layout>
    @push("styles")
        <link rel="stylesheet" href="{{asset("css/home.css")}}">
    @endpush
    <x-nav/>
    <div class="home">
        <div>filters and sort controls</div>
        <x-projects-list/>
        <button class="home__load">load more</button>
    </div>
</x-layout>
