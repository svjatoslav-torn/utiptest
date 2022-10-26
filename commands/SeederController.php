<?php
namespace app\commands;

use app\models\resource\Category;
use app\models\resource\Comment;
use app\models\resource\Post;
use app\models\Tag;
use app\models\User;
use yii\console\Controller;

/**
 * Консольный контроллер. Посев данных в таблицы.
 */
class SeederController extends Controller
{
    // Индексный. Базовый посев
    public function actionIndex()
    {
        $this->actionUsers();
        $this->actionCategories();
        $this->actionTags();
        $this->actionPosts();
        $this->actionComments();

        // Вывод справки
        echo PHP_EOL;
        echo PHP_EOL;
        echo '/////   СПРАВКА    /////';
        echo PHP_EOL;
        echo PHP_EOL;
        echo 'php yii create-user  -  создаст обычного пользователя';
        echo PHP_EOL;
        echo 'php yii create-user admin  -  создаст Админа';
        echo PHP_EOL;
        echo PHP_EOL;
        echo 'http://hostname/auth/register  -  доступна регистрация обычного пользователя. Поля: string $name, string $email, string $password';
        echo PHP_EOL;
        echo 'http://hostname/authlogin  -  получение Bearer токена. Поля: string $email, string $password';
        echo PHP_EOL;
        echo PHP_EOL;
        echo 'php yii roles/revoke  -  удалить роль у юзера';
        echo PHP_EOL;
        echo 'php yii roles/assign  -  добавить роль у юзера';
        echo PHP_EOL;
        echo PHP_EOL;
    }

    /**
     * Посев данных в категории, по умолчанию 5шт если не задано иное
     * @param string $count
     * @return void
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
     * @param string $count
     * @return void
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
     * @param string $count
     * @return void
     */
    public function actionPosts(int $count = 20)
    {
        $faker = \Faker\Factory::create();

        $categories = Category::find()->limit(5)->asArray()->all();
        $users = User::find()->limit(4)->asArray()->all();
        
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

    /**
     * Посев комментариев к постам, по умолчанию 100шт если не задано иное
     * @param string $count
     * @return void
     */
    public function actionComments(int $count = 100)
    {
        $faker = \Faker\Factory::create();

        $posts = Post::find()->limit(10)->asArray()->all();
        $users = User::find()->limit(4)->asArray()->all();
        
        for ( $i = 0; $i < $count; $i++ )
        {
            $comment = new Comment();
            $comment->setIsNewRecord(true);

            $comment->body = $faker->paragraph(5);
            $comment->user_id = $users[array_rand($users)]['id'];
            $comment->post_id = $posts[array_rand($posts)]['id'];
            $comment->save();           
        }

        echo 'В таблице "Комментарии" успешно создано '.$count.' записей';
        echo PHP_EOL;
    }

    /**
     * Посев тегов к постам, по умолчанию 10шт если не задано иное
     * @param string $count
     * @return void
     */
    public function actionTags(int $count = 10)
    {
        $faker = \Faker\Factory::create();
        
        for ( $i = 0; $i < $count; $i++ )
        {
            $tag = new Tag();
            $tag->setIsNewRecord(true);
            $tag->name = $faker->word();
            $tag->save();           
        }

        echo 'В таблице "Тэги" успешно создано '.$count.' записей';
        echo PHP_EOL;
    }
}
