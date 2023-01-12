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
 * Simple example of a text filter.
 *
 * @package    filter
 * @subpackage simplefilter
 * @copyright  2017 Richard Jones (https://richardnz.net)
 * @copyright  2023 G J Barnard - {@link http://moodle.org/user/profile.php?id=442195}.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * This filter looks for content tags in Moodle text and
 * replaces them with specified user-defined content.
 * @see filter_manager::apply_filter_chain()
 */
class filter_simplefilter extends moodle_text_filter {
    /**
     * This function looks for tags in Moodle text and
     * replaces them with questions from the question bank.
     * Tags have the format {{NEWTEXT:xxx}} where:
     *   - xxx is the user specified content
     * @param string $text to be processed by the text
     * @param array $options filter options
     *
     * @return string text after processing
     */
    public function filter($text, array $options = array()) {
        global $PAGE;

        // Basic test to avoid work.
        if (!is_string($text)) {
            // Non string content can not be filtered anyway.
            return $text;
        }

        /* Admin might need to change these at some point - eg to double curlies,
           therefore defined in {@link settings.php} with default values. */
        $config = get_config('filter_simplefilter');
        $starttag = $config->starttag;
        $endtag = $config->endtag;

        // Do a quick check to see if we have a tag.
        if (core_text::strpos($text, $starttag) === false) {
            return $text;
        }

        $renderer = $PAGE->get_renderer('filter_simplefilter');

        /* There may be a tag in here somewhere so continue
           Get the contents and positions in the text and call the
           renderer to deal with them. */
        $text = filter_simplefilter_insert_content($text, $starttag, $endtag, $renderer);

        return $text;
    }
}

/**
 * Function to replace filter text with button that opens content.
 *
 * @param string $str text to be processed
 * @param string $starttag start tag pattern to be searched for
 * @param string $endtag end tag for text to replace
 * @param renderer $renderer - filter renderer
 * @return a replacement text string
 */
function filter_simplefilter_insert_content($str, $starttag, $endtag, $renderer) {

    $newstring = $str;
    // While we have the start tag in the text.
    while (core_text::strpos($newstring, $starttag) !== false) {
        $initpos = core_text::strpos($newstring, $starttag);
        if ($initpos !== false) {
            // Find the start of the string after the tag.
            $pos = $initpos + core_text::strlen($starttag);
            $endpos = core_text::strpos($newstring, $endtag);
            // Extract the actual content between the tags.
            $content = core_text::substr($newstring, $pos, $endpos - $pos);
            // Clean the string.
            $content = filter_var($content, FILTER_SANITIZE_STRING);
            // Get the required content.
            $newcontent = $renderer->get_content($content);
            $newstring = substr_replace($newstring, $newcontent, $initpos,
                $endpos - $initpos + core_text::strlen($endtag));
            $initpos = $endpos + core_text::strlen($endtag);
        }
    }

    return $newstring;
}
