@extends('layouts.admin')

@section('content')
    <ol class="breadcrumb">
        <li><a href="/admin">Admin</a></li>
        <li><a href="/admin/articles">Articles</a></li>
        <li class="active">Edit draft</li>
    </ol>

    <form method="post" action="/admin/articles/{{ $article->id }}/submit" enctype="multipart/form-data">

    <div class="buttons">
        <div class="col-md-6 hidden-s hidden-xs">
            &nbsp;
        </div>
        <div class="col-md-6 text-right">
            @if ($current_user->isAdmin() or $current_user->isEditor())
                <button type="submit" formaction="/admin/articles/{{ $article->id }}/approve" class="btn btn-primary">Approve</button>
            @else
                <button type="submit" class="btn btn-primary">Submit</button>
            @endif
            <button type="submit" formaction="/admin/articles/{{ $article->id }}" class="btn btn-default">Save draft</button>
        </div>
    </div>

    <div class="col-md-8">
        <div class="homebox">
            <div class="body">
                <h3 class="underline"><label for="title">Title</label></h3>

                <div class="form-group">
                    <input type="text" value="{{ $article->title }}" class="form-control" name="title" id="title" placeholder="Title">
                </div>

                @if ($current_user->isAdmin() or $current_user->isEditor())
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" value="{{ $article->slug }}" class="form-control" name="slug" id="slug" placeholder="Slug">
                    </div>
                @endif
            </div>
        </div>

        <div class="homebox">
            <div class="body">
                <h3 class="underline"><label for="content">Content</label></h3>

                <div class="form-group">
                    <textarea id="content" name="content" class="form-control" rows="5">{{ $article->content }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="homebox">
            <div class="body">
                <h3 class="underline">Tags</h3>

                @if ($tags)
                <div class="form-group">
                    <ul class="tags">
                        @foreach ($tags as $tag)
                            <li><span><input type="checkbox" name="tags[]" value="{{ $tag->id }}">{{ $tag->name }}<span></li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

        <div class="homebox">
            <div class="body">
                <h3 class="underline">Photos</h3>

                @if ($article->photos)
                    <div class="photos">
                        @foreach ($article->photos as $photo)
                            <img src="http://geniussys.com/img/placeholder/blogpost-placeholder-100x100.png">
                        @endforeach
                    </div>
                @endif

                <hr>

                <div class="form-group">
                    <input type="file" name="photos[]"><br>
                    <input type="file" name="photos[]"><br>
                    <input type="file" name="photos[]"><br>
                    <input type="file" name="photos[]"><br>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="_METHOD" value="PUT">

    </form>
@stop

@section('scripts')
    @parent

    <script src="//cdn.ckeditor.com/4.5.6/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'content' );
    </script>
@stop
