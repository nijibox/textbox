@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @if($article->id)
        <h1>記事編集</h1>
        @else
        <h1>新規投稿追加</h1>
        @endif
        <div class="col-md-10">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="post">
                {!! csrf_field() !!}
                {{ Form::hidden('_articleId', $article->id) }}
                <div class="form-group">
                    <input class="form-control" type="text" name="articleTitle" placeholder="Title ?" value="{{$article->title}}">
                </div>
                <div class="form-group">
                    <textarea name="articleBody" data-provide="markdown" rows="10">{{$article->body}}</textarea>
                </div>
                <div class="form-group">
                    <label class="radio-inline">
                        {{ Form::radio('articleStatus', 'draft', $article->status == 'draft', ['id' => 'articleStatusDraft']) }}
                        下書きのまま
                    </label>
                    <label class="radio-inline">
                        {{ Form::radio('articleStatus', 'internal', $article->status == 'internal', ['id' => 'articleStatusInternal']) }}
                        社内公開する
                    </label>
                </div>
                <div class="text-right">
                    <button type="submit" class="btn btn-primary">保存する</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page_css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown/2.10.0/css/bootstrap-markdown.min.css" rel="stylesheet">
@endsection

@section('page_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/marked/0.3.5/marked.min.js"></script>
<script>
marked.setOptions({
    gfm: true,
    tables: false,
    breaks: true,
    pedantic: false,
    sanitize: false,
    smartLists: false,
    smartypants: false});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown/2.10.0/js/bootstrap-markdown.min.js"></script>
@endsection
