@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 font-serif">Your Time Capsules</h1>
            <p class="text-gray-600 mt-2">Welcome back, {{ Auth::user()->name }}. Here are your messages through time.</p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Ready to Open</p>
                        <p class="text-2xl font-bold text-green-800">{{ $unlockableCapsules->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-200 rounded-full flex items-center justify-center">
                        <span class="text-2xl">🎁</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Waiting</p>
                        <p class="text-2xl font-bold text-blue-800">{{ $pendingCapsules->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center">
                        <span class="text-2xl">⏳</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-6 border border-amber-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-600 text-sm font-medium">Opened</p>
                        <p class="text-2xl font-bold text-amber-800">{{ $openedCapsules->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center">
                        <span class="text-2xl">📖</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ready to Open Capsules -->
        @if($unlockableCapsules->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 font-serif">🎉 Ready to Open!</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($unlockableCapsules as $capsule)
                        <div class="bg-white rounded-xl shadow-lg border-2 border-green-200 p-6 transform hover:scale-105 transition-transform duration-200">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="font-semibold text-lg text-gray-900">{{ $capsule->title }}</h3>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-medium">Ready</span>
                            </div>

                            <p class="text-sm text-gray-600 mb-4">
                                Created {{ $capsule->created_at->diffForHumans() }}
                            </p>

                            <div class="flex space-x-3">
                                <a href="{{ route('capsules.show', $capsule) }}"
                                   class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-center font-medium transition-colors duration-200">
                                    Open Capsule
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Pending Capsules -->
        @if($pendingCapsules->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 font-serif">⏰ Waiting to Unlock</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pendingCapsules as $capsule)
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="font-semibold text-lg text-gray-900">{{ $capsule->title }}</h3>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-medium">Locked</span>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Unlocks on:</p>
                                <p class="font-medium text-blue-600">{{ $capsule->unlock_date->format('M j, Y \a\t g:i A') }}</p>
                            </div>

                            <div class="mb-4">
                                @php $timeLeft = $capsule->getTimeUntilUnlock() @endphp
                                <p class="text-sm text-gray-600 mb-1">Time remaining:</p>
                                <div class="text-2xl font-bold text-blue-800 font-mono">
                                    {{ $timeLeft['days'] }}d {{ $timeLeft['hours'] }}h {{ $timeLeft['minutes'] }}m
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <form action="{{ route('capsules.destroy', $capsule) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full bg-red-50 hover:bg-red-100 text-red-600 px-4 py-2 rounded-lg text-center font-medium transition-colors duration-200"
                                            onclick="return confirm('Are you sure you want to delete this time capsule? This action cannot be undone.')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Opened Capsules -->
        @if($openedCapsules->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 font-serif">📚 Your Journey</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($openedCapsules as $capsule)
                        <div class="bg-white rounded-xl shadow-lg p-6 opacity-75">
                            <div class="flex items-start justify-between mb-4">
                                <h3 class="font-semibold text-lg text-gray-900">{{ $capsule->title }}</h3>
                                <span class="bg-amber-100 text-amber-800 text-xs px-2 py-1 rounded-full font-medium">Opened</span>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm text-gray-600">Opened {{ $capsule->opened_at->diffForHumans() }}</p>
                            </div>

                            <div class="flex space-x-3">
                                <a href="{{ route('capsules.show', $capsule) }}"
                                   class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-center font-medium transition-colors duration-200">
                                    View Again
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Empty State -->
        @if($unlockableCapsules->count() === 0 && $pendingCapsules->count() === 0 && $openedCapsules->count() === 0)
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="text-5xl">📝</span>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-2 font-serif">No Time Capsules Yet</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Start your journey through time by creating your first time capsule.
                    Write a message to your future self!
                </p>
                <a href="{{ route('capsules.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold text-lg transition-all duration-200 transform hover:scale-105 inline-block">
                    Create Your First Capsule
                </a>
            </div>
        @endif
    </div>
@endsection
