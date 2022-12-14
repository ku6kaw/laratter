<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Tweet;
use Auth;
use App\Models\User;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tweets = Tweet::getAllOrderByUpdated_at();
        return view('tweet.index',compact('tweets'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('tweet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // バリデーション
        $validator = Validator::make($request->all(), [
            'tweet' => 'required | max:191',
            'description' => 'required',
        ]);
        // バリデーション:エラー
        if ($validator->fails()) {
            return redirect()
            ->route('tweet.create')
            ->withInput()
            ->withErrors($validator);
        }
        // create()は最初から用意されている関数
        // 戻り値は挿入されたレコードの情報
        //フォームから送信されてきたデータとユーザIDをマージし，DBにinsertする
        $data = $request->merge(['user_id' => Auth::user()->id])->all();
        //ddd($data);
        $image = $request->file('image');
        //ddd($image);
        $result = Tweet::create($data);
        //ddd($result);

        if($request->hasFile('image')){ //画像がアップロードされたか確認
            $path = \Storage::put('/public', $image); //storage/app/publicに画像を保存
            // $path = explode('/', $path); //画像のパスからpublicを除去
        }else{
            $path = null;
        }
        //ddd($path);
        $dir = 'image';
        
        // アップロードされたファイル名を取得
        $file_name = $request->file('image')->getClientOriginalName();

        // 取得したファイル名で保存
        $request->file('image')->storeAs('public/' . $dir, $file_name);

        $tweet = new Tweet();
        $tweet->user_id = $data['user_id'];
        $tweet->tweet = $data['tweet'];
        $tweet->description = $data['description'];
        $tweet->image = 'storage/' . $dir . '/' . $file_name;
        $tweet->save();


        // ルーティング「todo.index」にリクエスト送信（一覧ページに移動）
        return redirect()->route('tweet.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tweet = Tweet::find($id);
        return view('tweet.show', compact('tweet'));      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tweet = Tweet::find($id);
        return view('tweet.edit', compact('tweet'));
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
    //バリデーション
    $validator = Validator::make($request->all(), [
        'tweet' => 'required | max:191',
        'description' => 'required',
    ]);
    //バリデーション:エラー
    if ($validator->fails()) {
        return redirect()
        ->route('tweet.edit', $id)
        ->withInput()
        ->withErrors($validator);
    }
    //データ更新処理
    $result = Tweet::find($id)->update($request->all());
    return redirect()->route('tweet.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Tweet::find($id)->delete(); //データを削除
        return redirect()->route('tweet.index'); //一覧画面に移行
    }

    public function mydata()
    {
        // Userモデルに定義したリレーションを使用してデータを取得する．
        $tweets = User::query()
            ->find(Auth::user()->id)
            ->userTweets()
            ->orderBy('created_at','desc')
            ->get();
        //ddd($tweets);
        return view('tweet.myindex', compact('tweets'));

    }

    // public function iconUp(Request $request)
    // {
    //     //ddd($request);
    //     $dir = 'icon';

    //     $data = $request->merge(['user_id' => Auth::user()->id])->all();
    //     $icon = $request->file('icon');
    //     //$result = User::create($data);

    //     $path = \Storage::put('/public', $icon);

    //     // アップロードされたファイル名を取得
    //     $file_name = $request->file('icon')->getClientOriginalName();

    //     $users = new User();
    //     $users->icon = 'storage/' . $dir . '/' . $file_name;
    //     $users->save();

        

    //     return redirect()->route('tweet.myindex');
    // }

    public function timeline()
    {
        // フォローしているユーザを取得する
        $followings = User::find(Auth::id())->followings->pluck('id')->all();
        // 自分とフォローしている人が投稿したツイートを取得する
        $tweets = Tweet::query()
            ->where('user_id', Auth::id())
            ->orWhereIn('user_id', $followings)
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('tweet.index', compact('tweets'));
        }
}
