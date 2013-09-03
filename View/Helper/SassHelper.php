<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SassHelper
 *
 * @author flug
 */
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
App::uses('Component', 'Controller');

App::import("Vendor", "CakeSass.Sass", array(
    "file" => "autoload.php"
));

class SassHelper extends AppHelper {

    public $helpers = array('Html');
    private $sassFolder;
    private $cssFolder;
    private $scss;

    public function __construct(View $View, $options = array()) {
        parent::__construct($View, $options);

        $this->sassFolder = new Folder(WWW_ROOT . 'sass');

        $this->cssFolder = new Folder(TMP . 'css', true, 777);

        if (is_null($this->scss)) {
            $this->scss = new scssc();
        }

        $this->scss->setImportPaths($this->sassFolder->path . "/");
    }

    public function css($file, $options = array()) {

        if (isset($options['theme']) && trim($options['theme'])) {
            $this->set_theme($options['theme']);
        }

        if (is_array($file)) {
            foreach ($file as $candidate) {
                $source = $this->sassFolder->path . DS . $candidate . '.sass';
                $target = str_replace('.sass', '.css', str_replace($this->sassFolder->path, $this->cssFolder->path, $source));
                $this->auto_compile_sass($source, $target);
            }
        } else {
            if (isset($options['plugin']) && trim($options['plugin'])) {
                $this->sassFolder = new Folder(APP . 'Plugin' . DS . $options['plugin'] . DS . 'webroot' . DS . 'sass');
            }


            $source = $this->sassFolder->path . DS . $file . '.scss';
            if (file_exists($this->sassFolder->path . DS . $file . '.sass'))
                $source = $this->sassFolder->path . DS . $file . '.sass';


            $target = str_replace(array('.sass', '.scss'), '.css', str_replace($this->sassFolder->path, $this->cssFolder->path . "/" . md5_file($source), $source));

            $this->compile_sass($source, $target);
        }


        echo $this->Html->css($file);
    }

    public function compile_sass($source, $target) {


        $file = new File($source);
        $tmp_sass = $file->read();



        $css = $this->scss->compile($tmp_sass);

        //$file_css = new File($target, true, 777);
        //$file_css->write($css);
    }

}

?>
