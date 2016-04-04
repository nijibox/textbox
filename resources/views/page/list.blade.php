@extends('layouts.2paned')

@section('content.title')
<h1>@yield('title', 'ページ') ({{ $pages->currentPage() }}/{{$pages->lastPage()}})</h1>
@endsection

@section('content.main')
@foreach($pages as $page)
    <div class="well well-sm">
        <h3><a href="{{ route('get_page_single', $page->id) }}">{{ $page->title }}</a></h3>
        <div class="row">
            <div class="col-xs-6">
            </div>
            <div class="col-xs-6">
            <p class="text-right"><a href="{{ route('form_edit_page', $page->id) }}">編集する</a></p>
            </div>
        </div>
    </div>
@endforeach
<nav>
    <ul class="pagination pagination-sm">
        @if($pages->currentPage() == 1)
            <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
        @else
            <li><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
        @endif
        @for($page = 1; $page <= $pages->lastPage(); $page++)
            @if($page == $pages->currentPage())
                <li class="active"><a href="#">{{$page}} <span class="sr-only">(current)</span></a></li>
            @else
                <li><a href="{{ $pages->url($page) }}">{{$page}}</a></li>
            @endif
        @endfor
        @if($pages->currentPage() == $pages->lastPage())
            <li class="disabled"><a href="#" aria-label="Following"><span aria-hidden="true">&raquo;</span></a></li>
        @else
            <li><a href="#" aria-label="Following"><span aria-hidden="true">&raquo;</span></a></li>
        @endif
    </ul>
</nav>
@endsection

@section('content.sub')
@endsection
