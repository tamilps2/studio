@extends('layouts.main')

@section('title', $data['post']->title)

@push('meta')
    <meta name="description" content="{{ $data['meta']['meta_description'] }}">
    <meta name="og:title" content="{{ $data['meta']['og_title'] }}">
    <meta name="og:description" content="{{ $data['meta']['og_description'] }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $data['meta']['twitter_title'] }}">
    <meta name="twitter:description" content="{{ $data['meta']['twitter_description'] }}">

    @isset($data['meta']['canonical_link'])
        <link rel="canonical" href="{{ $data['meta']['canonical_link'] }}"/>
    @endisset

    @isset($data['post']->featured_image)
        <meta name="og:image" content="{{ url($data['post']->featured_image) }}">
        <meta name="twitter:image" content="{{ url($data['post']->featured_image) }}">
    @endisset
@endpush

@section('actions')
    @if($data['post']->user_id == optional(request()->user())->id)
        <div class="dropdown">
            <a href="#" id="actionDropdownMenu" class="nav-link pl-0 pr-3" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" viewBox="0 0 24 24" class="icon-cog primary"><path d="M6.8 3.45c.87-.52 1.82-.92 2.83-1.17a2.5 2.5 0 0 0 4.74 0c1.01.25 1.96.65 2.82 1.17a2.5 2.5 0 0 0 3.36 3.36c.52.86.92 1.8 1.17 2.82a2.5 2.5 0 0 0 0 4.74c-.25 1.01-.65 1.96-1.17 2.82a2.5 2.5 0 0 0-3.36 3.36c-.86.52-1.8.92-2.82 1.17a2.5 2.5 0 0 0-4.74 0c-1.01-.25-1.96-.65-2.82-1.17a2.5 2.5 0 0 0-3.36-3.36 9.94 9.94 0 0 1-1.17-2.82 2.5 2.5 0 0 0 0-4.74c.25-1.01.65-1.96 1.17-2.82a2.5 2.5 0 0 0 3.36-3.36zM12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/><circle cx="12" cy="12" r="2"/></svg>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionDropdownMenu">
                <a href="{{ url(sprintf('%s/posts/%s/edit', config('canvas.path'), $data['post']->id)) }}"
                   class="dropdown-item">{{ __('canvas::blog.buttons.edit') }}</a>

                <a href="{{ url(sprintf('%s/stats/%s', config('canvas.path'), $data['post']->id)) }}"
                   class="dropdown-item">{{ __('canvas::blog.buttons.stats') }}</a>
            </div>
        </div>
    @endif
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col col-lg-8">
                <h1 class="text-dark font-serif pt-5 mb-4 @unless($data['post']->summary) mb-4 @endif">{{ $data['post']->title }}</h1>

                <div class="media py-1">
                    <a href="{{ route('user', $data['username']) }}">
                        <img src="{{ $data['userAvatar'] }}"
                             class="mr-3 rounded-circle shadow-inner"
                             style="width: 50px"
                             alt="{{ $data['user']->name }}">
                    </a>
                    <div class="media-body">
                        <a href="{{ route('user', $data['username']) }}">
                            <p class="mt-0 mb-1 font-weight-bold text-dark">{{ $data['user']->name }}</p>
                        </a>
                        <span class="text-muted">{{ \Carbon\Carbon::parse($data['post']->published_at)->format('M d, Y') }} — {{ $data['post']->read_time }}</span>
                    </div>
                </div>

                @isset($data['post']->featured_image)
                    <img src="{{ $data['post']->featured_image }}" class="w-100 pt-4"
                         @isset($data['post']->featured_image_caption) alt="{{ $data['post']->featured_image_caption }}"
                         title="{{ $data['post']->featured_image_caption }}" @endisset>
                    @isset($data['post']->featured_image_caption)
                        <p class="text-muted text-center pt-3" style="font-size: 0.9rem">{!! $data['post']->featured_image_caption !!}</p>
                    @endisset
                @endisset

                <div class="post font-serif mt-4">{!! $data['post']->body !!}</div>

                @if($data['post']->tags->count() > 0)
                    @foreach($data['post']->tags as $tag)
                        <a href="{{ route('tag', $tag->slug) }}" class="badge badge-light p-2 my-1 text-decoration-none">{{ $tag->name }}</a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    @isset($data['meta']['canonical_link'])
        <div class="post">
            <hr>

            <p class="text-center font-italic pt-3 my-5">
                {{ __('canvas::blog.buttons.canonical') }} <a href="{{ url($data['meta']['canonical_link']) }}" target="_blank" class="text-dark" rel="noopener">{{ parse_url($data['meta']['canonical_link'])['host'] }}</a>
            </p>
        </div>
    @endisset

    <div class="read-more mt-5 container-fluid">
        <div class="row">
            @isset($data['next']['post'])
                <div class="col-lg bg-light text-center px-lg-5 py-5"
                     @isset($data['next']['post']->featured_image) style="background: linear-gradient(rgba(0, 0, 0, 0.8),rgba(0, 0, 0, 0.8)),url({{ $data['next']['post']->featured_image }}); background-size: cover" @endisset>
                    <a href="{{ route('post', [$data['next']['username'], $data['next']['post']->slug]) }}"
                       class="btn btn-sm text-decoration-none @isset($data['next']['post']->featured_image) btn-outline-light @else btn-outline-secondary @endisset text-uppercase font-weight-bold mt-3">
                        {{ __('canvas::blog.buttons.next') }}
                    </a>
                    <h2 class="font-weight-bold font-serif my-3">
                        <a href="{{ route('post', [$data['next']['username'], $data['next']['post']->slug]) }}" class="text-decoration-none @isset($data['next']['post']->featured_image) text-light @else text-dark @endisset">{{ $data['next']['post']->title }}</a>
                    </h2>
                    <p class="text-lg font-serif @isset($data['next']['post']->featured_image) text-white-50 @else text-muted @endisset">{{ Illuminate\Support\Str::limit(strip_tags($data['next']['post']->summary), 140) }}</p>
                </div>
            @endisset
            @isset($data['random']['post'])
                <div class="col-lg bg-light text-center px-lg-5 py-5"
                     @isset($data['random']['post']->featured_image) style="background: linear-gradient(rgba(0, 0, 0, 0.8),rgba(0, 0, 0, 0.8)),url({{ $data['random']['post']->featured_image }}); background-size: cover" @endisset>
                    <a href="{{ route('post', [$data['random']['username'], $data['random']['post']->slug]) }}" class="btn btn-sm text-decoration-none @isset($data['random']['post']->featured_image) btn-outline-light @else btn-outline-secondary @endisset text-uppercase font-weight-bold mt-3">
                        {{ __('canvas::blog.buttons.enjoy') }}
                    </a>
                    <h2 class="font-weight-bold font-serif my-3">
                        <a href="{{ route('post', [$data['random']['username'], $data['random']['post']->slug]) }}" class="text-decoration-none @isset($data['random']['post']->featured_image) text-light @else text-dark @endisset">{{ $data['random']['post']->title }}</a>
                    </h2>
                    <p class="font-serif body @isset($data['random']['post']->featured_image) text-white-50 @else text-muted @endisset">{{ Illuminate\Support\Str::limit(strip_tags($data['random']['post']->summary), 140) }}</p>
                </div>
            @endisset
        </div>
    </div>
@endsection
