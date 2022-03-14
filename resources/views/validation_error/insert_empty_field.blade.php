<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('') }}
        </h2>
    </x-slot>
    <div class="py-12 content-center">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-300">
                    <strong class="text-5xl text-center">You can't submit an empty field!</strong>
                </div>
            </div>
        </div>
    </div>
    @include('components/footer');
</x-app-layout>