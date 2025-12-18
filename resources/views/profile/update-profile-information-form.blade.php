<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Informações do Perfil') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Atualize as informações do seu perfil e endereço de e-mail.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null, photoWasUploaded: false}" 
                 x-init="
                    // Listen for when profile is saved
                    $wire.on('saved', () => {
                        if (photoWasUploaded) {
                            // Reload page after photo is saved to show updated photo
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            photoPreview = null;
                            photoName = null;
                        }
                        photoWasUploaded = false;
                    });
                    
                    // Also listen for navigation menu refresh
                    $wire.on('refresh-navigation-menu', () => {
                        if (photoWasUploaded) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        } else {
                            photoPreview = null;
                            photoName = null;
                        }
                        photoWasUploaded = false;
                    });
                    
                    // Fallback: Watch for when photo upload completes
                    Livewire.hook('message.processed', (message, component) => {
                        if (photoWasUploaded && message.updateQueue.length === 0) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 2000);
                        }
                    });
                 " 
                 class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model="photo"
                            accept="image/jpeg,image/png,image/jpg,image/gif"
                            x-ref="photo"
                            x-on:change="
                                    if ($refs.photo.files && $refs.photo.files[0]) {
                                        photoName = $refs.photo.files[0].name;
                                        photoWasUploaded = true;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                    }
                            " />

                <x-label for="photo" value="{{ __('Foto') }}" />

                @php
                    $currentUser = auth()->user()->fresh();
                    $photoUrl = $currentUser->profile_photo_path 
                        ? asset('storage/' . $currentUser->profile_photo_path) 
                        : null;
                @endphp

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    @if($photoUrl)
                        <img src="{{ $photoUrl }}?v={{ time() }}" alt="{{ $currentUser->name }}" class="rounded-full size-20 object-cover border-2 border-gray-200" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="rounded-full size-20 flex items-center justify-center {{ $currentUser->initial_color }} border-2 border-gray-200" style="display: none;">
                            <span class="text-white font-semibold text-3xl">{{ strtoupper(substr($currentUser->name, 0, 1)) }}</span>
                        </div>
                    @else
                        <div class="rounded-full size-20 flex items-center justify-center {{ $currentUser->initial_color }} border-2 border-gray-200">
                            <span class="text-white font-semibold text-3xl">{{ strtoupper(substr($currentUser->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;" x-cloak>
                    <img :src="photoPreview" alt="Preview" class="rounded-full size-20 object-cover border-2 border-gray-200">
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Selecionar Nova Foto') }}
                </x-secondary-button>

                @if ($currentUser->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remover Foto') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
                
                <!-- Upload Progress -->
                <div wire:loading wire:target="photo" class="mt-2">
                    <div class="text-sm text-gray-600">{{ __('Enviando foto...') }}</div>
                </div>
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Nome') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Seu endereço de e-mail não foi verificado.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Clique aqui para reenviar o e-mail de verificação.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('Um novo link de verificação foi enviado para o seu endereço de e-mail.') }}
                    </p>
                @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Salvo.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo,updateProfileInformation">
            <span wire:loading.remove wire:target="photo,updateProfileInformation">{{ __('Salvar') }}</span>
            <span wire:loading wire:target="photo,updateProfileInformation">{{ __('Salvando...') }}</span>
        </x-button>
    </x-slot>
</x-form-section>
