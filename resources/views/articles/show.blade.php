@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold">{{ $article->title }}</h1>
    <p class="text-gray-500 text-sm">Published on {{ $article->date }}</p>
    <p class="text-gray-700"><strong>Category:</strong> {{ $article->category }}</p>
    <hr class="my-4">
    <div class="prose">
        {!! $article->content !!}
    </div>
    <a href="{{ url('/') }}" class="mt-4 inline-block text-blue-600">← Back to homepage</a>
@endsection
