<x-layout>
    @push("styles")
        <link rel="stylesheet" href="{{asset("css/project.css")}}">
    @endpush

    <x-nav/>
    <div class="project">
        <div class="header">
            <a href="#" class="avatar"><img
                    src="https://cdn.dribbble.com/users/12947/avatars/normal/2cc07a586c7b7d71efed54888edd7981.jpg?1613497764"
                    alt=""></a>
            <div class="details">
                <p class="title">Brainstorm</p>
                <p class="user">Jhon Smith</p>
            </div>
            <div class="controls">
                <button class="like"><i class="fas fa-heart"></i>like</button>
            </div>
        </div>
        <div class="main">
            <div class="previews">
                @php
                    $img = "https://cdn.dribbble.com/userupload/2400357/file/original-4b53148ec031dde2d0cf24b04ce57f34.png?filters:format(webp)?filters%3Aformat%28webp%29=&compress=1&resize=1600x1200";
                @endphp
                <div class="previews_main"><img
                        src="{{$img}}"
                        alt=""></div>
                <div class="previews_other">

                    @for($i = 0; $i < 4; $i++)
                        <div class="card {{!$i ? "card-selected" : ""}}"><img src="{{$img}}" alt=""></div>
                    @endfor

                </div>
            </div>
            <div class="description">project description</div>
        </div>
    </div>
</x-layout>
