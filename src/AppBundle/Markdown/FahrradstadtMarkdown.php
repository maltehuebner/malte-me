<?php declare(strict_types=1);

namespace AppBundle\Markdown;

use cebe\markdown\block as block;
use cebe\markdown\inline as inline;
use cebe\markdown\Markdown;
use cebe\markdown\Parser;

class FahrradstadtMarkdown extends Markdown
{
    use block\TableTrait;
    use block\FencedCodeTrait;
    use inline\StrikeoutTrait;
    use inline\UrlLinkTrait;

    public $enableNewlines = true;
    public $html5 = true;

    protected function renderText($text)
    {
        if ($this->enableNewlines) {
            $br = $this->html5 ? "<br>\n" : "<br />\n";
            return strtr($text[1], ["  \n" => $br, "\n" => $br]);
        } else {
            return parent::renderText($text);
        }
    }
}
