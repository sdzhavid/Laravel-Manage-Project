<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View a Section') }}

            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-300">

                            <label for="section_name">Section Name: {{ $sections->section_name }}</label>
                            <br><br>
                            <label for="section_description">Section Description: {{ $sections->section_description }}</label>
                            <br>

                        </div>
                    </div>
                </div>
            </div>

            @if(@isset($tasks))

            <table class="table-fixed">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="lg:px-6 lg:py-2 hover:underline cursor-pointer hover:text-blue-300">@sortablelink('id', 'ID')</th>
                        <th class="lg:px-6 lg:py-2 hover:underline cursor-pointer hover:text-blue-300">@sortablelink('task_name', 'Task Name')</th>
                        <th class="lg:px-10 lg:py-2 hover:underline cursor-pointer hover:text-blue-300">@sortablelink('created_at', 'Created At')</th>
                        <th class="lg:px-6 lg:py-2 w-1/4">Operation</th>
                        <th class="lg:px-6 lg:py-2 hover:underline cursor-pointer hover:text-blue-300">@sortablelink('user.name', 'Creator Name')</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($tasks) > 0)
                        @foreach($tasks as $task)
                        <tr class="whitespace-nowrap">
                            <td class="px-6 py-2 text-center">{{ $task->id }}</td>
                            <td class="px-6 py-2 text-center">{{ $task->task_name }}</td>
                            <td class="px-10 py-2 text-center">{{ $task->created_at }}</td>
                            <td class="px-10 py-2 content-center">
                                <table>
                                    <thead>
                                        <th>
                                            <form method="POST" action="../destroy/{{ $task->id }}">
                                                @method('delete')
                                                @csrf

                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded-full">
                                                    Delete
                                                </button>
                                            </form>
                                        </th> 
                                        <th>
                                            <button class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded-full">
                                                <a href="../edit_task/{{ $task->id }}">Edit</a>
                                            </button>
                                        </th>
                                        <th>
                                            <button class="mt-2 bg-blue-300 hover:bg-blue-400 text-white font-bold py-1 px-3 rounded-full">
                                                <a href="../view_task/{{ $task->id }}">View</a>
                                            </button>
                                        </th>
                                    </thead>
                                </table>
                            </td>
                            <td class="px-6 py-2 text-center">{{ $task->user->name}}</td>
                        @endforeach
                    @endif
                </tbody>
            </table>
            @endif
        </h2>
    </x-slot>
    @include('components/footer');
</x-app-layout>