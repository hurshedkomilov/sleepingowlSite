<?php

namespace Admin\Http\Sections;

use AdminColumn;
use AdminColumnEditable;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use App\Model\Review;
use Illuminate\Support\Facades\Storage;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Section;

/**
 * Class Galleries
 *
 * @property \App\Model\Review $model
 *
 * @see http://sleepingowladmin.ru/docs/model_configuration_section
 */
class Galleries extends Section implements Initializable
{
    /**
     * @see http://sleepingowladmin.ru/docs/model_configuration#ограничение-прав-доступа
     *
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = 'Галлерея';

    /**
     * @var string
     */
    protected $alias;

    /**
     * @return DisplayInterface
     */
    public function onDisplay()
    {
        return AdminDisplay::datatablesAsync()  /*->table()->with('users')*/
        ->setHtmlAttribute('class', 'table-primary')
            ->setColumns(
                AdminColumn::text('id', '#')->setWidth('30px'),
                AdminColumn::text('title', 'Название')->setWidth('200px'),
                AdminColumn::text('slug', 'Слаг'),
                AdminColumn::text('description', 'Текст отзыва'),
                AdminColumn::image('image', 'Обложка'),
                AdminColumn::count('images', 'Галлерея'),
                AdminColumnEditable::checkbox('published', 'Published')->setLabel('Опубликован'),
                AdminColumn::datetime('created_at', 'Добавлен')
            )->paginate(15);
    }

    /**
     * @param int $id
     *
     * @return FormInterface
     */
    public function onEdit($id)
    {
        return AdminForm::panel()->addBody([
            AdminFormElement::text('title', 'Название')->required(),
            AdminFormElement::text('slug', 'Слаг')->required(),
            AdminFormElement::ckeditor('description', 'Текст отзыва')->required(),
            AdminFormElement::image('image', 'Обложка'),
            AdminFormElement::images('images', 'Галлерея'),
            AdminFormElement::radio('published', 'Опубликовано')->setOptions(['0' => 'Не опубликовано', '1' => 'Опубликовано'])
                ->required(),
            AdminFormElement::datetime('created_at', 'Добавлен')->required(),
        ]);
    }

    /**
     * @return FormInterface
     */
    public function onCreate()
    {
        return $this->onEdit(null);
    }

    /**
     * @return void
     */
    public function onDelete($id)
    {
        $review = Review::findOrFail($id);
        Storage::disk('public')->delete(str_replace('storage/', '', $review->image));
        Storage::disk('public')->delete(str_replace('storage/', '', $review->images));

    }

    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation($priority = 20);
    }

    public function getIcon()
    {
        return 'fa fa-camera';
    }


    //заголовок для создания записи
    public function getCreateTitle()
    {
        return 'Создание галлереи';
    }
}