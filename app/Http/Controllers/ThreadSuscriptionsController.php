<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

class ThreadSuscriptionsController extends Controller
{
    public function store($channelId, Thread $thread)
    {
        $thread->suscribe();
    }

    public function destroy($channelId, Thread $thread)
    {
        $thread->unsuscribe();
    }
}
