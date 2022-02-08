<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        // пользователи
        //factory(App\Models\User::class, 30)->create();
        $data = [
            [
                'id' => 1,
                'username' => 'Анатолий',
                'email' => 'anatoliy@rezh.ru',
                'password' => bcrypt('T0dwZawJ'),
                'sort' => 1,
            ],
            [
                'id' => 2,
                'username' => 'Сергей',
                'email' => 'sergey@rezh.ru',
                'password' => bcrypt('O{oUfjXo'),
                'sort' => 2,
            ],
            [
                'id' => 3,
                'username' => 'Администратор',
                'email' => 'admin@rezh.ru',
                'password' => bcrypt('ji?B#5$H'),
                'sort' => 3,
            ],
            [
                'id' => 4,
                'username' => 'Менеджер',
                'email' => 'manager@rezh.ru',
                'password' => bcrypt('~uAqFi98'),
                'sort' => 4,
            ],
            [
                'id' => 5,
                'username' => 'Диспетчер',
                'email' => 'dispatcher@rezh.ru',
                'password' => bcrypt('BVPToqp?'),
                'sort' => 5,
            ],
            [
                'id' => 6,
                'username' => 'Оператор',
                'email' => 'operator@rezh.ru',
                'password' => bcrypt('1j9UC24j'),
                'sort' => 6,
            ],
            [
                'id' => 7,
                'username' => 'Мастер',
                'email' => 'master@rezh.ru',
                'password' => bcrypt('avrUR27x'),
                'sort' => 7,
            ],
            [
                'id' => 8,
                'username' => 'Электромонтер',
                'email' => 'working@rezh.ru',
                'password' => bcrypt('p@AZcqtv'),
                'sort' => 8,
            ],
            [
                'id' => 9,
                'username' => 'Пользователь-1',
                'email' => 'user1@rezh.ru',
                'password' => bcrypt('zDeI}hWv'),
                'sort' => 9,
            ],
            [
                'id' => 10,
                'username' => 'Пользователь-2',
                'email' => 'user2@rezh.ru',
                'password' => bcrypt('yI3o~Oyc'),
                'sort' => 10,
            ],
            [
                'id' => 11,
                'username' => 'Пользователь-3',
                'email' => 'user3@rezh.ru',
                'password' => bcrypt('rJN~iENE'),
                'sort' => 11,
            ],
            [
                'id' => 12,
                'username' => 'Златислава',
                'email' => 'zlatislava@rezh.ru',
                'password' => bcrypt('Dy$o1Kbz'),
                'sort' => 12,
            ],
            [
                'id' => 13,
                'username' => 'Тестер - 1',
                'email' => 'tester1@rezh.ru',
                'password' => bcrypt('6s~j7rHS'),
                'sort' => 13,
            ],
            [
                'id' => 14,
                'username' => 'Тестер - 2',
                'email' => 'tester2@rezh.ru',
                'password' => bcrypt('VvJIlUS~'),
                'sort' => 14,
            ],
        ];
        DB::table('user')->insert($data);

        // роли
        $data = [
            [
                'id' => 1,
                'name' => 'vendor',
                'comment' => 'Программист-разработчик',
                'import' => 2,
                'api' => 2,
                'tasks' => 2,
                'spravs' => 2,
                'settings' => 2,
                'users' => 2,
                'sort' => 1,
            ],
            [
                'id' => 2,
                'name' => 'admin',
                'comment' => 'Администратор - имеет все права',
                'import' => 2,
                'api' => 2,
                'tasks' => 2,
                'spravs' => 2,
                'settings' => 2,
                'users' => 2,
                'sort' => 2,
            ],
            [
                'id' => 3,
                'name' => 'manager',
                'comment' => 'Менеджер - как Администратор. Но не может создавать новых Пользователей',
                'import' => 2,
                'api' => 2,
                'tasks' => 2,
                'spravs' => 2,
                'settings' => 2,
                'users' => 1,
                'sort' => 3,
            ],
            [
                'id' => 4,
                'name' => 'dispatcher',
                'comment' => 'Диспетчер',
                'import' => 0,
                'api' => 0,
                'tasks' => 2,
                'spravs' => 2,
                'settings' => 1,
                'users' => 1,
                'sort' => 4,
            ],
            [
                'id' => 5,
                'name' => 'operator',
                'comment' => 'Оператор',
                'import' => 0,
                'api' => 0,
                'tasks' => 1,
                'spravs' => 1,
                'settings' => 0,
                'users' => 0,
                'sort' => 5,
            ],
            [
                'id' => 6,
                'name' => 'master',
                'comment' => 'Мастер',
                'import' => 0,
                'api' => 0,
                'tasks' => 1,
                'spravs' => 0,
                'settings' => 0,
                'users' => 0,
                'sort' => 6,
            ],
            [
                'id' => 7,
                'name' => 'working',
                'comment' => 'Электромонтер',
                'import' => 0,
                'api' => 0,
                'tasks' => 1,
                'spravs' => 0,
                'settings' => 0,
                'users' => 0,
                'sort' => 7,
            ],
            [
                'id' => 8,
                'name' => 'disabled',
                'comment' => 'Заблокированный Пользователь в черном списке. Не сможет больше войти в систему',
                'import' => 0,
                'api' => 0,
                'tasks' => 0,
                'spravs' => 0,
                'settings' => 0,
                'users' => 0,
                'sort' => 8,
            ],
        ];
        DB::table('admin_user_roles')->insert($data);

        // роли пользователей
        $data = [
            [
                'user_id' => '1',
                'user_role_id' => '1',
            ],
            [
                'user_id' => '2',
                'user_role_id' => '1',
            ],
            [
                'user_id' => '3',
                'user_role_id' => '2',
            ],
            [
                'user_id' => '4',
                'user_role_id' => '3',
            ],
            [
                'user_id' => '5',
                'user_role_id' => '4',
            ],
            [
                'user_id' => '6',
                'user_role_id' => '5',
            ],
            [
                'user_id' => '7',
                'user_role_id' => '6',
            ],
            [
                'user_id' => '8',
                'user_role_id' => '7',
            ],
            [
                'user_id' => '9',
                'user_role_id' => '7',
            ],
            [
                'user_id' => '10',
                'user_role_id' => '7',
            ],
            [
                'user_id' => '11',
                'user_role_id' => '7',
            ],
            [
                'user_id' => '12',
                'user_role_id' => '2',
            ],
            [
                'user_id' => '13',
                'user_role_id' => '6',
            ],
            [
                'user_id' => '14',
                'user_role_id' => '7',
            ],
        ];
        DB::table('admin_user_role_pivots')->insert($data);
    }
}
