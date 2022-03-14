<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(Auth::user()->name . "'s Tasks") }}
        </h2>
    </x-slot>

    @if(@isset($tasks))

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-300">
                    <table class="table-fixed">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="lg:px-6 lg:py-2 hover:underline cursor-pointer hover:text-blue-300">@sortablelink('id', 'ID')</th>
                                <th class="lg:px-6 lg:py-2 hover:underline cursor-pointer hover:text-blue-300">@sortablelink('task_name', 'Task Name')</th>
                                <th class="lg:px-10 lg:py-2 hover:underline cursor-pointer hover:text-blue-300">@sortablelink('created_at', 'Created At')</th>
                                <th class="lg:px-6 lg:py-2 w-1/4">Operation</th>
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
                                                    <form method="POST" action="destroy/{{ $task->id }}">
                                                        @method('delete')
                                                        @csrf

                                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded-full">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </th> 
                                                <th>
                                                    <button class="mt-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded-full">
                                                        <a href="edit_task/{{ $task->id }}">Edit</a>
                                                    </button>
                                                </th>
                                                <th>
                                                    <button class="mt-2 bg-blue-300 hover:bg-blue-400 text-white font-bold py-1 px-3 rounded-full">
                                                        <a href="view_task/{{ $task->id }}">View</a>
                                                    </button>
                                                </th>
                                            </thead>
                                        </table>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('components/footer');
</x-app-layout>