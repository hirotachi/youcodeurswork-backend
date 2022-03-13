<div class="projects">
    @for($i = 0; $i < 6; $i++)
        <div class="card">
            <div class="card__preview">
                <img
                    src="https://cdn.dribbble.com/users/12947/screenshots/14387128/media/6a8310a77fb323a4ddc0813a532f617c.jpg?compress=1&resize=1200x900&vertical=top"
                    alt="">
            </div>
            <div class="card__details">
                <a href="#" class="avatar"><img
                        src="https://cdn.dribbble.com/users/1804127/avatars/mini/1ad544503482c0961a5507555b6e1e90.jpg?1615983637"
                        alt=""></a>
                <a href="#" class="name">Jhon Smith</a>
                <div class="more">
                    <div class="likes"><span class="like"><i class="fas fa-heart "></i></span> 25</div>
                </div>
            </div>
        </div>
    @endfor
</div>
