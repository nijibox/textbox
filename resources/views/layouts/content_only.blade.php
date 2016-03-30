@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        {!! $parser->parse($content) !!}
    </div>
</div>
@endsection
