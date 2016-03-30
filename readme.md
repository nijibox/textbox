# PHP Qiita-ism (仮)

PHPで実装された、プライベート環境向けQiita系サイトです

## 動かす

### 必要環境

* Laravel 5.2系が動くPHP
* RDBサービス(MySQL, SQLite)

### 動かし方

※ローカル環境向け

```bash
$ composer install
$ php artisan migrate
$ php artisan serve
```

## 運用環境デプロイ

masterブランチが更新されると、自動でwercker.comでCIが走り、テストも問題ない場合は問答無用でデプロイします。
※都合により、**マイグレーションは自動で動きません** (#4 参照)
その関係上、masterブランチへのダイレクトなpushはできなくなっています。

### 事前に

なるべく次のことは確認してください

* PHPUnitで既存テストが一通り通ること
* artisanでのビルトインサーバで簡単な動作ができていること

## ブランチ運用(暫定版)

* master
    * リリース対象ブランチ。基本的にこのブランチはいつ運用環境にデプロイされてしまってもいいようにしておく
* release/XXX
    * リリース後ブランチ。ドキュメント修正や簡単な文言調整はこちらで？
* develop/XXX
    * 開発ブランチ
* feature/XXX/YYY
    * 開発ブランチ(単機能)

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
