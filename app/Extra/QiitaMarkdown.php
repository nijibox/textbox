<?php
/***************************************
 * Qiita スタイル Markdownを実装する
 **************************************/
namespace App\Extra;

use \cebe\markdown\GithubMarkdown;


class QiitaMarkdown extends GithubMarkdown
{
    public function __construct()
    {
        // parent::__construct();
        $this->enableNewlines = true;
    }
}
