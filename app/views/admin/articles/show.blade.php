@extends('layouts.admin')

@section('content')
    <ol class="breadcrumb">
        <li><a href="/admin">Admin</a></li>
        <li><a href="/admin/articles">Articles</a></li>
        <li class="active">{{ $article->title }}</li>
    </ol>

    <div class="buttons">
        <div class="col-md-6">
            <a href="/admin/articles/{{ $article->id }}/edit" class="btn btn-default">Edit</a>

            <form id="deleteUser" method="POST" action="/admin/articles/{{ $article->id }}">
                <input type="hidden" name="_METHOD" value="DELETE">
                <button type="submit" class="btn btn-default">Delete</button>
            </form>
        </div>

        <div class="col-md-6 text-right">
            @if ($current_user and ($current_user->isAdmin() or $current_user->isEditor()))
                <form id="deleteUser" method="POST" action="/admin/articles/{{ $article->id }}/approve">
                    <input type="hidden" name="_METHOD" value="PUT">
                    <button type="submit" class="btn btn-primary">Approve</button>
                </form>
            @endif
        </div>
    </div>

    <table class="table table-striped">
        <tr>
            <th width="20%">Title</th>
            <td>{{ $article->title }}</td>
        </tr>
        <tr>
            <th>Slug</th>
            <td>{{ $article->slug }}</td>
        </tr>
        <tr>
            <th>Content</th>
            <td>{!! $article->content !!}</td>
        </tr>
        <tr>
            <th>Tags</th>
            <td>
                @if ($article->tags)
                    @foreach ($article->tags as $tag)
                        <span class="tag">{{ $tag->name }}</span>
                    @endforeach
                @endif
            </td>
        </tr>
        <tr>
            <th>Photos</th>
            <td>
                @if ($article->photos)
                    <div class="photos">
                        @foreach ($article->photos as $photo)
                            <img src="http://geniussys.com/img/placeholder/blogpost-placeholder-100x100.png">
                        @endforeach
                    </div>
                @endif
            </td>
        </tr>
        <tr>
            <th>Type</th>
            <td>{{ $article->type }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $article->status }}</td>
        </tr>
        <tr>
            <th>Data created</th>
            <td>{{ $article->created_at }}</td>
        </tr>
    </table>
@stop
