@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">最近の記事</div>

                <div class="panel-body">
                </div>

                <table class="table">
                    @foreach($latestArticles as $article)
                        <tr>
                            <td><a href="{{ route('get_article_single', $article->id) }}">{{ $article->title }}</a></td>
                        </tr>
                    @endforeach
                        <tr>
                            <td style="text-align: right;"><a href="{{ route('get_article_list') }}">...もっと見る</a></td>
                        </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
