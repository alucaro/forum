<div class="card">
    <div class="card-header">
        <div class="level">

            <h5 class="flex">
                <a href="#">
                {{ $reply->owner->name }}
                </a> said  {{ $reply->created_at->diffForHumans() }}...
            </h5>
             
            <div>
                <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                    {{ csrf_field()}}
                    <button type="submit" class="btn btn-default"  {{ $reply->isFavorited() ? 'disabled' : '' }}></button>
                        {{ $reply->favorites_count }} {{Str::plural('Favorite', $reply->favorites_count) }}
                    </button>
                </form>
            </div>

        </div>
        
    </div>       

    <div class="panel-body">
        {{ $reply->body }}
    </div>

</div>
<br>