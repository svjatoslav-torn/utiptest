<?php

namespace app\models\forms;

use app\models\Token;
use Yii;
use yii\base\Model;
use app\models\User;
use app\models\Post;
// use yii\imagine\Image;
use Imagine\Imagick\Imagine;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class PostForm extends Model
{
    public $title;
    public $body;
    public $status = 0;
    public $img_base64 = null;
    public $category_id;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            ['title', 'required', 'message' => 'В теле запроса нет Заголовка поста'],
            ['body', 'required', 'message' => 'В теле запроса нет Контента'],
            ['category_id', 'required', 'message' => 'Передайте идентификатор категории'],
            ['title', 'string', 'max' => 255],
            ['status', 'boolean'],
            ['img_base64', 'string'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    // public function validatePassword($attribute, $params)
    // {
    //     if (!$this->hasErrors()) {
    //         $user = $this->getUser();

    //         //Проверять хешированный пароль - готово
    //         if (!$user || !$user->validatePassword($this->password)) {
    //             $this->addError($attribute, 'Не верный логин или пароль. Проверьте учетные данные!');
    //         }
    //     }
    // }

    public function cookingBeforeSave(Post $modelPost = null)
    {
        $modelPost = $modelPost ?? new Post();

        $modelPost->title = $this->title;
        $modelPost->body = $this->body;
        $modelPost->status = $this->status;
        $modelPost->category_id = $this->category_id;

        if($this->img_base64){
            $modelPost->img_path =  $this->getImagePath($modelPost->img_path);
        }
        
        if($modelPost->isNewRecord){ // Только если новая запись
            $modelPost->author_id = Yii::$app->user->identity->id;
        }
        
        return $modelPost;
    }

    /**
     *  Check format base64, save image, return part of url or full url
     *
     *  @return string
     */
    public function getImagePath(?string $currentPath)
    {
        // Типа проверка - такая себе)))
        if(!strpos($this->img_base64, 'base64')){
            if($currentPath){
                return $currentPath;
            }
            return null;
        }

        $pattern = '/data:image\/(.+);base64,(.*)/';
        preg_match($pattern, $this->img_base64, $matches);

        $path = 'i/post/' . Yii::$app->security->generateRandomString(20) . "." . $matches[1];

        if(file_put_contents($path, base64_decode($matches[2]))){
            return $path;
        }
        return $currentPath;

    }

}