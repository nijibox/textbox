@extends('layouts.app')

@section('content')
<div class="container">
    <h2>マイページ</h2>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">自分の記事</div>

                <div class="panel-body">
                    <ul>
                        <li class="fa fa-lock">下書き</li>
                        <li class="fa fa-folder-open">公開中</li>
                    </ul>
                </div>

                <table class="table">
                    @foreach($authoredArticles as $article)
                        <tr>
                            {{--<td><a href="{{ route('form_edit_article', $article->id) }}">編集する</a></td>--}}
                            @if($article->status == 'draft')
                            <td style="width: 5%"><span class="fa fa-lock fa-fw"></span><!-- 下書き --></td>
                            @elseif($article->status == 'internal')
                            <td style="width: 5%"><span class="fa fa-folder-open fa-fw"></span><!-- 公開中 --></td>
                            @endif
                            <td style="width: 85%"><a href="{{ route('get_article_single', $article->id) }}">{{ $article->title }}</a></td>
                            <td style="width: 10%"><a href="{{ route('form_edit_article', $article->id) }}">編集する</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
