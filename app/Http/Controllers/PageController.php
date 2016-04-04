<?php

namespace App\Http\Controllers;

use Validator;
use Auth;
use DB;
use App\Http\Requests;
use App\Page;
use Illuminate\Http\Request;


class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 指定されたページを表示する。
     * 存在しない記事IDや閲覧権がない記事IDが指定されたらHTTPステータス404を返す。
     *
     * @todo テストコード無し
     * @param $articleId ページID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function getOne($pageId)
    {
        $page = Page::find($pageId);
        // If article is not found, abort request.
        if ( is_null($page) ) {
            return abort(404);
        }

        // Render article
        return view('page.view', [
            'page' => $page,
            'parser' => new \App\Extra\QiitaMarkdown(),
        ]);
    }
}
