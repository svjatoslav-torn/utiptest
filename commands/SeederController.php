<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\resource\Category;
use app\models\resource\Post;
use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SeederController extends Controller
{
    public function actionIndex()
    {
        $this->actionUsers();
        $this->actionCategories();
        $this->actionPosts();
    }

    /**
     * Посев данных в категории, по умолчанию 5шт если не задано иное
     * @param string $message the message to be echoed.
     * @return string
     */
    public function actionCategories(int $count = 5)
    {
        $faker = \Faker\Factory::create();
        
        for ( $i = 0; $i < $count; $i++ )
        {
            $category = new Category();
            $category->setIsNewRecord(true);
            $category->name = $faker->text(10);
            $category->save();           
        }

        echo 'В таблице "Категории" успешно создано '.$count.' записей';
        echo PHP_EOL;
    }

    /**
     * Посев данных в юзеров, по умолчанию 2шт если не задано иное
     * @param string $message the message to be echoed.
     * @return string
     */
    public function actionUsers(int $count = 2)
    {
        $faker = \Faker\Factory::create();
        
        for ( $i = 0; $i < $count; $i++ )
        {
            $user = new User();
            $user->setIsNewRecord(true);
            $user->name = $faker->text(10);
            $user->email = $faker->email();
            $user->setPassword($faker->text(8));
            $user->generateAuthKey();
            $user->save();    
            
            $authManager = \Yii::$app->getAuthManager();
            $role = $authManager->getRole('user');
            $authManager->assign($role, $user->id); 
        }

        echo 'В таблице "Пользователи" успешно создано '.$count.' записей. Роль - обычный пользователь!';
        echo PHP_EOL;
    }


    /**
     * Посев данных в посты, по умолчанию 20шт если не задано иное
     * @param string $message the message to be echoed.
     * @return string
     */
    public function actionPosts(int $count = 20)
    {
        $faker = \Faker\Factory::create();

        $categories = Category::find()->limit(5)->asArray()->all();
        $users = User::find()->limit(4)->asArray()->all();
        // var_dump(array_rand($users, 1));die;
        
        for ( $i = 0; $i < $count; $i++ )
        {
            $post = new Post();
            $post->setIsNewRecord(true);

            $post->title = $faker->text(10);
            $post->body = $faker->paragraph(5);
            $post->category_id = $categories[array_rand($categories)]['id'];
            $post->author_id = $users[array_rand($users)]['id'];
            $post->status = rand(0, 1);
            $post->save();           
        }

        echo 'В таблице "Посты" успешно создано '.$count.' записей';
        echo PHP_EOL;
    }
}
