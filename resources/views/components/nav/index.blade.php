@php
    $links = ["projects" => "/","jobs" => "/jobs"];
@endphp
<header class="nav">
    <a class="logo" href="/">logo</a>
    <div class="links">
        @foreach($links as $key => $val)
            <a href="{{$val}}"
               class="link {{\Illuminate\Support\Facades\Request::path() === $val ? "link--active" : ""}}">{{$key}}</a>
        @endforeach
    </div>
    <div class="controls">
        <x-nav.search/>
        <span class="btn">
            <i class="far fa-bell"></i>
        </span>

        <div class="account"></div>
        <a class="create" href="/create">create</a>
        <span class="btn menu-btn">
            <i class="far fa-bars"></i>
            <i class="fal fa-times"></i>
        </span>
    </div>
    <div class="menu-mobile">
        <form>
            <label class="search">
                <span class="icon"><i class="far fa-search"></i></span>
                <input type="text" placeholder="Search">
            </label>
        </form>
        <div class="menu-mobile__links">
            @foreach($links as $key => $val)
                <a href="{{$val}}"
                   class="link {{\Illuminate\Support\Facades\Request::path() === $val ? "link--active" : ""}}">{{$key}}</a>
            @endforeach
        </div>
        <a href="/create" class="create">create</a>
        <div class="menu-mobile__account">
            <div class="avatar"></div>
            <div class="details">
                <p class="name">jhon smith</p>
                <a href="/settings" class="settings">settings</a>
            </div>
            <a href="/logout" class="logout"><i class="far fa-sign-out"></i></a>
        </div>
    </div>
    <script>
        const menuBtn = document.querySelector(".menu-btn");
        const menu = document.querySelector(".menu-mobile");

        menuBtn.addEventListener("click", () => {
            menuBtn.classList.toggle("menu-btn--open");
            menu.classList.toggle("menu-mobile--open");
        });

    </script>
</header>
