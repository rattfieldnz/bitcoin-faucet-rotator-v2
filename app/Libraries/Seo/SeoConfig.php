<?php
/**
 * Created by PhpStorm.
 * User: robattfield
 * Date: 8/09/2017
 * Time: 21:49
 */

namespace App\Libraries\Seo;

/**
 * Class SeoConfig
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Libraries\Seo
 */
class SeoConfig
{
    public $title = '';
    public $titleAppend = '';
    public $titleSeparator = '';
    public $description = '';
    public $keywords = [];
    public $publishedTime = '';
    public $modifiedTime = '';
    public $authorName = '';
    public $currentUrl = '';
    public $imagePath = '';
    public $categoryDescription = '';

    public function pageTitle()
    {
        $separator = !empty($this->titleSeparator) ? $this->titleSeparator : env('APP_TITLE_SEPARATOR');
        $append = !empty($this->titleAppend) ? $this->titleAppend : env('APP_TITLE_APPEND');
        if (empty($this->title)) {
            return $append;
        }
        return $this->title . " " . $separator . " " . $append;
    }
}
