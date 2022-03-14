<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create a new Section') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-300">
                    <form action="store_section" method="POST">
                        
                        @csrf

                        <label for="section_name">Section Name: </label>
                        <input type="text" name="section_name" placeholder="Section Name:  " class="mt-8 sm:ml-12" required>
                        <br>
                        <label for="section_description">Section Description: </label>
                        <input type="text" name="section_description" placeholder="Description: " class="mt-8 ml-2 sm:h-16 sm:w-96" required>
                        <br>
                        <input type="submit" value ="Save" class="mt-8 bg-green-500 text-white font-bold py-2 px-4 rounded-full">
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('components/footer');
</x-app-layout>