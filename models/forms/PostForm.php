<?php
namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\Post;

/**
 * Форма для данных поста
 * 
 * @property string $title
 * @property string $body
 * @property bool|int $status
 * @property string|null $img_base64
 * @property int $category_id
 * @property string $tags
 * 
 * @package app\models\forms
 * @since 1.0.0.0
 */
class PostForm extends Model
{
    public string $title;
    public $body;
    public $status = 0;
    public $img_base64 = null; //Сюда передаем с фронта mime base64
    public $category_id;
    public $tags = '';

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            ['title', 'required', 'message' => 'В теле запроса нет Заголовка поста'],
            ['body', 'required', 'message' => 'В теле запроса нет Контента'],
            ['category_id', 'required', 'message' => 'Передайте идентификатор категории'],
            ['title', 'string', 'max' => 255],
            ['status', 'boolean'],
            ['img_base64', 'string'],
            ['tags', 'string'],
        ];
    }

    /**
     * @param Post|null $modelPost
     * 
     * @return Post
     */
    public function cookingBeforeSave(Post $modelPost = null)
    {
        $modelPost = $modelPost ?? new Post();

        $modelPost->title = $this->title;
        $modelPost->body = $this->body;
        $modelPost->status = $this->status;
        $modelPost->category_id = $this->category_id;

        if ($this->img_base64) {
            $modelPost->img_path = $this->getImagePath($modelPost->img_path);
        }
        
        if ($modelPost->isNewRecord) {
            $modelPost->author_id = Yii::$app->user->identity->id;
        }
        
        return $modelPost;
    }

    /**
     *  Check format base64, save image, return part of url or full url
     * @param string|null $currentPath
     * 
     * @return string
     */
    public function getImagePath(string|null $currentPath): string
    {
        if (!strpos($this->img_base64, 'base64')) {
            if ($currentPath) {
                return $currentPath;
            }
            return null;
        }

        $pattern = '/data:image\/(.+);base64,(.*)/';
        preg_match($pattern, $this->img_base64, $matches);

        $path = 'i/post/' . Yii::$app->security->generateRandomString(20) . "." . $matches[1];

        if (file_put_contents($path, base64_decode($matches[2]))) {
            return $path;
        }
        
        return $currentPath;
    }
}