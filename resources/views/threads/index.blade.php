@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    
        @forelse ($threads as $thread)
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                    
                        <div class="level">

                            <h4 class="flex">
                                <a href="{{ $thread->path() }}">
                                    {{ $thread->title }}
                                </a>
                            </h4>

                            <a href="{{ $thread->path() }}">
                                {{ $thread->replies_count }} replies
                            </a>

                        </div>
                    </div>

                    <div class="panel-body m-4">
                        <div class="body">{{ $thread->body }}</div>
                        <br>
                    </div>
                </div>
                <br>
            </div>
        @empty
            <p>There is no relevant results at this time</p>
        @endforelse
    </div>
</div>
@endsection