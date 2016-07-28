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
<attachments token="{{csrf_token()}}" >
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/riot/2.5.0/riot+compiler.min.js"></script>
<script type="riot/tag">
    <attachments>
        <h3>添付</h3>

        <form enctype="multipart/form-data" role="form">
            <input type="hidden" name="_token" value={ opts.token }>
            <input type="file" class="form-control" name="attachment" >
            <button type="button" onclick={ postFile } >Add</button>
        </form>

        this.fileData = null
        postFile (e) {
            var formElm = $(e.target).parent();
            var formData = new FormData($(formElm).get(0));
            $.ajax({
                url: '/attachments',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json'
            })
            .done(function(data, textStatus, jqXHR){
                console.log('OK');
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                console.log('NG');
            });
        }
    </attachments>
</script>
<script>
riot.mount('*');
</script>
@endsection
