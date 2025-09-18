@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 font-serif">Create a Time Capsule</h1>
            <p class="text-gray-600 mt-2">Write a message to your future self. Choose when it should unlock.</p>
        </div>

        <form action="{{ route('capsules.store') }}" method="POST" enctype="multipart/form-data"
              class="bg-white rounded-xl shadow-lg p-8">
            @csrf

            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Capsule Title *
                </label>
                <input type="text"
                       id="title"
                       name="title"
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('title') border-red-500 @enderror"
                       value="{{ old('title') }}"
                       placeholder="Give your time capsule a meaningful title">
                @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Your Message *
                </label>
                <textarea id="content"
                          name="content"
                          required
                          rows="12"
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('content') border-red-500 @enderror"
                          placeholder="Write your message to the future. What do you want to tell your future self? What are your hopes, dreams, or current thoughts?">{{ old('content') }}</textarea>
                @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-600">
                    💡 Consider including: Current goals, how you're feeling today, predictions about the future,
                    advice to your future self, or memories you don't want to forget.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="unlock_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Unlock Date *
                    </label>
                    <input type="datetime-local"
                           id="unlock_date"
                           name="unlock_date"
                           required
                           min="{{ now()->addDays(1)->format('Y-m-d\TH:i') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('unlock_date') border-red-500 @enderror"
                           value="{{ old('unlock_date') }}">
                    @error('unlock_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Content Type
                    </label>
                    <select id="content_type"
                            name="content_type"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="text" {{ old('content_type') == 'text' ? 'selected' : '' }}>Text Only</option>
                        <option value="photo" {{ old('content_type') == 'photo' ? 'selected' : '' }}>Photos</option>
                        <option value="mixed" {{ old('content_type') == 'mixed' ? 'selected' : '' }}>Text & Photos</option>
                    </select>
                </div>
            </div>

            <div class="mb-8">
                <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">
                    Attachments (Optional)
                </label>
                <input type="file"
                       id="attachments"
                       name="attachments[]"
                       multiple
                       accept="image/*,.pdf"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="mt-2 text-sm text-gray-600">
                    You can attach photos or documents (Max 5MB each). Supported formats: JPG, PNG, PDF
                </p>
            </div>

            <!-- Quick Date Buttons -->
            <div class="mb-8">
                <p class="text-sm font-medium text-gray-700 mb-3">Quick Date Selection:</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <button type="button" onclick="setQuickDate(1, 'month')"
                            class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg font-medium transition-colors duration-200">
                        1 Month
                    </button>
                    <button type="button" onclick="setQuickDate(6, 'month')"
                            class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg font-medium transition-colors duration-200">
                        6 Months
                    </button>
                    <button type="button" onclick="setQuickDate(1, 'year')"
                            class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg font-medium transition-colors duration-200">
                        1 Year
                    </button>
                    <button type="button" onclick="setQuickDate(5, 'year')"
                            class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg font-medium transition-colors duration-200">
                        5 Years
                    </button>
                </div>
            </div>

            <div class="flex space-x-4">
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold text-lg transition-all duration-200 transform hover:scale-105">
                    🔐 Seal Time Capsule
                </button>
                <a href="{{ route('dashboard') }}"
                   class="px-8 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold text-lg transition-colors duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        function setQuickDate(amount, unit) {
            const now = new Date();
            let futureDate;

            if (unit === 'month') {
                futureDate = new Date(now.getFullYear(), now.getMonth() + amount, now.getDate(), now.getHours(), now.getMinutes());
            } else if (unit === 'year') {
                futureDate = new Date(now.getFullYear() + amount, now.getMonth(), now.getDate(), now.getHours(), now.getMinutes());
            }

            const isoString = futureDate.toISOString().slice(0, 16);
            document.getElementById('unlock_date').value = isoString;
        }
    </script>
@endsection
