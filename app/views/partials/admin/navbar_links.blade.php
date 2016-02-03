<li><a href="/admin/articles">Articles</a></li>
@if ($current_user->isAdmin())
    <li><a href="/admin/tags">Tags</a></li>
    <li><a href="/admin/users">Users</a></li>
@endif
