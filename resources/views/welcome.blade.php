@extends('layouts.app')

@section('content')
    <div class="relative overflow-hidden">
        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-6xl font-bold text-gray-900 font-serif mb-6">
                    Digital Time Capsule
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Write letters to your future self. Store memories, dreams, and moments in time.
                    Your messages will unlock exactly when you choose, creating a bridge between who you are today
                    and who you'll become tomorrow.
                </p>

                <div class="space-x-4">
                    <a href="{{ route('register') }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold text-lg transition-all duration-200 transform hover:scale-105 inline-block">
                        Start Your Journey
                    </a>
                    <a href="{{ route('login') }}"
                       class="bg-white hover:bg-gray-50 text-blue-600 px-8 py-3 rounded-lg font-semibold text-lg border-2 border-blue-600 transition-all duration-200 transform hover:scale-105 inline-block">
                        Sign In
                    </a>
                </div>
            </div>

            <!-- Features -->
            <div class="mt-24 grid md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">🔐</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure & Encrypted</h3>
                    <p class="text-gray-600">Your messages are encrypted and stored securely. Only you can access them when the time comes.</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">⏰</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Future Unlock</h3>
                    <p class="text-gray-600">Set any future date. Your capsules remain sealed until that moment arrives.</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-3xl">💌</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Rich Content</h3>
                    <p class="text-gray-600">Include text, photos, and attachments to create meaningful time capsules.</p>
                </div>
            </div>
        </div>

        <!-- Background Elements -->
        <div class="absolute top-0 left-0 w-full h-full pointer-events-none overflow-hidden">
            <div class="absolute top-20 left-10 w-64 h-64 bg-blue-200 rounded-full opacity-20 animate-pulse"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-amber-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-green-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
        </div>
    </div>
@endsection
