<?php

namespace App\Http\Controllers;

use App\Models\newsModel;
use App\Models\usersModel;
use Illuminate\Http\Request;

use function App\Helpers\is_mobile;

class newsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->input('type');

        $data = newsModel::get()->toArray();

        $res['status_code'] = 1;
        $res['message'] = "Success";
        $res['data'] = $data;

        return is_mobile($type, "news", $res, 'view');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $request->session()->get('user_id');
        $updateData = $request->except('_method', '_token', 'submit');

        $type = $request->input('type');

        $file_name = "";
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalname = $file->getClientOriginalName();
            $name = "ad-file" . '_' . date('YmdHis');
            $ext = \File::extension($originalname);
            $file_name = $name . '.' . $ext;
            $path = $file->storeAs('public/', $file_name);
            $updateData['file'] = $file_name;
        }

        newsModel::insert($updateData);

        usersModel::whereRaw("1 = 1")->update(['news_notify' => 1]);

        $res['status_code'] = 1;
        $res['message'] = "Insert successfully";

        return is_mobile($type, "news.index", $res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $type = $request->input('type');

        $editData = newsModel::where(['id' => $id])->get()->toArray();

        $res['status_code'] = 1;
        $res['message'] = "Success";
        $res['editData'] = $editData[0];

        return is_mobile($type, 'news', $res, 'view');
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
        $updateData = $request->except('_method', '_token', 'submit');

        $type = $request->input('type');

        $file_name = "";
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalname = $file->getClientOriginalName();
            $name = "ad-file" . '_' . date('YmdHis');
            $ext = \File::extension($originalname);
            $file_name = $name . '.' . $ext;
            $path = $file->storeAs('public/', $file_name);
            $updateData['file'] = $file_name;
        }

        newsModel::where(['id' => $id])->update($updateData);

        usersModel::whereRaw("1 = 1")->update(['news_notify' => 1]);

        $res['status_code'] = 1;
        $res['message'] = "Updated successfully";

        return is_mobile($type, "news.index", $res);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $type = $request->input('type');

        newsModel::where(['id' => $id])->delete();

        $res['status_code'] = 1;
        $res['message'] = "Deleted Successfully";

        return is_mobile($type, 'news.index', $res);
    }

    public function newsIndex(Request $request)
    {
        $type = $request->input('type');
        $user_id = $request->session()->get('user_id');

        $data = newsModel::orderBy('id', 'desc')->get()->toArray();

        usersModel::where(['id' => $user_id])->update(['news_notify' => 0]);

        $res['status_code'] = 1;
        $res['message'] = "Success";
        $res['data'] = $data['0'];

        return is_mobile($type, "pages.news", $res, 'view');
    }
}