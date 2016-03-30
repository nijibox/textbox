@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <h1>最近の記事 ({{ $articles->currentPage() }}/{{$articles->lastPage()}})</h1>
    </div>
    <div class="row">
        @foreach($articles as $article)
            <div class="well well-sm">
                <h3><a href="{{ route('get_article_single', $article->id) }}">{{ $article->title }}</a></h3>
                <p class="text-right">
                    by {{ $article->author->name }}
                    at {{ $article->updated_at}}
                </p>
            </div>
        @endforeach
        <nav>
            <ul class="pagination pagination-sm">
                @if($articles->currentPage() == 1)
                    <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                @else
                    <li><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                @endif
                @for($page = 1; $page <= $articles->lastPage(); $page++)
                    @if($page == $articles->currentPage())
                        <li class="active"><a href="#">{{$page}} <span class="sr-only">(current)</span></a></li>
                    @else
                        <li><a href="{{ $articles->url($page) }}">{{$page}}</a></li>
                    @endif
                @endfor
                @if($articles->currentPage() == $articles->lastPage())
                    <li class="disabled"><a href="#" aria-label="Following"><span aria-hidden="true">&raquo;</span></a></li>
                @else
                    <li><a href="#" aria-label="Following"><span aria-hidden="true">&raquo;</span></a></li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endsection
