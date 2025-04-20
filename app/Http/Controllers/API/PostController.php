<?php

namespace App\Http\Controllers\API;

use App\Models\post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['Posts']   = post::all();
        return $this->sendResponse($data,'All Post Data');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validateuser=Validator::make(
            $request->all(),[
                'title'=>'required',
                'description'=>'required',
                'image'=>'required|mimes:png,jpg,jepg,gif'
            ]
            );
           
            if($validateuser->fails()){
                return $this->sendError('validation error',$validateuser->errors()->all(),401);
            }
            $imageName=$request->image;
            $ext=$imageName->getClientOriginalExtension();
            $imageExt=time().'.'.$ext;
            $imageName->move(public_path().'/uploads',$imageExt);


            $post = post::create([
                'title'=>$request->title,
                'description'=>$request->description,
                'image'=>$imageExt
            ]);
            return $this->sendResponse($post,"Post Data Updated");

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data['post']= post::select('id','title','description','image')
        ->where(['id'=>$id])->get();
        return $this->sendResponse($data,"Single Post Data");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validateuser=Validator::make(
            $request->all(),[
                'title'=>'required',
                'description'=>'required',
                'image'=>'required|mimes:png,jpg,jepg,gif'
            ]
            );
           
            if($validateuser->fails()){
                return $this->sendError('validation error',$validateuser->errors()->all(),401);
            }
            $postdata= post::select('id','image')->where(['id'=>$id])->get();
            //return  $postdata;
            if($request->image != '')
            {   
                $path= public_path().'/uploads';
                if($postdata[0]->image !='' && $postdata[0]->image != null)
                {   
                    $old_file=$path.$postdata[0]->image;
                    if(file_exists($old_file)){
                        unlink($old_file);
                    }
                }
                
                    $imageName=$request->image;
                    $ext=$imageName->getClientOriginalExtension();
                    $imageExt=time().'.'.$ext;
                    $imageName->move(public_path().'/uploads/',$imageExt);
            
            }
            else
            {
                //echo "hey"; die;
                $imageExt= $postdata[0]->image; 
                 
            }
            

             
            $post = post::where(['id'=>$id])->update([
                'title'=>$request->title,
                'description'=>$request->description,
                'image'=>$imageExt
            ]);
            return $this->sendResponse($post,"Post Data Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       // echo "hiii"; die;
        $imagePath=post::select('image')->where(['id'=> $id])->get();
        //return $imagePath; die;
        $imagePath[0]['image']='1745003411.jpg';
        $path= public_path().'/uploads/'.$imagePath[0]['image'];
        //return $path; die;
        unlink($path);
        $post=post::where('id',$id)->delete();
        return $this->sendResponse($post,"Post Data Deleted");
    }
}
