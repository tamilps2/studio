@extends('layouts.main')

@section('title', $data['user']->name)

@push('meta')
    <meta name="description" content="{{ $data['summary'] }}">
    <meta name="og:title" content="{{ $data['user']->name }}">
    <meta name="og:description" content="{{ $data['summary'] }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $data['user']->name }}">
    <meta name="twitter:description" content="{{ $data['summary'] }}">
    <meta name="og:image" content="{{ url($data['avatar']) }}">
    <meta name="twitter:image" content="{{ url($data['avatar']) }}">
@endpush

@section('content')
    <div class="container my-5 col-md-8 offset-md-2 align-items-center">
        <div class="row">
            <div class="col-lg-2">
                <img src="{{ $data['avatar'] }}" alt="{{ $data['user']->name }}" width="120" class="rounded-circle shadow-inner">
            </div>
            <div class="col-lg-10">
                <h1 class="font-weight-bold">{{ $data['user']->name }}</h1>
                <p class="text-muted">
                    {{ $data['summary'] }}
                </p>
            </div>
        </div>
    </div>

    <main role="main" class="container col-md-8 offset-md-2 @if($data['posts']->count() == 0) mt-4 @endif">
        <h3 class="mb-4 font-italic @if($data['posts']->count() > 0) pb-4 border-bottom @endif font-serif">
            {{ __('canvas::blog.posts.label') }}
        </h3>
        @if($data['posts']->count() > 0)
            @foreach($data['posts'] as $post)
                @if(!$loop->first)
                    <div class="mb-5">
                        <h3>
                            <a href="{{ route('post', [$post->userMeta->username, $post->slug]) }}" class="font-serif text-dark text-decoration-none">{{ $post->title }}</a>
                        </h3>
                        <p class="text-muted mb-2">{{ $post->published_at->format('M d') }} — {{ $post->read_time }}</p>
                        <p>
                            <a href="{{ route('post', [$post->userMeta->username, $post->slug]) }}" class="text-dark text-decoration-none">{{ $post->summary }}</a>
                        </p>
                    </div>
                @endif
            @endforeach

            {{ $data['posts']->links() }}
        @endif
    </main>
@endsection
