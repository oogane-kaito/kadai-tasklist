@if (Auth::check())
    <!-- {{-- ユーザー一覧ページへのリンク --}}
    <li><a class="link link-hover" href="#">Users</a></li>
    {{-- ユーザー詳細ページへのリンク --}}
    <li><a class="link link-hover" href="#">{{ Auth::user()->name }}&#39;s profile</a></li> -->
    <li class="divider lg:hidden"></li>
    <li><a class="link link-hover" href="{{ route('tasks.create') }}">>タスクの追加</a></li>
    {{-- ログアウトへのリンク --}}
    <li><a class="link link-hover" href="#" onclick="event.preventDefault();this.closest('form').submit();">Logout</a></li>
@else
    {{-- ユーザー登録ページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('register') }}">Signup</a></li>
    <li class="divider lg:hidden"></li>
    {{-- ログインページへのリンク --}}
    <li><a class="link link-hover" href="{{ route('login') }}">Login</a></li>
@endif