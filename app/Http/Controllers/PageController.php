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
    const ITEMS_PER_PAGE = 20;

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
     * @param $pageId ページID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function getOne($pageId)
    {
        $page = Page::find($pageId);
        // If page is not found, abort request.
        if ( is_null($page) ) {
            return abort(404);
        }

        // Render page
        return view('page.view', [
            'page' => $page,
            'parser' => new \App\Extra\QiitaMarkdown(),
        ]);
    }

    /**
     * 記事投稿用フォームを表示させる
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function newForm()
    {
        return view('page.form', [
                'page' => new page(['status' => 'draft']),
            ]
        );
    }

    /**
     * 記事編集用フォームを表示させる。
     * 指定された記事IDが存在しない場合、編集権がない場合はHTTPステータス404を返す。
     *
     * @param $pageId 記事ID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function editForm($pageId)
    {
        $page = page::find($pageId);
        // If page is not found, abort request.
        if ( is_null($page) ) {
            return abort(404);
        }
        return view('page.form', [
            'page' => $page,
        ]);

    }

    /**
     * 記事を登録(新規,変更両方)する
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function postOne(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'pageTitle' => 'required',
                'pageBody' => 'required',
                'pageHeadline' => 'required',
                'pageLocation' => 'required',
            ]
        );
        // If validate is NG, return form view
        if ( $validator->fails() ) {
            return view('page.form', ['errors' => $validator->errors()]);
        }

        // Post validated values
        $params = [
            'title' => $request->input('pageTitle'),
            'body' => $request->input('pageBody'),
            'headline' => $request->input('pageHeadline'),
            'location' => $request->input('pageLocation'),
        ];
        if ($request->input('_pageId')) {
            $page = Page::find($request->input('_pageId'));
            if ( is_null( $page ) ) {
                return abort(404);
            }
            foreach ($params as $attr => $val) {
                $page->{$attr} = $val;
            }
            $message = 'page is updated';
        } else {
            $page = page::create($params);
            $message = 'New page is created';
        }
        DB::transaction(function () use ($page, $request, $message)
        {
            $page->save();
            $request->session()->flash('flash_message', $message);
        });
        return redirect(route('get_page_single', ['pageId' => $page->id]));
    }

    /**
     * 表示可能な記事リストを表示する。
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getList()
    {
        $pages = Page::paginate(static::ITEMS_PER_PAGE);

        // TODO: 記事がない場合の処理が必要？

        // Render pages
        return view('page.list', [
            'pages' => $pages,
        ]);
    }

}
