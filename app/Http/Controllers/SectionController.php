<?php

namespace App\Http\Controllers;
use App\Models\Section;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class SectionController extends Controller
{
    /**
     * Display a listing of all the Section resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function sections()
    {
        $sections = Section::all();
        return view('sections')->with('sections', $sections);
    }

    
    /**
     * Show the form for creating a new Section resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view('crud_sections/create_section');
    }

    /**
     * Remove the specified Section resource from storage.
     *
     * @param  \App\Models\Int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $section_entry = Section::find($id);

        if($section_entry->user_id !== Auth::id()){
            return redirect()->route('sections')->with('error', 'This section belongs to ' . $section_entry->user->name . '! You can\'t delete it!');
        }
        $section_entry = Section::destroy($id);

        return redirect()->route('sections')->with('success', 'Section has been removed!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $section_entry = Section::find($id);

        if($section_entry == null){
            return view('validation_error/data_not_found');
        }
        if($section_entry->user_id !== Auth::id()){
            return redirect()->route('sections')->with('error', 'This section belongs to ' . $section_entry->user->name . '! You can\'t access it!');
        }
        return view('crud_sections/edit_section')->with('sections', $section_entry);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $task
     * @param  \App\Models\Int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Section $section, $id, Request $request)
    {

        switch ($request->method()) {
            case 'GET':
                return redirect()->route('crud_sections/edit_section', [$id]);
            default:
                $section = Section::find($id);

                if($section->user_id !== Auth::id()){
                    return redirect()->route('sections')->with('error', 'This section belongs to ' . $section->user->name . '! You can\'t edit it!');
                }
                request()->validate([
                    'section_name' => 'required',
                    'section_description' => 'required'
                ]);        
        
                $section->update([
                    'section_name' => request('section_name'),
                    'section_description' => request('section_description')
                ]);
                
                return redirect()->route('sections')->with('success', 'The task: "' . $section->section_name . '" has been edited!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $section = Section::find($id);
        
        if($section == null){
            return view('validation_error/data_not_found');
        }

        $tasks = Task::where('section_id', '=', $section->id)->get();
        
        return view('crud_sections/view_section')->with('sections', $section)->with('tasks', $tasks);
    }

    /**
     * Store a newly created Section resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'section_name' => 'required',
            'section_description' => 'required'
        ]);

        $section = new Section();

        $section->user()->associate(Auth::user());
        $section->section_name = $request->input('section_name');
        $section->section_description = $request->input('section_description');
        $section->save();

        return redirect()->route('sections')->with('success',  'The section: "' . $section->section_name . '" has been created!');
    }
}
