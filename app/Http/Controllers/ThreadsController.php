<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Channel;
use App\Filters\ThreadFilters;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{

    /**
     * ThreadsController constructor
     */
    public function __construct()
    {
        //this works but need add every link that you havent access
        //$this->middleware('auth')->only(['create','store', 'update']);
        //we can use except
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Channel $channel, ThreadFilters $filters)
    {
        
        $threads = $this->getThreads($channel, $filters);

        if ( request()->wantsJson()) {
            return $threads;
        }

        return view('threads.index', compact('threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => 'required|exists:channels,id'
        ]);

          $thread = Thread::create([
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id'),
            'title' => request('title'),
            'body' => request('body')
          ]);

          return \redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param  $channel_id
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channel_id, Thread $thread)
    {
        //return Thread::withCount('replies')->find(12); //just for see result
        //return $thread->getReplyCountAttribute();
        //return $thread;
        //return view('threads.show', compact('thread'));

        return view('threads.show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(20)
        ]);

    }

    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        //after filters refactor
        //$threads = Thread::with('channel')->latest()->filter($filters); //reducir el numero ded queries
        //after use global scope in Thread (protected $with = ['creator', 'channel'];) we can change this to
        $threads = Thread::latest()->filter($filters);

        if($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        $threads = $threads->get();
        return $threads;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Thread  $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy($channel, Thread $thread)
    {
        $thread->replies()->delete();//first we have to delete the replies associated
        $thread->delete();

        if (request()->wantsJson()){
            return \response([], 204);
        }

        return redirect('/threads');
        
    }

}
