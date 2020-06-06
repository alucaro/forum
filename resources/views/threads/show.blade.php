@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h4>{{ $thread->title }}</h4></div>

                <div class="panel-body">
                    {{ $thread->body }}
                </div>

            </div>
        </div>
    </div>

    <br>

    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach($thread->replies as $reply)
                <div class="card">
                    <div class="card-header">
                        <a href="#">
                            {{ $reply->owner->name }}
                        </a> said  {{ $reply->created_at->diffForHumans() }}...
                    </div>       

                        <div class="panel-body">
                            {{ $reply->body }}
                        </div>
                </div>
                <br>
            @endforeach
        </div>
    </div>

</div>
@endsection