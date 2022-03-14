    <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-300">
                    
                    <div class="text-center sm:text-right mt-2 mb-10">

                        @if(\Session::has('success'))

                            <div class="bg-green-200 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-4" role="alert">
                                <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                                <div>
                                    <p>{{ \Session::get('success') }}</p>
                                </div>
                                </div>
                            </div>
                        @endif
                        @if(\Session::has('error'))

                        <div class="bg-red-200 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-4" role="alert">
                            <div class="flex">
                            <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                            <div>
                                <p>{{ \Session::get('error') }}</p>
                            </div>
                            </div>
                        </div>
                    @endif
                    
                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full">
                            <a href="create" class="btn">New Task</a>
                        </button>
                    </div>

                    <form action="{{ route('searchTask') }}" method="GET" class="flex flex-col mb-4">
                        <div class="flex">
                            <label for="">Search by: </label>
                            <div class="ml-14 flex space-x-96 justify-center">
                                <p>Task Name</p>
                                <p>Creator</p>
                                <p>From Date Created Backwards</p>
                            </div>
                        </div>
                        <div class="ml-10 flex space-x-48">
                            <input type="search" name="task_query_name" placeholder="Task Name" class="mt-3 w-72 bg-white h-10 px-5 pr-10 rounded-full text-sm focus:outline-none">
                            <input type="search" name="task_query_creator" placeholder="Creator Name" class="mt-3 w-72 bg-white h-10 px-5 pr-10 rounded-full text-sm focus:outline-none">
                            <input type="date" name="task_query_date_created" placeholder="Creation Date" class="mt-3 w-72 bg-white h-10 px-5 pr-10 rounded-full text-sm focus:outline-none">
                        </div> 
                        <button type="submit" class="mt-3 w-28 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full">
                            <div class="flex">
                                Search
                                <svg class="ml-3 text-white h-4 w-4 fill-current text-center" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                                viewBox="0 0 56.966 56.966" style="enable-background:new 0 0 56.966 56.966;" xml:space="preserve"
                                width="512px" height="512px">
                                <path
                                d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" />
                                </svg>
                            </div>
                        </button>
                    </form>

                    @if(@isset($tasks))

                    <table class="table-fixed">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="lg:px-6 lg:py-2 hover:underline cursor-pointer hover:text-blue-300">@sortablelink('id', 'ID')</th>
                                <th class="lg:px-6 lg:py-2 hover:underline cursor-pointer hover:text-blue-300">@sortablelink('task_name', 'Task Name')</th>
                                <th class="lg:px-10 lg:py-2 hover:underline cursor-pointer hover:text-blue-300">@sortablelink('created_at', 'Created At')</th>
                                <th class="lg:px-6 lg:py-2 w-1/4">Operation</th>
                                <th class="lg:px-6 lg:py-2 hover:underline cursor-pointer hover:text-blue-300">@sortablelink('user.name', 'Creator Name')</th>
                                <th class="lg:px-6 lg:py-2 w-1/4">Assigned to Section?</th>
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
                                    <td class="px-6 py-2 text-center">{{ $task->user->name}}</td>
                                    <td class="px-6 py-2 text-center">
                                        @if($task->section_id == null)
                                        <p>❌</p>
                                        @else
                                        <p>✅</p> 
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    @endif
                    {!! $tasks->appends(\Request::except('page'))->render() !!}
                </div>
            </div>
        </div>
    </div>
    @include('components/footer');
</x-app-layout>
