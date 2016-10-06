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
    {{ Form::hidden('attachmentIds', $article->attachmentsForInput(), ['id' => 'attachment-ids']) }}
    <div class="form-group">
        <label class="control-label">タイトル</label>
        <input class="form-control" type="text" name="articleTitle" placeholder="Title ?" value="{{$article->title}}">
    </div>
    <div class="form-group">
        <label class="control-label">タグ</label>
        <input class="form-control" type="text" name="articleTags" data-role="tagsinput" value="{{$article->tagsForInput()}}">
    </div>
    <edit-markdown></edit-markdown>
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
<attachments token="{{csrf_token()}}" article-id="{{ $article->id }}">
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/markdown-it/8.0.0/markdown-it.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/riot/2.5.0/riot+compiler.min.js"></script>
<script type="riot/tag">
    <attachments>
        <h3>添付</h3>

        <form enctype="multipart/form-data" role="form">
            <input type="hidden" name="_token" value={ opts.token }>
            <input type="file" class="form-control" name="attachment" >
            <button type="button" class="btn btn-default" onclick={ postFile } >追加</button>
        </form>

        <div>
        <p style="white-space:nowrap; overflow:hidden;" each={ attachment in attachments }>
            <button type="button" class="btn-clipboard btn btn-default btn-xs"  data-clipboard-text={ buildClipboardText(attachment) } >
                <span class="glyphicon glyphicon-duplicate"></span>
            </button>
            { attachment.original_name }
        </p>
        </div>

        this.fileData = null
        this.attachments = [];
        addPostedAttachments (attachments) {
            var idset = []; 
            this.attachments.forEach(function(val, idx){
                idset.push(val.id);
            }, this);
            self = this;
            attachments.forEach(function(val, idx){
                self.attachments.push(val);
                idset.push(val.id);
                self.update();
            });
            console.log(idset.join());
            $('#attachment-ids').val(idset.join())
        }
        postFile (e) {
            var formElm = $(e.target).parent();
            var formData = new FormData($(formElm).get(0));
            self = this;
            $.ajax({
                url: '/attachments',
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json'
            })
            .done(function(result, textStatus, jqXHR){
                self.addPostedAttachments(result.data);
                // this.update({attachments: result.data});
            })
            .fail(function(jqXHR, textStatus, errorThrown){
                console.log('NG');
            });
        }
        buildClipboardText (attachment) {
            return '![' + attachment.original_name +  ']' + '(' + attachment.url + ')'
        }
        this.on('mount', function() {
            console.log(opts.articleId);
            if (opts.articleId != null){
                self = this;
                $.ajax({
                    url: '/attachments?articleId='+opts.articleId,
                    type: 'GET',
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json'
                })
                .done(function(result, textStatus, jqXHR){
                    self.addPostedAttachments(result.data);
                })
            }
        })
    </attachments>

    <edit-markdown>
        <div class="form-group">
            <label class="control-label">本文</label>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#edit-markdown" aria-controls="edit-markdown" role="tab" data-toggle="tab">編集</a></li>
                <li role="presentation"><a href="#preview-markdown" aria-controls="preview-markdown" role="tab" data-toggle="tab">プレビュー</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="edit-markdown">
                    <textarea name="articleBody" class="form-control" rows="30" onkeyup={ edit }>{this.body}</textarea>
                </div>
                <div role="tabpanel" class="tab-pane" id="preview-markdown">
                    <div class="page-content" id="preview"></div>
                </div>
            </div>
        </div>

        this.body = opts.article.body
        this.md = window.markdownit()
        this.on('mount', function() {
            document.getElementById('preview').innerHTML = this.md.render(this.body)
        })
        edit(e){
            this.body = e.target.value
            document.getElementById('preview').innerHTML = this.md.render(this.body)
        }

    </edit-markdown>

</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js"></script>
<script>
new Clipboard('.btn-clipboard');
var articleJson = {!! json_encode($article) !!};
riot.mount('attachments');
riot.mount('edit-markdown', {article: articleJson});
</script>
@endsection
