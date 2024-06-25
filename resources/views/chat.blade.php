<x-app-layout>
    <div class="flex flex-col h-screen">
        <div class="flex-1 overflow-y-auto px-10 py-5">
            @foreach ($messages as $message)
                <div class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 border-gray-200 {{ auth()->user()->id === $message->user->id ? 'bg-blue-100 ml-auto' : 'bg-gray-200' }} rounded-xl mb-5 mt-5 first:mt-0 last:mb-0">
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <span class="text-sm font-semibold text-gray-900">{{ $message->user->username }}</span>
                        <span class="text-sm font-normal text-gray-500">{{ date("H:i", strtotime($message->created_at)) }}</span>
                    </div>
                    <p class="text-sm font-normal py-2.5 text-gray-900">{{ $message->content }}</p>
                </div>
            @endforeach
        </div>
        <div class="px-10 pb-5 pt-0">
            <form>
                <label for="chat" class="sr-only">Your message</label>
                <div class="flex items-center py-2 px-3 rounded-lg">
                    <textarea id="chat" rows="1" class="block mx-4 p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Your message..."></textarea>
                    <button type="submit" class="inline-flex justify-center p-2 text-blue-600 rounded-full cursor-pointer hover:bg-blue-100">
                        <svg class="w-6 h-6 rotate-90" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
