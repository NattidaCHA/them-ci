<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Template {

    private $_module = NULL;
    private $CI;
    private $template_data = array();
    private $partials = array();
    private $html = '';

    public function __construct() {
        $this->CI =& get_instance();
        if (method_exists( $this->CI->router, 'fetch_module' ))  {
            $this->_module  = $this->CI->router->fetch_module();
        }
    }


    public function set_partial($name) {
        if (is_array($name)) {
            foreach($name as $n) {
                $path = ASSETPATH.'/partials/'.self::_ext($n);
                if (file_exists($path)) {
                    $this->partials[$n] = $path;
                }
            }
        } else {
            $path = ASSETPATH.'/partials/'.self::_ext($name);
            if (file_exists($path)) {
                $this->partials[$name] = $path;
            }
        }
    }


    public function get_partial($name , $data) {
        $path = ASSETPATH.'/partials/'.self::_ext($name);
        if (file_exists($path)) {
            $this->CI->load->vars($data);
            return $this->CI->load->file($path, TRUE);
        }
        return NULL;
    }


    public function set_layout($name, $body_view, $data) {
        $path = ASSETPATH.'/layouts/'.self::_ext($name);
        if (file_exists($path)) {
            $this->CI->load->vars($data);
            foreach($this->partials as $name => $value) {
                $this->template_data['__'.$name] = $this->CI->load->file($value, TRUE);
            }

            $this->template_data['__body'] = $this->find_view($body_view);
            $this->CI->load->clear_vars();
            $this->CI->load->vars($this->template_data);
            $this->html = $this->CI->load->file($path, TRUE);
        } else {
            $this->html = '';
        }
    }


    public function build() {
        $this->CI->output->set_output($this->html);
    }


    private function find_view($view) {
        $path = '';
        $list_views = array(
            APPPATH . 'views/' ,
            APPPATH . 'modules/' . $this->_module .'/views/'
        );

        foreach($list_views as $list) {
            $path .= $list . self::_ext($view).'<br />';
            if (file_exists($list . self::_ext($view))) {
                return $this->CI->load->file($list . self::_ext($view), TRUE);
            }
        }

        show_error('Can not render view file. Please check file name do you want to use.' , 503);
        return NULL;
    }

    private function _ext($filename) {
        return $filename.(pathinfo($filename, PATHINFO_EXTENSION) ? '' : '.php');
    }
}
/* End of file Template.php */