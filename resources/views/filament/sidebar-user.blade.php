{{--
    User card at the top of the sidebar.

    Filament puts identity in a topbar avatar menu. The reference design instead
    opens the sidebar with who you are signed in as, which is also the more
    useful arrangement here: this panel has four roles with different
    permissions, so "which account am I?" is a question worth answering without
    a click. The topbar menu still exists for signing out.
--}}
@auth
    @php
        $user = filament()->auth()->user();
        $role = str($user->role ?? '')->replace('_', ' ')->title()->toString();
    @endphp

    <div class="fastora-sidebar-user">
        <span class="fastora-sidebar-user-avatar" aria-hidden="true">
            {{ str($user->name)->trim()->explode(' ')->take(2)->map(fn ($p) => str($p)->substr(0, 1))->implode('') }}
        </span>

        <span class="fastora-sidebar-user-text">
            <span class="fastora-sidebar-user-name">{{ $user->name }}</span>
            <span class="fastora-sidebar-user-meta">{{ $role ?: $user->email }}</span>
        </span>
    </div>
@endauth
