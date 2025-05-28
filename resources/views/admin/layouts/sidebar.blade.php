<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{route('admin.dashboard')}}">
               {{$settings->site_name}}
        </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="">||</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Панель управления</li>
            <li class="dropdown {{setActive(['admin.dashboard'])}}">
                <a href="{{route('admin.dashboard')}}" class="nav-link"><i
                        class="fas fa-fire"></i><span>Панель управления</span></a>
            </li>

            <li class="dropdown">
                <a href="/" class="nav-link"><i
                        class="fas fa-globe"></i><span>Сайт</span></a>
            </li>


            <li class="menu-header">Управление</li>


            <li
            class="dropdown {{ setActive([
                'admin.top-menu.*',
                'admin.footer-menu.*',
            ]) }}">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i>
                <span>Меню</span></a>
            <ul class="dropdown-menu">
                <li class="{{ setActive(['admin.top-menu.*']) }}"><a class="nav-link"
                        href="{{ route('admin.top-menu.index') }}">Верхнее меню</a></li>
                <li class="{{ setActive(['admin.footer-menu.*']) }}"><a class="nav-link"
                        href="{{ route('admin.footer-menu.index') }}">Нижнее меню</a></li>
            </ul>
        </li>

            <li class="dropdown {{ setActive(['admin.neighborhoods.*']) }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-map-marker"></i>
        <span>Районы</span></a>
    <ul class="dropdown-menu">
        <li class="{{ setActive(['admin.neighborhoods.index']) }}"><a class="nav-link"
                href="{{ route('admin.neighborhoods.index') }}">Просмотр районов</a>
        </li>
        <li class="{{ setActive(['admin.neighborhoods.create']) }}"><a class="nav-link"
                href="{{ route('admin.neighborhoods.create') }}">Создать район</a>
        </li>
    </ul>
</li>
<li class="dropdown {{ setActive(['admin.metro-stations.*']) }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-subway"></i>
        <span>Станции метро</span></a>
    <ul class="dropdown-menu">
        <li class="{{ setActive(['admin.metro-stations.index']) }}"><a class="nav-link"
                href="{{ route('admin.metro-stations.index') }}">Просмотр станций метро</a>
        </li>
        <li class="{{ setActive(['admin.metro-stations.create']) }}"><a class="nav-link"
                href="{{ route('admin.metro-stations.create') }}">Создать станцию метро</a>
        </li>
    </ul>
</li>
<li class="dropdown {{ setActive(['admin.services.*']) }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cogs"></i>
        <span>Услуги</span></a>
    <ul class="dropdown-menu">
        <li class="{{ setActive(['admin.services.index']) }}"><a class="nav-link"
                href="{{ route('admin.services.index') }}">Просмотр услуг</a>
        </li>
        <li class="{{ setActive(['admin.services.create']) }}"><a class="nav-link"
                href="{{ route('admin.services.create') }}">Создать услугу</a>
        </li>
    </ul>
</li>

<li class="dropdown {{ setActive(['admin.profiles.*']) }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user-circle"></i>
        <span>Профили</span></a>
    <ul class="dropdown-menu">
        <li class="{{ setActive(['admin.profiles.index']) }}"><a class="nav-link"
                href="{{ route('admin.profiles.index') }}">Все профили</a>
        </li>
        <li class="{{ setActive(['admin.profiles.index', ['filter' => 'verified']]) }}"><a class="nav-link"
                href="{{ route('admin.profiles.index', ['filter' => 'verified']) }}">Верифицированные профили</a>
        </li>
        <li class="{{ setActive(['admin.profiles.index', ['filter' => 'active']]) }}"><a class="nav-link"
                href="{{ route('admin.profiles.index', ['filter' => 'active']) }}">Активные профили</a>
        </li>
        <li class="{{ setActive(['admin.profiles.index', ['filter' => 'disabled']]) }}"><a class="nav-link"
                href="{{ route('admin.profiles.index', ['filter' => 'disabled']) }}">Отключенные профили</a>
        </li>
        <li class="{{ setActive(['admin.profiles.index', ['filter' => 'deleted']]) }}"><a class="nav-link"
                href="{{ route('admin.profiles.index', ['filter' => 'deleted']) }}">Удаленные профили</a>
        </li>
    </ul>
</li>
<li class="dropdown {{ setActive(['admin.paid-services.*']) }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-dollar-sign"></i>
        <span>Платные услуги</span></a>
    <ul class="dropdown-menu">
        <li class="{{ setActive(['admin.paid-services.index']) }}"><a class="nav-link"
                href="{{ route('admin.paid-services.index') }}">Просмотр платных услуг</a>
        </li>
        <li class="{{ setActive(['admin.paid-services.create']) }}"><a class="nav-link"
                href="{{ route('admin.paid-services.create') }}">Создать платную услугу</a>
        </li>
    </ul>
</li>


        <li class="dropdown {{ setActive(['admin.ad-tariffs.*']) }}"><a class="nav-link"
            href="{{ route('admin.ad-tariffs.index') }}"><i class="fas fa-ad"></i>
            <span>
                Тарифы на рекламу
            </span>
            </a>
    </li>

<li class="{{ setActive(['admin.comments.*']) }}">
    <a class="nav-link d-flex align-items-center justify-content-between" href="{{ route('admin.comments.index') }}">
        <div>
            <i class="fas fa-comments"></i>
            <span>Комментарии</span>
        </div>
        @php
            $pendingComments = \App\Models\Comment::where('approved', false)->count();
        @endphp
        @if($pendingComments > 0)
            <span class="badge rounded-circle bg-warning text-white d-flex align-items-center justify-content-center"
                  style="width: 24px; height: 24px;">
                {{ $pendingComments }}
            </span>
        @endif
    </a>
</li>

<li class="{{ setActive(['admin.reviews.*']) }}">
    <a class="nav-link d-flex align-items-center justify-content-between" href="{{ route('admin.reviews.index') }}">
        <div>
            <i class="fas fa-star"></i>
            <span>Отзывы</span>
        </div>
        @php
            $pendingReviews = \App\Models\Review::where('approved', false)->count();
        @endphp
        @if($pendingReviews > 0)
            <span class="badge rounded-circle bg-warning text-white d-flex align-items-center justify-content-center"
                  style="width: 24px; height: 24px;">
                {{ $pendingReviews }}
            </span>
        @endif
    </a>
</li>

<li class="{{ setActive(['admin.verifications.*']) }}"><a class="nav-link" href="{{ route('admin.verifications.index') }}">
    <i class="fas fa-check-circle"></i>
 <span>
    Верификация
    @php
    $pendingVerification = \App\Models\VerificationPhoto::where('status', 'pending')->count();
@endphp
@if($pendingVerification > 0)
    <span class="badge rounded-circle bg-warning text-white d-flex align-items-center justify-content-center"
          style="width: 24px; height: 24px;">
        {{ $pendingVerification }}
    </span>
@endif
</span>   
</a>
</li>

<li class="{{ setActive(['admin.messenger.*']) }}">
    <a class="nav-link d-flex align-items-center justify-content-between" href="{{ route('admin.messenger.index') }}">
      
            <i class="fas fa-comment-dots"></i> <span>Чаты</span>
      
        @php
            use App\Models\ChatMessage;

            $unreadAdminMessages = ChatMessage::whereHas('conversation', function ($q) {
                $q->where('admin_id', auth()->id());
            })
            ->whereNull('read_at') // unread
            ->where('receiver_id', auth()->id()) // admin must be the receiver
            ->count();
        @endphp

        @if($unreadAdminMessages > 0)
            <span class="badge rounded-circle bg-danger text-white d-flex align-items-center justify-content-center"
                  style="width: 24px; height: 24px;">
                {{ $unreadAdminMessages }}
            </span>
        @endif
    </a>
</li>



            <li class="dropdown {{ setActive(['admin.custom-category.*']) }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-tags"></i>
        <span> категории</span></a>
    <ul class="dropdown-menu">
        <li class="{{ setActive(['admin.custom-category.index']) }}"><a class="nav-link"
                href="{{ route('admin.custom-category.index') }}">Просмотр категорий</a>
        </li>
        <li class="{{ setActive(['admin.custom-category.create']) }}"><a class="nav-link"
                href="{{ route('admin.custom-category.create') }}">Создать категорию</a>
        </li>
    </ul>
</li>

<li
class="dropdown {{ setActive([
    'admin.customer.index',
    'admin.manage-user.index',
    'admin.admin-list.index',
    'admin.add-fund.*',
    'admin.withdraw-fund.*'
]) }}">
<a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i>
    <span>Пользователи</span></a>
<ul class="dropdown-menu">
    <li class="{{ setActive(['admin.admin-list.index']) }}"><a class="nav-link"
            href="{{ route('admin.admin-list.index') }}">Список администраторов</a></li>
    <li class="{{ setActive(['admin.customer.index']) }}"><a class="nav-link"
            href="{{ route('admin.customer.index') }}">Список клиентов</a></li> 
    <li class="{{ setActive(['admin.manage-user.index']) }}"><a class="nav-link"
            href="{{ route('admin.manage-user.index') }}">Управление пользователями</a></li>

</ul>
</li>
<li class="{{ setActive(['admin.transactions.*']) }}"><a class="nav-link" href="{{ route('admin.transactions.index') }}"><i class="fas fa-exchange-alt"></i><span>Транзакции</span></a></li>


            <li
                class="dropdown {{setActive([
                    'admin.filters.age.*',
                    'admin.filters.hair-color.*',
                    'admin.filters.height.*',
                    'admin.filters.weight.*',
                    'admin.filters.size.*',
                    'admin.filters.price.*',

                ])}}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-filter"></i> <span>Фильтры</span></a>
                <ul class="dropdown-menu">
                    <li class="{{setActive(['admin.filters.age.index', 'admin.filters.age.create', 'admin.filters.age.edit'])}}"><a class="nav-link" href="{{route('admin.filters.age.index')}}">Возраст</a></li>
                    <li class="{{setActive(['admin.filters.hair-color.index', 'admin.filters.hair-color.create', 'admin.filters.hair-color.edit'])}}"><a class="nav-link" href="{{route('admin.filters.hair-color.index')}}">Цвет волос</a></li>
                    <li class="{{setActive(['admin.filters.height.index', 'admin.filters.height.create', 'admin.filters.height.edit'])}}"><a class="nav-link" href="{{route('admin.filters.height.index')}}">Рост</a></li>
                    <li class="{{setActive(['admin.filters.weight.index', 'admin.filters.weight.create', 'admin.filters.weight.edit'])}}"><a class="nav-link" href="{{route('admin.filters.weight.index')}}">Вес</a></li>
                    <li class="{{setActive(['admin.filters.size.index', 'admin.filters.size.create', 'admin.filters.size.edit'])}}"><a class="nav-link" href="{{route('admin.filters.size.index')}}">Размер груди</a></li>
                    <li class="{{setActive(['admin.filters.price.index', 'admin.filters.price.create', 'admin.filters.price.edit'])}}"><a class="nav-link" href="{{route('admin.filters.price.index')}}">Цена</a></li>
                </ul>
            </li>

            <li class="menu-header">Настройки и другое</li>
            <li class="{{ setActive(['admin.seo_templates.*']) }}">
                <a class="nav-link" href="{{ route('admin.seo_templates.edit',['pageType' => 'profile']) }}">
                <i class="fas fa-star"></i>
             <span>
                Профиль SEO 
            </span>   
            </a>
            </li>


            <li class="dropdown {{setActive(['admin.settings.*', 'admin.hero-section-setting.*'])}}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cog"></i> <span>Настройки</span></a>
                <ul class="dropdown-menu">
                    <li class="{{setActive(['admin.settings.*'])}}"><a class="nav-link" href="{{route('admin.settings.index')}}">Общие настройки</a></li>
                    <li class="{{setActive(['admin.hero-section-setting.*'])}}"><a class="nav-link" href="{{route('admin.hero-section-setting.index')}}">Настройки секции Hero</a></li>
                    <li class="{{ setActive(['admin.hero-section-override.index']) }}"><a class="nav-link"
                            href="{{ route('admin.hero-section-override.index') }}">Замена текстов на страницах категори</a></li>
                </ul>
            </li>
            
        </ul>

    </aside>
</div>

