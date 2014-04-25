<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Filter converting URLs in the text to HTML links
 *
 * @package    filter
 * @subpackage layout
 * @copyright  2014 Richard Oelmann
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class filter_layout extends moodle_text_filter {

     /**
      * @var array global configuration for this filter
      *
      * This might be eventually moved into parent class if we found it
      * useful for other filters, too.
      */
    protected static $globalconfig;

    /**
     * Apply the filter to the text
     *
     * @see filter_manager::apply_filter_chain()
     * @param string $text to be processed by the text
     * @param array $options filter options
     * @return string text after processing
     */
    public function filter($text, array $options = array()) {

        // TODO: Remove any script and other tags which we do not wish to filter. It
        // is unlikely that we'll find any suitable links within these areas so for
        // now this part has been left unfinished.

        // We should search only for reference to specific codes [layout-code].
        // Reference: layout-row - begins a new row; 
        //			  layout-end - needed to end each div (row/box etc);
        //			  layout-box-x - creates a bootstrap span/column of x twelfths;
        //			  layout-div-abcxyz - adds a div with the class abcxyz
        // Anything else creates a div with no additional classes
        $search = "(\[(layout-.*?)\])is";
        $text = preg_replace_callback($search, array($this, 'callback'), $text);
        return $text;
    }

    ////////////////////////////////////////////////////////////////////////////
    // internal implementation starts here
    ////////////////////////////////////////////////////////////////////////////

    /**
     * Returns the global filter setting
     *
     * If the $name is provided, returns single value. Otherwise returns all
     * global settings in object. Returns null if the named setting is not
     * found.
     *
     * @param mixed $name optional config variable name, defaults to null for all
     * @return string|object|null
     */
    protected function get_global_config($name=null) {
        $this->load_global_config();
        if (is_null($name)) {
            return self::$globalconfig;

        } elseif (array_key_exists($name, self::$globalconfig)) {
            return self::$globalconfig->{$name};

        } else {
            return null;
        }
    }

    /**
     * Makes sure that the global config is loaded in $this->globalconfig
     *
     * @return void
     */
    protected function load_global_config() {
        if (is_null(self::$globalconfig)) {
            self::$globalconfig = get_config('filter_layout');
        }
    }
    
    private function callback(array $matches) {
		$type = substr($matches[1],7,3);
		if ($type == "row"){ //[layout-row] creates a new row
			$embed='<div class="row row-fluid">';
		} elseif ($type == "end"){ //[layout-end] required to end each box/row/div
			$embed='</div>';
		} elseif ($type == "box"){ //[layout-box-x] creates a span/column x 12ths (desktop only currently ie not BS3 xs, sm, lg options)
			$chunk= explode("-",$matches[1]);
			$size=$chunk[2];
			$embed='<div class="col-md-'.$size.' span'.$size.'">';
		} elseif ($type == "div"){ //[layout-div-abcxyz] creates a div with class abcxyz
			$chunk= explode("-",$matches[1]);
			$class=$chunk[2];
			$embed='<div class="'.$class.'">';
		} else { //catchall [layout-abcxyz] just creates a div with no additional classes
			$embed='<div>';
		}

        return $embed;
    }
}
