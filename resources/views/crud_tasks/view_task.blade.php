<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View a Task') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-300">
                    
                        <div class="flex flex-wrap -mx-3 mb-6">
                          <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                              Task Name
                            </label>
                            <label class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-first-name">{{ $tasks->task_name }} </label>
                          </div>
                          <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                              Task Description
                            </label>
                            <label class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">{{ $tasks->task_description }}</label>
                          </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-2">
                          <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-state">
                              Assign to Section:
                            </label>
                            <div class="relative ">
                                <form action="../assign_task/{{ $tasks->id }}" method="POST" class="flex">

                                    @method('PUT')
                                    @csrf

                                    <select class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="section_name">
                                        <option>None</option>
                                        @foreach ($sections as $section)
                                            <option>{{ $section->section_name }}</option>
                                        @endforeach
                                    </select>

                                    <input type="submit" value ="Assign" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-full ml-8">

                                </form>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>

                        @if(\Session::has('success'))

                        <div class="mt-5 bg-green-200 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-4" role="alert">
                            <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                                    <div>
                                        <p>{{ \Session::get('success') }}</p>
                                    </div>
                                </div>
                        </div>
                        @endif

                        @if(\Session::has('error'))

                        <div class="mt-5 bg-red-200 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-4" role="alert">
                            <div class="flex">
                                <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                                    <div>
                                        <p>{{ \Session::get('error') }}</p>
                                    </div>
                                </div>
                        </div>
                        @endif

                        <div class="divide-blue-300 divide-solid divide-y-4">
                            <h3 class="mt-9 text-3xl mb-3 ml-10">Comments Section</h3>
                        
                            @include('components.replys', ['comments' => $tasks->comments, 'id' => $tasks->id])
            
                            <hr />
                           </div>
            
                           <div>
                            <br><h5 class="text-xl italic">Leave a comment</h5>
                            <form method="post" action="{{ route('comment.add') }}">
                                @csrf
                                <div class="">
                                    <input required type="text" name="comment" />
                                    <input type="hidden" name="task_id" value="{{ $tasks->id }}" />
                                </div>
                                <div class="">
                                        <input required type="submit" class="bg-blue-500 hover:bg-blue-400 text-white font-bold py-2 px-4 border-b-4 border-blue-700 hover:border-blue-500 rounded mt-3" value="Add Comment" />
                                </div>
                            </form>
                           </div>

                </div>
            </div>
        </div>
    </div>
    @include('components/footer');
</x-app-layout>