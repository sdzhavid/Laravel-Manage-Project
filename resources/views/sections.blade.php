<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("All Sections") }}
        </h2>
    </x-slot>

    <div class="flex flew-row-reverse">
        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full mt-4 ml-20">
            <a href="create/section" class="btn">New Section</a>
        </button>
    </div>

    @if(\Session::has('success'))

    <div class="bg-green-200 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-4 mt-12" role="alert">
        <div class="flex">
        <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
        <div>
            <p>{{ \Session::get('success') }}</p>
        </div>
        </div>
    </div>
    @endif
    @if(\Session::has('error'))

    <div class="bg-red-200 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-4 mt-12" role="alert">
        <div class="flex">
        <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
        <div>
            <p>{{ \Session::get('error') }}</p>
        </div>
        </div>
    </div>
    @endif

    @if (@isset($sections))
        @if (count($sections) > 0)
            <div class="ml-20 mt-8 mb-8 grid grid-cols-3 gap-x-1 gap-y-12">
                @foreach ($sections as $section)
                <div class="max-w-sm rounded overflow-hidden shadow-lg w-80">
                    <div class="m-5">
                        <div class="flex flex-wrap">
                            <span class="font-bold flex-auto">{{ $section->section_name }}</span>
                            <p class="">Tasks №: {{ $section->tasks->count() }}</p>
                        </div>
                        <span class="block text-gray-500 text-sm">{{ $section->section_description }}</span>
                        <table>
                            <thead>
                                <th>
                                    <form method="POST" action="destroy/section/{{ $section->id }}">
                                        @method('delete')
                                        @csrf

                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded-full">
                                            Delete
                                        </button>
                                    </form>
                                </th> 
                                <th>
                                    <button class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded-full">
                                        <a href="edit_section/{{ $section->id }}">Edit</a>
                                    </button>
                                </th>
                                <th>
                                    <button class="mt-2 bg-blue-300 hover:bg-blue-400 text-white font-bold py-1 px-3 rounded-full">
                                        <a href="view_section/{{ $section->id }}">View</a>
                                    </button>
                                </th>
                            </thead>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    @endif

    @include('components/footer');
</x-app-layout>