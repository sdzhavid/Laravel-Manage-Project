<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of all the Task resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $tasks = Task::sortable()->paginate(3);
        return view('dashboard')->with('tasks', $tasks);
    }

    /**
     * Display all of the tasks which belong to the authenticated user
     * 
     * @return \Illuminate\Http\Response
     */
    public function my_tasks()
    {
        $tasks = Task::sortable()
                        ->where('user_id', '=', Auth::user()->id)
                        ->with('user')
                        ->paginate(5);

        return view('my_tasks')->with('tasks', $tasks);
    }

    /**
     * Show the form for creating a new Task resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('crud_tasks/create_task');
    }

    /**
     * Store a newly created Task resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'task_name' => 'required',
            'task_description' => 'required'
        ]);

        $task = new Task();

        $task->user()->associate(Auth::user());
        $task->task_name = $request->input('task_name');
        $task->task_description = $request->input('task_description');
        $task->save();

        return redirect()->route('dashboard')->with('success',  'The task: "' . $task->task_name . '" has been created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        $sections = Section::all();

        if($task == null){
            return view('validation_error/data_not_found');
        }
        return view('crud_tasks/view_task')->with('tasks', $task)->with('sections', $sections);
    }

    /**
     * Assigns a Task to a Section
     * 
     * @param \Illuminate\Support\Facades\Request   $request
     * @param int   $task_id
     * @return \Illuminate\Http\Response
     */
    public function assignToSection(Request $request, $task_id)
    {
        switch ($request->method()) {
            case 'GET':
                return redirect()->route('crud_tasks.view_task', [$task_id]);
    
            default:
                $taskToBeAssigned = Task::find($task_id);
                $sectionName = request('section_name');
        
                $section = Section::where('section_name', 'LIKE', '%'. $sectionName . '%')->first();
        
                if ($section == null){
                    $taskToBeAssigned->section_id = null;
                    $taskToBeAssigned->save();
        
                    return redirect()->route('dashboard')->with('success', 'The task: "' . $taskToBeAssigned->task_name .
                                                            '" isn\'t assigned to any section anymore!');
                }

                $taskToBeAssigned->section_id = $section->id;
                $taskToBeAssigned->save();
        
        
                return redirect()->route('dashboard')->with('success', 'The task: "' . $taskToBeAssigned->task_name .
                                                            '" has been assigned to the Section: "' . $section->section_name .'"!');

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task_entry = Task::find($id);

        if($task_entry == null){
            return view('validation_error/data_not_found');
        }
        if($task_entry->user_id !== Auth::id()){
            return redirect()->route('dashboard')->with('error', 'This task belongs to ' . $task_entry->user->name . '! You can\'t access it!');
        }
        return view('crud_tasks/edit_task')->with('tasks', $task_entry);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @param  \App\Models\Task  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Task $task, $id, Request $request)
    {

        switch ($request->method()) {
            case 'GET':
                return redirect()->route('crud_tasks/edit_task', [$id]);
            default:
                $task = Task::find($id);

                if($task->user_id !== Auth::id()){
                    return redirect()->route('dashboard')->with('error', 'This task belongs to ' . $task->user->name . '! You can\'t edit it!');
                }
                request()->validate([
                    'task_name' => 'required',
                    'task_description' => 'required'
                ]);        

                $task->update([
                    'task_name' => request('task_name'),
                    'task_description' => request('task_description')
                ]);
                
                return redirect()->route('dashboard')->with('success', 'The task: "' . $task->task_name . '" has been edited!');
        }
    }

    /**
     * Remove the specified Task resource from storage.
     *
     * @param  \App\Models\Int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task_entry = Task::find($id);

        if($task_entry->user_id !== Auth::id()){
            return redirect()->route('dashboard')->with('error', 'This task belongs to ' . $task_entry->user->name . '! You can\'t delete it!');
        }
        $task_entry = Task::destroy($id);

        return redirect()->route('dashboard')->with('success', 'Task has been removed!');
    }

    /**
     * Seaches for tasks by name/creator or created_at date.
     * 
     * @param \Illuminate\Support\Facades\Request
     * @return \Illuminate\Http\Response
     */
    public function searchTask(Request $request)
    {
        if(!$request->filled('task_query_name') && !$request->filled('task_query_creator') &&!$request->filled('task_query_date_created'))
        {

            return redirect()->route('dashboard')
                    ->with('tasks', Task::all())
                    ->with('success', 'No search field was selected. Showing all tasks.');

        } else if($request->filled('task_query_name') && !$request->filled('task_query_creator') &&!$request->filled('task_query_date_created'))
        {

            $search_text = $request->task_query_name;
                
            $tasks_new = Task::where('task_name', 'LIKE', '%'.$search_text.'%')
                                ->with('user')
                                ->paginate(3);

            return view('dashboard')->with('tasks', $tasks_new);

        } else if($request->filled('task_query_name') && $request->filled('task_query_creator') &&!$request->filled('task_query_date_created'))
        {

            $search_text_name = $request->task_query_name;
            $search_text_creator = $request->task_query_creator;

            $tasks_new = Task::where('task_name', 'LIKE', '%'.$search_text_name.'%')
                                ->join('users', 'users.id', '=', 'tasks.user_id')
                                ->where('users.name', 'LIKE', '%'.$search_text_creator.'%')
                                ->paginate(3);
        
             return view('dashboard')->with('tasks', $tasks_new);

        } else if($request->filled('task_query_name') && $request->filled('task_query_creator') && $request->filled('task_query_date_created'))
        {

            $search_name = $request->task_query_name;
            $search_creator = $request->task_query_creator;
            $search_date = $request->task_query_date_created;

            if(date($search_date) > Carbon::now()){

                return redirect()->route('dashboard')
                        ->with('tasks', Task::all())
                        ->with('success', 'Date selected is larger than the current date. Please select the current date or lower. Showing all tasks');
            }

            $tasks_new = Task::join('users', 'users.id', '=', 'tasks.user_id')
                                ->where('users.name', 'LIKE', '%'.$search_creator.'%')
                                ->whereDate('tasks.created_at', '<=', $search_date)
                                ->where('tasks.task_name', 'LIKE', '%'.$search_name.'%')
                                ->paginate(3);

            return view('dashboard')->with('tasks', $tasks_new);
            
        } else if(!$request->filled('task_query_name') && $request->filled('task_query_creator') &&!$request->filled('task_query_date_created'))
        {

            $search_text = $request->task_query_creator;
                
            $tasks_new = Task::join('users', 'users.id', '=', 'tasks.user_id')
                            ->where('users.name', 'LIKE', '%'.$search_text.'%')
                            ->paginate(3);
                            
            return view('dashboard')->with('tasks', $tasks_new);

        } else if(!$request->filled('task_query_name') && $request->filled('task_query_creator') &&$request->filled('task_query_date_created'))
        {

            
            $search_creator = $request->task_query_creator;
            $search_date = $request->task_query_date_created;

            if(date($search_date) > Carbon::now()){

                return redirect()->route('dashboard')
                        ->with('tasks', Task::all())
                        ->with('success', 'Date selected is larger than the current date. Please select the current date or lower. Showing all tasks');

            }
            
            $tasks_new = Task::join('users', 'users.id', '=', 'tasks.user_id')
                                    ->where('users.name', 'LIKE', '%'.$search_creator.'%')
                                    ->whereDate('tasks.created_at', '<=', date($search_date))
                                    ->paginate(3);

            return view('dashboard')->with('tasks', $tasks_new);

        } else if($request->filled('task_query_name') && !$request->filled('task_query_creator') &&$request->filled('task_query_date_created'))
        {

            $search_date = $request->task_query_date_created;
            $search_name = $request->task_query_name;

            if(date($search_date) > Carbon::now()){

                return redirect()->route('dashboard')
                        ->with('tasks', Task::all())
                        ->with('success', 'Date selected is larger than the current date. Please select the current date or lower. Showing all tasks');

            }

            $tasks_new = Task::where('task_name', 'LIKE', '%'.$search_name.'%')
                                ->whereDate('created_at', '<=', date($search_date))
                                ->with('user')
                                ->paginate(3);

            return view('dashboard')->with('tasks', $tasks_new);

        } else if(!$request->filled('task_query_name') && !$request->filled('task_query_creator') &&$request->filled('task_query_date_created'))
        {

            $search_date = $request->task_query_date_created;
            
            if(date($search_date) > Carbon::now()){

                return redirect()->route('dashboard')
                        ->with('tasks', Task::all())
                        ->with('success', 'Date selected is larger than the current date. Please select the current date or lower. Showing all tasks');

            }
            $tasks_new = Task::whereDate('created_at', '<=', date($search_date))
                                ->paginate(3);

            return view('dashboard')->with('tasks', $tasks_new);
        }
        
    }
}
