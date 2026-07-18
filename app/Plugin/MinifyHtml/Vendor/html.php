<?php
/**
 * Class Minify_HTML
 * @package Minify
 */

/**
 * Compress HTML
 *
 * This is a heavy regex-based removal of whitespace, unnecessary comments and
 * tokens. IE conditional comments are preserved. There are also options to have
 * STYLE and SCRIPT blocks compressed by callback functions.
 *
 * A test suite is available.
 *
 * @package Minify
 * @author Stephen Clay <steve@mrclay.org>
 */
class Minify_HTML {
    /**
     * @var boolean
     */
    protected $_jsCleanComments = true;

    /**
     * "Minify" an HTML page
     *
     * @param string $html
     *
     * @param array $options
     *
     * 'cssMinifier' : (optional) callback function to process content of STYLE
     * elements.
     *
     * 'jsMinifier' : (optional) callback function to process content of SCRIPT
     * elements. Note: the type attribute is ignored.
     *
     * 'xhtml' : (optional boolean) should content be treated as XHTML1.0? If
     * unset, minify will sniff for an XHTML doctype.
     *
     * @return string
     */
    public static function minify($html, $options = array()) {
        $min = new self($html, $options);
        return $min->process();
    }


    /**
     * Create a minifier object
     *
     * @param string $html
     *
     * @param array $options
     *
     * 'cssMinifier' : (optional) callback function to process content of STYLE
     * elements.
     *
     * 'jsMinifier' : (optional) callback function to process content of SCRIPT
     * elements. Note: the type attribute is ignored.
     *
     * 'jsCleanComments' : (optional) whether to remove HTML comments beginning and end of script block
     *
     * 'xhtml' : (optional boolean) should content be treated as XHTML1.0? If
     * unset, minify will sniff for an XHTML doctype.
     */
    public function __construct($html, $options = array())
    {
        $this->_html = str_replace("\r\n", "\n", trim($html));
        if (isset($options['xhtml'])) {
            $this->_isXhtml = (bool)$options['xhtml'];
        }
        if (isset($options['cssMinifier'])) {
            $this->_cssMinifier = $options['cssMinifier'];
        }
        if (isset($options['jsMinifier'])) {
            $this->_jsMinifier = $options['jsMinifier'];
        }
        if (isset($options['jsCleanComments'])) {
            $this->_jsCleanComments = (bool)$options['jsCleanComments'];
        }
    }


    /**
     * Minify the markeup given in the constructor
     * 
     * @return string
     */
    public function process()
    {
        if ($this->_isXhtml === null) {
            $this->_isXhtml = (false !== strpos($this->_html, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML'));
        }
        
        $this->_replacementHash = 'MINIFYHTML' . md5($_SERVER['REQUEST_TIME']);
        $this->_placeholders = array();

        // remove HTML comments (not containing IE conditional comments).
        $this->_html = preg_replace_callback(
            '/<!--([\\s\\S]*?)-->/'
            ,array($this, '_commentCB')
            ,$this->_html);
                    
        // replace SCRIPTs (and minify) with placeholders
        $this->_html = preg_replace_callback(
            '/(\\s*)<script(\\b[^>]*?>)([\\s\\S]*?)<\\/script>(\\s*)/i'
            ,array($this, '_removeScriptCB')
            ,$this->_html);
        
        if(@Router::getRequest()->is('json')){
            $this->_html = $this->_removeJsCB($this->_html);
        }

        // replace STYLEs (and minify) with placeholders
        $this->_html = preg_replace_callback(
            '/\\s*<style(\\b[^>]*>)([\\s\\S]*?)<\\/style>\\s*/i'
            ,array($this, '_removeStyleCB')
            ,$this->_html);
        
        // replace PREs with placeholders
        $this->_html = preg_replace_callback('/\\s*<pre(\\b[^>]*?>[\\s\\S]*?<\\/pre>)\\s*/i'
            ,array($this, '_removePreCB')
            ,$this->_html);
        
        // replace TEXTAREAs with placeholders
        $this->_html = preg_replace_callback(
            '/\\s*<textarea(\\b[^>]*?>[\\s\\S]*?<\\/textarea>)\\s*/i'
            ,array($this, '_removeTextareaCB')
            ,$this->_html);
        
        // trim each line.
        // @todo take into account attribute values that span multiple lines.
        $this->_html = preg_replace('/^\\s+|\\s+$/m', '', $this->_html);
        
        // remove ws around block/undisplayed elements
        $this->_html = preg_replace('/\\s+(<\\/?(?:area|base(?:font)?|blockquote|body'
            .'|caption|center|col(?:group)?|dd|dir|dl|dt|fieldset|form'
            .'|frame(?:set)?|h[1-6]|head|hr|html|legend|li|link|map|menu|meta'
            .'|ol|opt(?:group|ion)|p|param|t(?:able|body|head|d|h||r|foot|itle)'
            .'|ul)\\b[^>]*>)/i', '$1', $this->_html);

        $this->_html = preg_replace('/\\s+(<\\/?(div)\\b[^>]*>)/i', ' $1', $this->_html);
        
        // remove ws outside of all elements
        $this->_html = preg_replace(
            '/>(\\s(?:\\s*))?([^<]+)(\\s(?:\s*))?</'
            ,'>$1$2$3<'
            ,$this->_html);
        
        // use newlines before 1st attribute in open tags (to limit line lengths)
        //$this->_html = preg_replace('/(<[a-z\\-]+)\\s+([^>]+>)/i', "$1\n$2", $this->_html);
        
        // fill placeholders
        $this->_html = str_replace(
            array_keys($this->_placeholders)
            ,array_values($this->_placeholders)
            ,$this->_html
        );
        // issue 229: multi-pass to catch scripts that didn't get replaced in textareas
        $this->_html = str_replace(
            array_keys($this->_placeholders)
            ,array_values($this->_placeholders)
            ,$this->_html
        );

        // Minifier encore plus
        $this->_html = $this->minify_html($this->_html);

        $replace = array(
            '=""'   => '',
            "=''"   => '',
        );
        //$this->_html = str_ireplace(array_keys($replace), array_values($replace), $this->_html);


        //remove optional ending tags (see http://www.w3.org/TR/html5/syntax.html#syntax-tag-omission )
        $remove = array(
            '</colgroup>', '</dd>', '</dt>', '</option>', '</li>', '</tbody>', '</td>', '</th>', /*'</tr>'*/ 
        );
        $this->_html = str_ireplace($remove, '', $this->_html);

        return $this->_html;
    }





    public static function minify_html($input) {
    if(trim($input) === "") return $input;
    // Remove extra white-space(s) between HTML attribute(s)
    $input = preg_replace_callback('#<([^\/\s<>!]+)(?:\s+([^<>]*?)\s*|\s*)(\/?)>#s', function($matches) {
        return '<' . $matches[1] . preg_replace('#([^\s=]+)(\=([\'"]?)(.*?)\3)?(\s+|$)#s', ' $1$2', $matches[2]) . $matches[3] . '>';
    }, str_replace("\r", "", $input));
    // Minify inline CSS declaration(s)
    if(strpos($input, ' style=') !== false) {
        $input = preg_replace_callback('#<([^<]+?)\s+style=([\'"])(.*?)\2(?=[\/\s>])#s', function($matches) {
            return '<' . $matches[1] . ' style=' . $matches[2] . self::minify_css($matches[3]) . $matches[2];
        }, $input);
    }


    $HtmlAttrRem = array('type="text/css"', 'type="text/javascript"', ' alt=""');
    $HtmlAttrRem = Hash::merge($HtmlAttrRem, str_replace('"', '', $HtmlAttrRem));
    $input = str_replace($HtmlAttrRem, '', $input);


    //remove redundant (white-space) characters
    $replace = array(
        //remove tabs before and after HTML tags
        '/\>[^\S ]+/s'   => '>', // strip whitespaces after tags, except space
        '/[^\S ]+\</s'   => '<', // strip whitespaces before tags, except space
        //'/(\s)+/s' => '\\1', // shorten multiple whitespace sequences

        //shorten multiple whitespace sequences; keep new-line characters because they matter in JS!!!
        '/([\t ])+/s'  => ' ',
        //remove leading and trailing spaces
        '/^([\t ])+/m' => '',
        '/([\t ])+$/m' => '',
        // remove JS line comments (simple only); do NOT remove lines containing URL (e.g. 'src="http://server.com/"')!!!
        '~//[a-zA-Z0-9 ]+$~m' => '',
        //remove empty lines (sequence of line-end and white-space characters)
        '/[\r\n]+([\t ]?[\r\n]+)+/s'  => "\n",
        //remove empty lines (between HTML tags); cannot remove just any line-end characters because in inline JS they can matter!
        //'/\>[\r\n\t ]+\</s'    => '><',
        //remove "empty" lines containing only JS's block end character; join with next line (e.g. "}\n}\n</script>" --> "}}</script>"
        '/}[\r\n\t ]+/s'  => '}',
        '/}[\r\n\t ]+,[\r\n\t ]+/s'  => '},',
        //remove new-line after JS's function or condition start; join with next line
        '/\)[\r\n\t ]?{[\r\n\t ]+/s'  => '){',
        '/,[\r\n\t ]?{[\r\n\t ]+/s'  => ',{',
        //remove new-line after JS's line end (only most obvious and safe cases)
        '/\),[\r\n\t ]+/s'  => '),',

        '#<(img|input)(>| .*?>)#s' => '<$1$2</$1>',
        // Remove a line break and two or more white-space(s) between tag(s)
        '#(<!--.*?-->)|(>)(?:\n*|\s{2,})(<)|^\s*|\s*$#s' => '$1$2$3',
        '#(<!--.*?-->)|(?<!\>)\s+(<\/.*?>)|(<[^\/]*?>)\s+(?!\<)#s' => '$1$2$3', // t+c || o+t
        /* Trop Lourt
        '#(<!--.*?-->)|(<[^\/]*?>)\s+(<[^\/]*?>)|(<\/.*?>)\s+(<\/.*?>)#s' => '$1$2$3$4$5',// o+o || c+c
        '#(<!--.*?-->)|(<\/.*?>)\s+(\s)(?!\<)|(?<!\>)\s+(\s)(<[^\/]*?\/?>)|(<[^\/]*?\/?>)\s+(\s)(?!\<)#s' => '$1$2$3$4$5$6$7',// c+t || t+o || o+t -- separated by long white-space(s)
        */

        '#(<!--.*?-->)|(<[^\/]*?>)\s+(<\/.*?>)#s' => '$1$2$3', // empty tag
        '#<(img|input)(>| .*?>)<\/\1>#s' => '<$1$2', // reset previous fix
        '#(&nbsp;)&nbsp;(?![<\s])#' => '$1 ',// clean up ...
        '#(?<=\>)(&nbsp;)(?=\<)#' => '$1', // --ibid

        // On supprime certains espacement supplémentaire

        '/ {2,}/' => '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s',
        '/!\s+!/' => ' ',
        '/ {2,}/' => ' ',
        '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s' => '',
        );


    $input = preg_replace(array_keys($replace), array_values($replace), $input);

    // Remove HTML comment(s) except IE comment(s)
    //'#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s' => "",

    $input = preg_replace_callback('#\s*<!--(?!\[if\s).*?-->\s*|(?<!\>)\n+(?=\<[^!])#s', function($matches) {

        //debug(Router::getRequest()->navigateur['nom']);

        return $matches[0];
        //return '';

    }, $input);

    //remove quotes from HTML attributes that does not contain spaces; keep quotes around URLs!
    //$1 and $4 insert first white-space character found before/after attribute
    $input = preg_replace_callback('~([\r\n\t ])?([a-zA-Z0-9]+)="([a-zA-Z0-9_/\\-]+)"([\r\n\t ])?~s', function($matches) {
        // On evite d'enlever les surcottes au adresse qui pointe vers la racine, elle peut generer des erreurs dans certains nav mobile
        if ($matches[2] == 'href' AND $matches[3] == Router::getRequest()->webroot) return $matches[1].$matches[2].'="'.$matches[3].@$matches[4].'"';

        if (in_array($matches[2], array('fill'))) {
            return $matches[1].$matches[2].'="'.$matches[3].@$matches[4].'"';
        }

        return $matches[1].$matches[2].'='.$matches[3].@$matches[4];
    }, str_replace("\r", "", $input));


    // On enleve terilogie des champs img, input & meta "/>" par ">"
    $input = preg_replace_callback('/\\<(\\b[^>]*)\\/>\\s*/i', function($matches) {
        if (in_array(explode(' ', $matches[1])[0], array('path')) OR substr($matches[1], strlen($matches[1])-1, 1) == '\\') return $matches[0];
        return '<' . $matches[1].'>';
    }, str_replace("\r", "", $input));
    
    return $input;
}



public static function minify_css($input) {
    if(trim($input) === "") return $input;
    $input = preg_replace(
        array(
            // Remove comment(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
            // Remove unused white-space(s)
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
            // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
            '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
            // Replace `:0 0 0 0` with `:0`
            '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
            // Replace `background-position:0` with `background-position:0 0`
            '#(background-position):0(?=[;\}])#si',
            // Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
            '#(?<=[\s:,\-])0+\.(\d+)#s',
            // Minify string value
            '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
            '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
            // Minify HEX color code
            '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
            // Replace `(border|outline):none` with `(border|outline):0`
            '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
            // Remove empty selector(s)
            '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
        ),
        array(
            '$1',
            '$1$2$3$4$5$6$7',
            '$1',
            ':0',
            '$1:0 0',
            '.$1',
            '$1$3',
            '$1$2$4$5',
            '$1$2$3',
            '$1:0',
            '$1$2'
        ),
    $input);

    // On enleve le ";" de fin
    if (substr($input, $len = strlen($input)-1, $pos = 1) == ';') $input = substr_replace($input, '', $len, $pos);
    return $input;
}

// JavaScript Minifier
public static function minify_js($input) {
    if(trim($input) === "") return $input;
    return preg_replace(
        array(
            // Remove comment(s)
            '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
            // Remove white-space(s) outside the string and regex
            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
            // Remove the last semicolon
            '#;+\}#',
            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
            '#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
            // --ibid. From `foo['bar']` to `foo.bar`
            '#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
        ),
        array(
            '$1',
            '$1$2',
            '}',
            '$1$3',
            '$1.$3'
        ),
    $input);
}


    
    protected function _commentCB($m)
    {
        return (0 === strpos($m[1], '[') || false !== strpos($m[1], '<!['))
            ? $m[0]
            : '';
    }
    
    protected function _reservePlace($content)
    {
        $placeholder = '%' . $this->_replacementHash . count($this->_placeholders) . '%';
        $this->_placeholders[$placeholder] = $content;
        return $placeholder;
    }

    protected $_isXhtml = null;
    protected $_replacementHash = null;
    protected $_placeholders = array();
    protected $_cssMinifier = null;
    protected $_jsMinifier = null;

    protected function _removePreCB($m)
    {
        return $this->_reservePlace("<pre{$m[1]}");
    }
    
    protected function _removeTextareaCB($m)
    {
        return $this->_reservePlace("<textarea{$m[1]}");
    }

    protected function _removeStyleCB($m)
    {
        $openStyle = "<style{$m[1]}";
        $css = $m[2];
        // remove HTML comments
        $css = preg_replace('/(?:^\\s*<!--|-->\\s*$)/', '', $css);
        
        // remove CDATA section markers
        $css = $this->_removeCdata($css);
        
        // minify
        $minifier = $this->_cssMinifier
            ? $this->_cssMinifier
            : 'trim';
        $css = call_user_func($minifier, $css);
        
        return $this->_reservePlace($this->_needsCdata($css)
            ? "{$openStyle}/*<![CDATA[*/{$css}/*]]>*/</style>"
            : "{$openStyle}{$css}</style>"
        );
    }

    protected function _removeJsCB($js)
    {
        
        // remove HTML comments (and ending "//" if present)
        if ($this->_jsCleanComments) {
            $js = preg_replace('/(?:^\\s*<!--\\s*|\\s*(?:\\/\\/)?\\s*-->\\s*$)/', '', $js);
        }
        
        // minify
        $minifier = $this->_jsMinifier
            ? $this->_jsMinifier
            : 'trim';

        try {
            $js = call_user_func($minifier, $js);

            $replace = array(
                '/ {2,}/' => '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s',
                '/!\s+!/' => ' ',
                '/ {2,}/' => ' ',
                '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s' => '',
            );
        } catch (Exception $e) {
            $js = $this->minify_js($js);
            
            $replace = array(
                '/ {2,}/' => '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s',
                '/!\s+!/' => ' ',
                '/ {2,}/' => ' ',
            );
        }

        // On remplace les espaces par des ; et on le minify encore
        $js = preg_replace(array_keys($replace), array_values($replace), $js);

        // On arrange quelque erreur ;
        $js = str_replace(');{', '){', $js);

        $js = $this->minify_js($js);

        return $js;
    }

    protected function _removeScriptCB($m)
    {
        $openScript = "<script{$m[2]}";
        $js = $m[3];
        
        // whitespace surrounding? preserve at least one space
        $ws1 = ($m[1] === '') ? '' : ' ';
        $ws2 = ($m[4] === '') ? '' : ' ';

        // remove CDATA section markers
        $js = $this->_removeCdata($js);

        $js = $this->_removeJsCB($js);

        return $this->_reservePlace($this->_needsCdata($js)
            ? "{$ws1}{$openScript}/*<![CDATA[*/{$js}/*]]>*/</script>{$ws2}"
            : "{$ws1}{$openScript}{$js}</script>{$ws2}"
        );
    }

    protected function _removeCdata($str)
    {
        return (false !== strpos($str, '<![CDATA['))
            ? str_replace(array('<![CDATA[', ']]>'), '', $str)
            : $str;
    }
    
    protected function _needsCdata($str)
    {
        return ($this->_isXhtml && preg_match('/(?:[<&]|\\-\\-|\\]\\]>)/', $str));
    }
}
