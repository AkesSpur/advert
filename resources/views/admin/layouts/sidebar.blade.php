<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="">
                {{ env('APP_NAME') }}
        </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="">||</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            {{-- <li class="dropdown {{setActive(['admin.dashboard'])}}">
                <a href="{{route('admin.dashboard')}}" class="nav-link"><i
                        class="fas fa-fire"></i><span>Dashboard</span></a>
            </li> --}}

            <li class="dropdown">
                <a href="/" class="nav-link"><i
                        class="fas fa-globe"></i><span>Site</span></a>
            </li>

            
            {{-- <li
                class="dropdown {{ setActive([
                'admin.category.*',
                'admin.section.*'
                ]) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-list"></i>
                    <span>Manage Categories</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.section.index']) }}"><a class="nav-link"
                        href="{{ route('admin.section.index') }}">View Sections</a>
                </li>
                    <li class="{{ setActive(['admin.category.index']) }}"><a class="nav-link"
                            href="{{ route('admin.category.index') }}">View Categories</a>
                    </li>
                </ul>
            </li> --}}


            
            <li
                class="dropdown {{ setActive([
                    'admin.customer.index',
                    'admin.manage-user.index',
                    'admin.admin-list.index',
                ]) }}">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-users"></i>
                    <span>Users</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive(['admin.customer.index']) }}"><a class="nav-link"
                            href="{{ route('admin.customer.index') }}">Customer list</a></li>
 
                    <li class="{{ setActive(['admin.admin-list.index']) }}"><a class="nav-link"
                            href="{{ route('admin.admin-list.index') }}">Admin Lists</a></li>

                    <li class="{{ setActive(['admin.manage-user.index']) }}"><a class="nav-link"
                            href="{{ route('admin.manage-user.index') }}">Manage user</a></li>

                </ul>
            </li>


            <li class="dropdown {{ setActive(['admin.neighborhoods.*']) }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-map-marker"></i>
        <span>Neighborhoods</span></a>
    <ul class="dropdown-menu">
        <li class="{{ setActive(['admin.neighborhoods.index']) }}"><a class="nav-link"
                href="{{ route('admin.neighborhoods.index') }}">View Neighborhoods</a>
        </li>
        <li class="{{ setActive(['admin.neighborhoods.create']) }}"><a class="nav-link"
                href="{{ route('admin.neighborhoods.create') }}">Create Neighborhood</a>
        </li>
    </ul>
</li>
<li class="dropdown {{ setActive(['admin.metro-stations.*']) }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-subway"></i>
        <span>Metro Stations</span></a>
    <ul class="dropdown-menu">
        <li class="{{ setActive(['admin.metro-stations.index']) }}"><a class="nav-link"
                href="{{ route('admin.metro-stations.index') }}">View Metro Stations</a>
        </li>
        <li class="{{ setActive(['admin.metro-stations.create']) }}"><a class="nav-link"
                href="{{ route('admin.metro-stations.create') }}">Create Metro Station</a>
        </li>
    </ul>
</li>
<li class="dropdown {{ setActive(['admin.services.*']) }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-cogs"></i>
        <span>Services</span></a>
    <ul class="dropdown-menu">
        <li class="{{ setActive(['admin.services.index']) }}"><a class="nav-link"
                href="{{ route('admin.services.index') }}">View Services</a>
        </li>
        <li class="{{ setActive(['admin.services.create']) }}"><a class="nav-link"
                href="{{ route('admin.services.create') }}">Create Service</a>
        </li>
    </ul>
</li>

<li class="dropdown {{ setActive(['admin.profiles.*']) }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-user-circle"></i>
        <span>Profiles</span></a>
    <ul class="dropdown-menu">
        <li class="{{ setActive(['admin.profiles.index']) }}"><a class="nav-link"
                href="{{ route('admin.profiles.index') }}">All Profiles</a>
        </li>
        <li class="{{ setActive(['admin.profiles.index', ['filter' => 'verified']]) }}"><a class="nav-link"
                href="{{ route('admin.profiles.index', ['filter' => 'verified']) }}">Verified Profiles</a>
        </li>
        <li class="{{ setActive(['admin.profiles.index', ['filter' => 'active']]) }}"><a class="nav-link"
                href="{{ route('admin.profiles.index', ['filter' => 'active']) }}">Active Profiles</a>
        </li>
        <li class="{{ setActive(['admin.profiles.index', ['filter' => 'disabled']]) }}"><a class="nav-link"
                href="{{ route('admin.profiles.index', ['filter' => 'disabled']) }}">Disabled Profiles</a>
        </li>
        <li class="{{ setActive(['admin.profiles.index', ['filter' => 'deleted']]) }}"><a class="nav-link"
                href="{{ route('admin.profiles.index', ['filter' => 'deleted']) }}">Deleted Profiles</a>
        </li>
    </ul>
</li>
<li class="dropdown {{ setActive(['admin.paid-services.*']) }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-dollar-sign"></i>
        <span>Paid Services</span></a>
    <ul class="dropdown-menu">
        <li class="{{ setActive(['admin.paid-services.index']) }}"><a class="nav-link"
                href="{{ route('admin.paid-services.index') }}">View Paid Services</a>
        </li>
        <li class="{{ setActive(['admin.paid-services.create']) }}"><a class="nav-link"
                href="{{ route('admin.paid-services.create') }}">Create Paid Service</a>
        </li>
    </ul>
</li>

<li class="{{ setActive(['admin.comments.*']) }}"><a class="nav-link" href="{{ route('admin.comments.index') }}">
    <i class="fas fa-comments"></i>
 <span>
    Comments
</span>   
</a>
</li>

<li class="{{ setActive(['admin.reviews.*']) }}"><a class="nav-link" href="{{ route('admin.reviews.index') }}">
    <i class="fas fa-star"></i>
 <span>
    Reviews
</span>   
</a>
</li>

<li class="{{ setActive(['admin.verifications.*']) }}"><a class="nav-link" href="{{ route('admin.verifications.index') }}">
    <i class="fas fa-check-circle"></i>
 <span>
    Verification
</span>   
</a>
</li>

<li class="{{ setActive(['admin.messenger.*']) }}">
    <a class="nav-link d-flex align-items-center justify-content-between" href="{{ route('admin.messenger.index') }}">
      
            <i class="fas fa-comment-dots"></i> <span>Chats</span>
      
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

<li class="menu-header">Settings & More</li>


            {{-- <li class="{{ setActive(['admin.support-info.*']) }}"><a class="nav-link" href="{{ route('admin.support-info.index') }}">
                <i class="fas fa-headset"></i>
             <span>
                Support Info
            </span>   
            </a>
            </li>

            <li class="{{ setActive(['admin.payment-settings.*']) }}"><a class="nav-link"
                href="{{ route('admin.payment-settings.index') }}"><i class="fas fa-cog"></i>
                <span>
                Payment Settings
                </span>
            </a></li>

 
                    <li><a class="nav-link {{ setActive(['admin.settings']) }}" href="{{ route('admin.settings.index') }}"><i class="fas fa-wrench"></i>
                    <span>Settings</span></a>
                   </li> --}}

        </ul>

    </aside>
</div>

