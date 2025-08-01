<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $chats = Chat::with([
            'exchangeRequest.requester',
            'exchangeRequest.offeredItem.photos',
            'exchangeRequest.offeredItem.user',
            'exchangeRequest.requestedItem.photos',
            'exchangeRequest.requestedItem.user',
        ])
        ->whereHas('exchangeRequest', function ($query) use ($user) {
            $query->whereIn('status', ['accepted', 'cancelled', 'completed'])
                ->where(function ($q) use ($user) {
                    $q->where('requester_id', $user->id)
                        ->orWhereHas('offeredItem', fn($q2) => $q2->where('user_id', $user->id))
                        ->orWhereHas('requestedItem', fn($q3) => $q3->where('user_id', $user->id));
                });
        })
        ->paginate(6)
        ->through(function ($chat) use ($user) {
            $exchange = $chat->exchangeRequest;
            $yoSoyRequester = $exchange->requester_id === $user->id;

            return (object)[
                'id' => $chat->id,
                'alias' => $yoSoyRequester 
                    ? $exchange->requestedItem->user->alias 
                    : $exchange->requester->alias,
                'foto' => $yoSoyRequester
                    ? ($exchange->requestedItem->photos->first()?->photo_url 
                        ? Storage::url($exchange->requestedItem->photos->first()->photo_url) 
                        : null)
                    : ($exchange->offeredItem->photos->first()?->photo_url 
                        ? Storage::url($exchange->offeredItem->photos->first()->photo_url) 
                        : null),
                'titulo' => $yoSoyRequester
                    ? $exchange->requestedItem->title
                    : $exchange->offeredItem->title,
                'ultimo_mensaje' => $chat->messages()->latest('sent_at')->first()?->content ?? null,
                'match_date' => $exchange->match_date,
                'estado' => $exchange->status,
            ];
        });


        return view('chats.index', compact('chats'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, int $id)
    {
        $user = auth()->user();

        $chat = Chat::with([
            'exchangeRequest.requester',
            'exchangeRequest.offeredItem.user',
            'exchangeRequest.requestedItem.user',
        ])->findOrFail($id);

        $exchange = $chat->exchangeRequest;

        if (
            !$exchange || (
                $user->id !== $exchange->requester_id &&
                $user->id !== $exchange->offeredItem->user_id &&
                $user->id !== $exchange->requestedItem->user_id
            )
        ) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $chat->messages()->create([
            'sender_id' => $user->id,
            'content' => $validated['content'],
            'sent_at' => now(),
        ]);

        return redirect()->route('chats.show', $chat->id);
    }


    /**
     * Display the specified resource.
     */
    public function show(Chat $chat)
    {
        $user = auth()->user();
        $exchange = $chat->exchangeRequest;

        if (
            $user->id !== $exchange->requester_id &&
            $user->id !== $exchange->offeredItem->user_id &&
            $user->id !== $exchange->requestedItem->user_id
        ) {
            abort(403);
        }

        $messages = $chat->messages()->with('sender')->orderByDesc('sent_at')->get();

        return view('chats.show', compact('chat', 'messages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
