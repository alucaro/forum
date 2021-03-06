<reply :attributes="{{ $reply }}" inline-template v-cloak>

    <div id="reply-{{ $reply->id }}" class="card mt-4">
        <div class="card-header">
            <div class="level">

                <h5 class="flex">
                    <a href="{{ route('profile', $reply->owner) }}">
                        {{ $reply->owner->name }}
                    </a> said {{ $reply->created_at->diffForHumans() }}...
                </h5>

                @if (Auth::check())
                <div>
                    <favorite :reply="{{ $reply }}"></favorite>
                </div>
                @endif

            </div>

        </div>

        <div class="panel-body m-2">

            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>

                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>

            <div v-else v-text="body">
                {{ $reply->body }}
            </div>

        </div>

        @can ('update', $reply)
        <div class="panel-footer level border-top my-3">

            <button class="btn btn-sm m-1" @click="editing = true">Edit </button>
            <button class="btn btn-sm m-1 btn-danger" @click="destroy">Delete </button>

        </div>
        @endcan

    </div>

</reply>