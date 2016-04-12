<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\User;


class AdminSet extends Command
{
    /**
     * 管理者権限付与コマンド
     *
     * @var string
     */
    protected $signature = 'admin:set {email}';

    /**
     * 説明文
     *
     * @var string
     */
    protected $description = '指定したメールアドレスのユーザーに管理者権限を付与する';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userEmail = $this->argument('email');
        $user =  User::where('email', $userEmail)->first();
        if ( is_null($user) ) {
            $this->error('指定されたメールアドレスは見つかりませんでした');
            return false;
        }
        $user->is_admin = true;
        $user->save();
        return true;
    }
}
