@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <h1>{{ $article->title }}</h1>
        @if($article->author->id == Auth::user()->id)
        <form action="{{ '/articles/' . $article->id . '/_edit' }}">
            <button type="submit" class="btn btn-default pull-right">編集する</button>
        </form>
        @endif
        <hr>
        {{-- フラッシュメッセージの表示 --}}
        @if (Session::has('flash_message'))
        <div class="alert alert-success">{{ Session::get('flash_message') }}</div>
        @endif
    </div>
    <div class="row">
        {!! $parser->parse($article->body) !!}
    </div>
</div>
@endsection
