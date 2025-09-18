@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-gradient-to-br from-amber-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <span class="text-4xl">🎁</span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 font-serif mb-2">Time Capsule Opened!</h1>
            <p class="text-gray-600">
                {{ $timeCapsule->title }} •
                @if($timeCapsule->is_unlocked)
                    First opened {{ $timeCapsule->opened_at->diffForHumans() }}
                @else
                    Opened just now
                @endif
            </p>
        </div>

        <!-- Metadata -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-xl p-6 mb-8 border border-blue-200">
            <div class="grid md:grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-blue-600 text-sm font-medium">Created</p>
                    <p class="text-lg font-semibold text-blue-900">{{ $timeCapsule->created_at->format('M j, Y') }}</p>
                </div>
                <div>
                    <p class="text-blue-600 text-sm font-medium">Scheduled to Unlock</p>
                    <p class="text-lg font-semibold text-blue-900">{{ $timeCapsule->unlock_date->format('M j, Y \a\t g:i A') }}</p>
                </div>
                <div>
                    <p class="text-blue-600 text-sm font-medium">Journey Length</p>
                    <p class="text-lg font-semibold text-blue-900">{{ $timeCapsule->created_at->diffForHumans($timeCapsule->unlock_date) }}</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 font-serif">Your Message from the Past</h2>

            <div class="prose prose-lg max-w-none">
                <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border-l-4 border-amber-400 p-6 rounded-r-lg">
                    <div class="whitespace-pre-wrap text-gray-800 leading-relaxed font-serif text-lg">{{ $content }}</div>
                </div>
            </div>

            <!-- Attachments -->
            @if($timeCapsule->attachments && count($timeCapsule->attachments) > 0)
                <div class="mt-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Attachments</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($timeCapsule->attachments as $attachment)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    @if(str_starts_with($attachment['mime_type'], 'image/'))
                                        <span class="text-2xl">🖼️</span>
                                    @else
                                        <span class="text-2xl">📄</span>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $attachment['original_name'] }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ number_format($attachment['size'] / 1024, 1) }} KB
                                        </p>
                                    </div>
                                    <a href="{{ Storage::url($attachment['path']) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Reflection Section -->
        <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl p-6 mb-8 border border-green-200">
            <h3 class="text-xl font-semibold text-green-900 mb-3">Reflection Time</h3>
            <p class="text-green-800">
                How do you feel reading your message from {{ $timeCapsule->created_at->diffForHumans() }}?
                Has anything changed? Did any of your predictions come true?
                Consider creating a new time capsule to capture your thoughts today!
            </p>
        </div>

        <!-- Actions -->
        <div class="text-center space-y-4">
            <a href="{{ route('capsules.create') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold text-lg transition-all duration-200 transform hover:scale-105">
                Create Another Capsule
            </a>

            <div>
                <a href="{{ route('dashboard') }}"
                   class="text-gray-600 hover:text-gray-800 font-medium">
                    ← Back to Dashboard
                </a>
            </div>

            <form action="{{ route('capsules.destroy', $timeCapsule) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-red-600 hover:text-red-800 font-medium text-sm"
                        onclick="return confirm('Are you sure you want to delete this time capsule? This action cannot be undone.')">
                    Delete This Capsule
                </button>
            </form>
        </div>
    </div>
@endsection
