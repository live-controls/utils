<?php

namespace LiveControls\Utils;

class BBCodes
{
    /**
     * Transforms any bbcode inside of the string to the html variant
     *
     * @param string $string
     * @return string
     */
    public static function transform(string $string, bool $nonDocument = false): string
    {
        $newstring = preg_replace("/\[b](.+?)\[\/b]/ms", "<strong>$1</strong>", $string);
        $newstring = preg_replace("/\[i](.+?)\[\/i]/ms", "<em>$1</em>", $newstring);
        $newstring = preg_replace("/\[s](.+?)\[\/s]/ms", "<s>$1</s>", $newstring);
        $newstring = preg_replace("/\[u](.+?)\[\/u]/ms", "<u>$1</u>", $newstring);
        $newstring = preg_replace("/\[img](.+?)\[\/img]/ms", "<img src=\"$1\" class=\"img-fluid\">", $newstring);
        $newstring = preg_replace("/\[img=(.*)x(.*)](.+?)\[\/img]/ms", "<img src=\"$3\" width=\"$1\" height=\"$2\">", $newstring);
        $newstring = preg_replace("/\[center](.+?)\[\/center]/ms", "<div align=\"center\">$1</div>", $newstring);
        $newstring = preg_replace("/\[justify](.+?)\[\/justify]/ms", "<div align=\"justify\">$1</div>", $newstring);
        $newstring = preg_replace("/\[right](.+?)\[\/right]/ms", "<div align=\"right\">$1</div>", $newstring);
        $newstring = preg_replace("/\[ul](.+?)\[\/ul]/ms", "<ul>$1</ul>", $newstring);
        $newstring = preg_replace("/\[ol](.+?)\[\/ol]/ms", "<ol>$1</ol>", $newstring);
        $newstring = preg_replace("/\[li](.+?)\[\/li]/ms", "<li>$1</li>", $newstring);
        $newstring = preg_replace("/\[url=([^\]]*)](.+?)\[\/url]/ms", "<a href=\"$1\" target=\"_blank\">$2</a>", $newstring);
        $newstring = preg_replace("/\[url](.+?)\[\/url]/ms", "<a href=\"$1\" target=\"_blank\">$1</a>", $newstring);
        $newstring = preg_replace("/\[email=([^\]]*)](.+?)\[\/email]/ms", "<a href=\"mailto:$1\" target=\"_blank\">$2</a>", $newstring);
        $newstring = preg_replace("/\[size=([^\]]*)](.+?)\[\/size]/ms", "<font size=\"$1\">$2</font>", $newstring);
        $newstring = preg_replace("/\[color=([^\]]*)](.+?)\[\/color]/ms", "<font style=\"color:$1\">$2</font>", $newstring);
        $newstring = preg_replace("/\[hr]/ms", "<hr>", $newstring);
        $newstring = preg_replace("/\[sub](.+?)\[\/sub]/ms", "<sub>$1</sub>", $newstring);
        $newstring = preg_replace("/\[sup](.+?)\[\/sup]/ms", "<sup>$1</sup>", $newstring);
        if($nonDocument){
            $newstring = preg_replace("/\[yt](.+?)\[\/yt]/ms", "<iframe tyoe=\"text/html\" width=\"200\" height=\"200\" src=\"http://www.youtube.com/embed/$\" frameborder=\"0\" />", $newstring);
        }
        return $newstring;
    }
}
