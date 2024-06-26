<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex flex-col items-center">
                <div class="max-w-xl w-full">                    
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Create a new chat') }}
                            </h2>
                        </header>
                    
                        <form method="post" action="{{ route('chats.store') }}" class="mt-6 space-y-6">
                            @csrf

                            <div>
                                <x-input-label for="username" :value="__('Who are you creating chat with')" />
                                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" required autofocus placeholder="{{ __('Username')}}"/>
                                <x-input-error class="mt-2" :messages="$errors->get('username')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Create') }}</x-primary-button>
                            </div>
                        </form>
                    </section> 
                </div>
            </div>
        </div>
    </div>
</x-app-layout>