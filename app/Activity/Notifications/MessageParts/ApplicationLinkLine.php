<?php

namespace BookStack\Activity\Notifications\MessageParts;

use Illuminate\Contracts\Support\Htmlable;
use Stringable;

/**
 * A bullet point list of content, where the keys of the given list array
 * are bolded header elements, and the values follow.
 */
class ApplicationLinkLine implements Htmlable, Stringable
{
    public function __construct(
       
    ) {
    }

    public function toHtml(): string
     {
    //     $list = [];
    //     foreach ($this->list as $header => $content) {
    //         $list[] = '<strong>' . '*' . '</strong> ' . e($content);
    //     }
        return 'Click on the links above to go directly to the section or access the Extranet here 👉🏼' . '<a href="' . e('https://ignitx.org/extranet/') . '"> <b>' .'https://ignitx.org/extranet/' . ' </b></a>';
    }

    public function __toString(): string
    {
        return '';
    }
}
