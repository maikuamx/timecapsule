<?php

namespace App\Http\Controllers;

use App\Models\TimeCapsule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TimeCapsuleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $unlockableCapsules = $user->timeCapsules()
            ->unlockable()
            ->where('is_unlocked', false)
            ->orderBy('unlock_date', 'asc')
            ->get();

        $pendingCapsules = $user->timeCapsules()
            ->pending()
            ->orderBy('unlock_date', 'asc')
            ->get();

        $openedCapsules = $user->timeCapsules()
            ->where('is_unlocked', true)
            ->orderBy('opened_at', 'desc')
            ->get();

        return view('dashboard', compact('unlockableCapsules', 'pendingCapsules', 'openedCapsules'));
    }

    public function create()
    {
        return view('capsules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'unlock_date' => 'required|date|after:now',
            'content_type' => 'required|in:text,photo,mixed',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        ]);

        $capsule = new TimeCapsule([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'content_type' => $validated['content_type'],
            'unlock_date' => Carbon::parse($validated['unlock_date']),
        ]);

        $capsule->encryptContent($validated['content']);

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('capsule-attachments', 'private');
                $attachments[] = [
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }
            $capsule->attachments = $attachments;
        }

        $capsule->save();

        return redirect()->route('dashboard')
            ->with('success', 'Your time capsule has been created! It will unlock on ' .
                $capsule->unlock_date->format('F j, Y \a\t g:i A'));
    }

    public function show(TimeCapsule $timeCapsule)
    {
        $this->authorize('view', $timeCapsule);

        if (!$timeCapsule->isUnlockable()) {
            return redirect()->route('dashboard')
                ->with('error', 'This time capsule is not yet ready to be opened. Please wait until ' .
                    $timeCapsule->unlock_date->format('F j, Y \a\t g:i A'));
        }

        $timeCapsule->markAsOpened();
        $content = $timeCapsule->decryptContent();

        return view('capsules.show', compact('timeCapsule', 'content'));
    }

    public function destroy(TimeCapsule $timeCapsule)
    {
        $this->authorize('delete', $timeCapsule);

        $timeCapsule->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Time capsule deleted successfully.');
    }
}
