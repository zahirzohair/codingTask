<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Tag;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $isdone=null, $search=null, $order=null, $tag=null)
    {
        $baseQuery = Note::query();

        if($request->isdone || $request->isdone == '0'){
             $baseQuery->where('is_done', $request->isdone);   
        } 
        if($request->search){
            $baseQuery->where(function($query) use ($request){
                $query->where('title', 'LIKE', '%'.$request->search.'%')
                     ->orWhere('content', 'LIKE', '%'.$request->search.'%');
            });
        } 

        if($request->tag){
            $baseQuery->whereHas('tags', function($query) use ($request){
                $query->whereIn('name', $request->tag);
            });
        }

        if($request->order == 'created_at'){
            $baseQuery->orderBy('created_at', $request->direction ?? 'ASC');

        }else if($request->order == 'due_date'){
             $baseQuery->orderBy('due_date', $request->direction ?? 'ASC');

        }

        return $baseQuery->with('tags')->get(); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'is_done' => 'required'
        ]);

        if(!empty($request->get('due_date'))){
            $request->validate([
                'due_date' =>'date_format:Y-m-d'
            ]);
        }

        $note = Note::create([
            'title' => $request->get('title'),
            'content'  => $request->get('content'),
            'due_date' => $request->get('due_date'),
            'is_done'  => $request->get('is_done'),

        ]); 

        if($note){

            // Get list of tags
            $tagNames = $request->get('name');
            $tagIds = [];

            if(!empty($tagNames)){

                foreach($tagNames as $tagName){
                    $tag = Tag::firstOrCreate(['name'=>$tagName]);

                    if($tag){
                      $tagIds[] = $tag->id;
                    }
                }
                $note->tags()->sync($tagIds);
            }            
        }

        return  $note->with('tags')->latest()->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $note = Note::find($id);

        $request->validate([
            'title' => 'required',
            'is_done' => 'required'
        ]);

        if(!empty($request->get('due_date'))){
            $request->validate([
                'due_date' =>'date_format:Y-m-d'
            ]);
        }
    
        $note->update([
            'title' => $request->get('title'),
            'content'  => $request->get('content'),
            'due_date' => $request->get('due_date'),
            'is_done'  => $request->get('is_done'),
        ]); 

        $note->tags()->delete();

        if($note){

            // Get list of tags 
            $tagNames = $request->get('name');         
            foreach($tagNames as $tagName){
                $tag = $note->tags()->create(['name'=>$tagName]);
                
            }
        }

        return $note;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Note::destroy($id); 
    }


}
