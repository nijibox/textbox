@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @if($page->id)
        <h1>ページ編集</h1>
        @else
        <h1>新規ページ追加</h1>
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
                {{ Form::hidden('_pageId', $page->id) }}
                <div class="form-group">
                    <label class="control-label">タイトル</label>
                    <input class="form-control" type="text" name="pageTitle" placeholder="Title ?" value="{{$page->title}}">
                </div>
                <div class="form-group">
                    <label class="control-label">ヘッドライン</label>
                    <input class="form-control" type="text" name="pageHeadline" placeholder="Headline ?" value="{{$page->headline}}">
                </div>
                <!-- TODO: 表示枠は、当分の間header固定 -->
                <input class="form-control" type="hidden" name="pageLocation" value="header">
                <div class="form-group">
                    <label class="control-label">本文</label>
                    <textarea name="pageBody" data-provide="markdown" rows="10">{{$page->body}}</textarea>
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
