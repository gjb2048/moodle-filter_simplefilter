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
 * Functions for inserting and displaying content
 * @package    filter
 * @subpackage simplefilter
 * @copyright  2017 Richard Jones (https://richardnz.net/)
 * @copyright  2023 G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Custom renderer class for filter_simplefilter
 * @copyright  2017 Richard Jones (https://richardnz.net/)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter_simplefilter_renderer extends plugin_renderer_base {
    /**
     * This function returns content
     * @param string $content the text for the collabsible.
     * @return string the html required to display the content.
     */
    public function get_content($content) {
        $data = new stdClass();
        $data->buttontext = get_string('button_label', 'filter_simplefilter');
        $data->content = $content;
        return $this->render_from_template('filter_simplefilter/panel', $data);
    }
}
