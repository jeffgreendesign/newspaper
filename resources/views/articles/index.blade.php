@extends('layouts.app')

@section('content')
<h1 class="text-3xl font-bold mb-4">Latest News</h1>

<!-- Search Bar -->
<form method="GET" action="{{ url('/') }}" class="mb-4">
    <input type="text" name="search" placeholder="Search articles..." 
    class="border p-2 rounded w-64" value="{{ request('search') }}">
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Search</button>
</form>

<!-- Categories -->
<h2 class="text-xl font-semibold">Categories</h2>
<ul class="flex gap-2 mb-4">
    @foreach($articles->unique('category') as $article)
    <li>
        <a href="{{ url('category/' . strtolower($article->category)) }}" 
            class="bg-gray-200 text-gray-800 px-3 py-1 rounded">
            {{ $article->category }}
        </a>
    </li>
    @endforeach
</ul>

<!-- Articles -->
<ul class="space-y-4">
    @forelse($articles as $article)
    <li class="bg-white p-4 shadow rounded">
        <a href="{{ url('article/' . $article->slug) }}" class="text-lg font-bold text-blue-600">
            {{ $article->title }}
        </a>
        <p class="text-gray-600 text-sm">{{ $article->date }}</p>
    </li>
    @empty
    <p>No articles found.</p>
    @endforelse
</ul>

<!-- Pagination -->
<div class="mt-6">
    {{ $articles->links('pagination::tailwind') }}
</div>
@endsection
