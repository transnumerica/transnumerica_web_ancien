<?php

App::import('Vendor', 'MinifyHtml.Minify_HTML', array('file' => 'html.php'));

class MinifyHtmlHelper extends AppHelper {

    public $view;

    public function __construct($view) {

        $this->view = $view;
        parent::__construct($view);
    }

    public function afterLayout($layoutFile) {
        if (!empty($this->view->layout)) $this->view->output = $this->minify_html($this->view->output);
    }

    public function afterRender($viewFile) {
        if (empty($this->view->layout)) $this->view->output = $this->minify_html($this->view->output);
    }
    protected function minify_html($html) {

        App::import('Vendor', 'AssetCompress.Minifier', array('file' => 'jshrink/Minifier.php'));
        App::import('Vendor', 'AssetCompress.cssmin', array('file' => 'cssmin/CssMin.php'));

        $options = array(
            "cssMinifier" => array("CssMin", "minify"),
            "jsMinifier" => array("JShrink\Minifier", "minify"),
            'xhtml' => false
        );

        return Minify_HTML::minify($html, $options);
    }
}
