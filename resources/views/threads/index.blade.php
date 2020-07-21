@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    
        @foreach ($threads as $thread)
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
        @endforeach 
    </div>
</div>
@endsection