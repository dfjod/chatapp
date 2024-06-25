<x-app-layout>
    <div class="m-10">
        @foreach ($messages as $message)
            <div class="p-4 mb-5 bg-blue-200 w-fit rounded-lg">
                <p>{{ $message->content }}</p>
            </div>
        @endforeach
    </div>
</x-app-layout>
