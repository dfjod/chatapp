<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex flex-col items-center">
                <div class="max-w-xl w-full">                    
                    <section>
                        <form method="post" action="{{ route('chats.update', $chat) }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                @can('adminActions', $chat)
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $chat->name)" required autofocus placeholder="{{ __('Name')}}"/>
                                @endcan
                                @cannot('adminActions', $chat)
                                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $chat->name)" required autofocus placeholder="{{ __('Name')}}" disabled/>
                                @endcannot
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
                            <div class="flex gap-3">
                                @can('adminActions', $chat)                                        
                                    <div class="flex items-center gap-4">
                                        <x-primary-button>
                                            {{ __('Update') }}
                                        </x-primary-button>
                                    </div>
                                @endcan

                                <div>
                                    <a href="{{ route('chats.show', $chat) }}">
                                        <x-secondary-button>{{ __('Cancel')}}</x-secondary-button>
                                    </a>
                                </div>
                                @if (session('status') === 'chat-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >{{ __('Updated.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section> 
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex flex-col items-center">
                <div class="max-w-xl w-full">                    
                    <section>
                        <div class="p-4 max-w-md bg-white rounded-lg border shadow-md sm:p-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold leading-none text-gray-900">{{ __('Chat participants') }}</h3>
                            </div>
                            <div class="flow-root">
                                <ul role="list" class="divide-y divide-gray-200">
                                    @foreach ($chat->users as $user)
                                        <li class="py-3 sm:py-4">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-base font-medium text-gray-900 truncate">
                                                        {{ $user->username }}
                                                    </p>
                                                </div>
                                                <div class="inline-flex items-center text-base font-semibold text-gray-900">
                                                    @switch($chat->getUserRole($user->id))
                                                        @case(0)
                                                            {{__('Owner')}}
                                                            @break
                                                        @case(1)
                                                            {{__('Admin')}}
                                                            @break
                                                        @case(2)
                                                            {{__('Regular')}}
                                                        @break
                                                        @default
                                                            {{__('Error')}}
                                                    @endswitch
                                                </div>
                                                <div class="hidden sm:flex sm:items-center sm:ms-6">
                                                    <x-dropdown align="right" width="48">
                                                        <x-slot name="trigger">
                                                            <button class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-900 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50" type="button">
                                                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                                    <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                                                </svg>
                                                            </button>
                                                        </x-slot>
                                                        <x-slot name="content">                                  
                                                                @can('adminActions', $chat)
                                                                    @can('ownerActions', $chat)
                                                                    <!-- Promote user -->
                                                                    <form method="POST" action="{{ route('chats.promoteUser', compact('chat', 'user')) }}">
                                                                        @csrf
                                            
                                                                        <x-dropdown-link :href="route('chats.promoteUser', compact('chat', 'user'))"
                                                                                onclick="event.preventDefault();
                                                                                            this.closest('form').submit();">
                                                                            {{ __('Promote') }}
                                                                        </x-dropdown-link>
                                                                    </form>

                                                                    <!-- Demote user -->
                                                                    <form method="POST" action="{{ route('chats.demoteUser', compact('chat', 'user')) }}">
                                                                        @csrf
                                            
                                                                        <x-dropdown-link :href="route('chats.demoteUser', compact('chat', 'user'))"
                                                                                onclick="event.preventDefault();
                                                                                            this.closest('form').submit();">
                                                                            {{ __('Demote') }}
                                                                        </x-dropdown-link>
                                                                    </form>
                                                                    @endcan
                                                                    <!-- Kick user -->
                                                                    <form method="POST" action="{{ route('chats.kickUser', compact('chat', 'user')) }}">
                                                                        @csrf
                                            
                                                                        <x-dropdown-link :href="route('chats.kickUser', compact('chat', 'user'))"
                                                                                onclick="event.preventDefault();
                                                                                            this.closest('form').submit();">
                                                                            {{ __('Kick') }}
                                                                        </x-dropdown-link>
                                                                    </form>
                                                                @endcan
                                                            </x-slot>
                                                    </x-dropdown>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @can('adminActions', $chat)
                            <form method="post" action="{{ route('chats.addUser', $chat) }}" class="mt-6 space-y-6">
                                @csrf

                                <div>
                                    <x-input-label for="username" :value="__('Add a user to the chat')" />
                                    <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" required autofocus placeholder="{{ __('Username')}}"/>
                                    <x-input-error class="mt-2" :messages="$errors->get('username')" />
                                </div>
                                <div class="flex gap-3">
                                    <div class="flex items-center gap-4">
                                        <x-primary-button>{{ __('Add') }}</x-primary-button>
                                    </div>

                                    <div>
                                        <a href="{{ route('chats.show', $chat) }}">
                                            <x-secondary-button>{{ __('Cancel')}}</x-secondary-button>
                                        </a>
                                    </div>
                                    @if (session('status') === 'user-added')
                                        <p
                                            x-data="{ show: true }"
                                            x-show="show"
                                            x-transition
                                            x-init="setTimeout(() => show = false, 2000)"
                                            class="text-sm text-gray-600"
                                        >{{ __('User added.') }}</p>
                                    @endif
                                </div>
                            </form>
                        @endcan
                    </section> 
                </div>
            </div>
            @can('ownerActions', $chat)
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg flex flex-col items-center">
                    <div class="max-w-xl w-full">                    
                        <section class="space-y-6">
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    {{ __('Delete chat') }}
                                </h2>
                            </header>
                        
                            <x-danger-button
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'confirm-chat-deletion')"
                            >{{ __('Delete Chat') }}</x-danger-button>
                        
                            <x-modal name="confirm-chat-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                                <form method="post" action="{{ route('chats.destroy', $chat) }}" class="p-6">
                                    @csrf
                                    @method('delete')
                        
                                    <h2 class="text-lg font-medium text-gray-900">
                                        {{ __('Are you sure you want to delete the chat?') }}
                                    </h2>
                        
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ __('Once the chat is deleted, all of its resources and data will be permanently deleted.') }}
                                    </p>
                        
                                    <div class="mt-6 flex justify-end">
                                        <x-secondary-button x-on:click="$dispatch('close')">
                                            {{ __('Cancel') }}
                                        </x-secondary-button>
                        
                                        <x-danger-button class="ms-3">
                                            {{ __('Delete Chat') }}
                                        </x-danger-button>
                                    </div>
                                </form>
                            </x-modal>
                        </section>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>