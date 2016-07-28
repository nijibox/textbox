@extends('layouts.2paned')

@section('content.main')
@if($article->id)
<h1>記事編集</h1>
@else
<h1>新規投稿追加</h1>
@endif

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
        <label class="control-label">タイトル</label>
        <input class="form-control" type="text" name="articleTitle" placeholder="Title ?" value="{{$article->title}}">
    </div>
    <div class="form-group">
        <label class="control-label">タグ</label>
        <input class="form-control" type="text" name="articleTags" data-role="tagsinput" value="{{$article->tagsForInput()}}">
    </div>
    <div class="form-group">
        <label class="control-label">本文</label>
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
@endsection

@section('content.sub')
<h3>メディアアップロード</h3>
<form enctype="multipart/form-data" id="add-media-form" role="form" method="POST" action="">
    {!! csrf_field() !!}
    <input type="file" class="form-control" id="catagry_logo">
    <button type="button" id="add-media-button">Add</button>
</form>
@endsection

@section('page_css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-markdown/2.10.0/css/bootstrap-markdown.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet">
<style>
.bootstrap-tagsinput {
    width: 100%;
}
</style>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script>
/*Add new catagory Event*/
$("#add-media-button").on('click', function(e){
    $.ajax({
        url: '/media',
        // headers:  {
        //     "X-CSRF-TOKEN": token
        // },
        type: 'POST',
        data: ({
            type: 'post',
            formData: new FormData(this),
        }),
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
            console.log(response);
        },
    });
});
/*Add new catagory Event*/
</script>
@endsection
