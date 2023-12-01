<?php

namespace HelpieFaq\Features\Insights\Trackers;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


/* 
    Data Structure Example of option 'helpie_faq_searches' : array[$date]['searchTerm']

    array( 
        '02-13-2019' => array(
            'payment' => 5,
            'service' => 3
        )
    );

*/

if (!class_exists('\HelpieFaq\Features\Insights\Trackers\Search_Tracker')) {
    class Search_Tracker extends \HelpieFaq\Features\Insights\Trackers\Event_Tracker
    {

        private $option_name = 'helpie_faq_searches';

        public function __construct(){
            parent::__construct();
         }



        public function get_new_count($counter_data, $event_data){
            // If search empty, return value
            if($this->is_search_empty($event_data)) return $counter_data;

            $counter_data = $this->update_todays_count($counter_data, $event_data);
            $counter_data = $this->update_current_month_count($counter_data, $event_data);
            $counter_data = $this->update_all_time_count($counter_data, $event_data);

            return $counter_data;
        }

        public function is_search_empty($event_data){
            $is_search_empty = (!isset($event_data) || empty($event_data) || key($event_data) == '');
            return $is_search_empty;
        }

        public function update_count($counter_data, $event_data ){

            update_option($this->option_name, $counter_data);
        }

        public function get_event_data($postData){
            $string = isset($postData['searchTerm']) ? $postData['searchTerm'] : '';
            $curr_searches = $this->get_search_words_array($string);

            $event_data = $curr_searches;
            return $event_data;
        }

        public function get_current_count($postData){ 
            $string = isset($postData['searchTerm']) ? $postData['searchTerm'] : '';
            $counter_data = get_option($this->option_name);

            // $prev_searches = array();
            // if( isset($counter_data[$string]) )
            // $prev_searches = $counter_data[$string];
            // return $prev_searches;

            return $counter_data;
        }



        public function update_todays_count($counter_data, $event_data){
            // $counter_data = get_option($this->option_name);

            foreach($event_data as $searchTerm => $value){

                // Default - level 1
                if(!isset($counter_data[$searchTerm]) )
                $counter_data[$searchTerm] = array();

                // Default - level 2
                if(!isset($counter_data[$searchTerm][$this->current_date]) )
                $counter_data[$searchTerm][$this->current_date] = 0;

                $counter_data[$searchTerm][$this->current_date] += $value;
            }
        //    error_log('counter_data: ' .print_r($counter_data, true));
           
            return $counter_data;
        }


        public function update_current_month_count($counter_data, $event_data){
           
            $current_month = $this->insights_helper->get_current_month($this->current_timestamp);

            foreach($event_data as $searchTerm => $value){

                // Default - level 1
                if(!isset($counter_data[$searchTerm]) )
                $counter_data[$searchTerm] = array();

                // Default - level 2
                if(!isset($counter_data[$searchTerm][$current_month]) )
                $counter_data[$searchTerm][$current_month] = 0;

                $counter_data[$searchTerm][$current_month] += $value;
            }
           
           
            return $counter_data;
        }

        public function update_all_time_count($counter_data, $event_data ){
            $all_time_key = 'all-time';
            // $counter_data['all-time'] = isset($counter_data['all-time'] ) ? ($counter_data['all-time'] + 1) : 1;

            foreach($event_data as $searchTerm => $value){

                // Default - level 1
                if(!isset($counter_data[$searchTerm]) )
                $counter_data[$searchTerm] = array();

                // Default - level 2
                if(!isset($counter_data[$searchTerm][$all_time_key]) )
                $counter_data[$searchTerm][$all_time_key] = 0;

                $counter_data[$searchTerm][$all_time_key] += $value;
            }
           

            return $counter_data;
        }


        public function process_data()
        {
            $postData = array();

            if (isset($_POST['searchTerm']) && !empty($_POST['searchTerm'])) {
                $postData['searchTerm'] = sanitize_text_field($_POST['searchTerm']);
            }



            return $postData;
        }
        
        public  function get_search_words_array($string)
        {
            $new_string = $this->removeCommonWords($string);
            $new_string = strtolower($new_string);

            $word_array = preg_split('/\s+/', $new_string);
            $word_array = array_map('trim', $word_array);

            $inputArray = array();
            foreach ($word_array as $key => $value) {
                $inputArray[$value] = 1;
            }

            return $inputArray;
        }

        public  function removeCommonWords($input)
        {
            $commonWords = $this->get_common_words();

            return preg_replace('/\b(' . implode('|', $commonWords) . ')\b/', '', $input);
        }
        
        public  function get_common_words()
        {
            // EEEEEEK Stop words
            $commonWords = array('a', 'able', 'about', 'above', 'abroad', 'according', 'accordingly', 'across', 'actually', 'adj', 'after', 'afterwards', 'again', 'against', 'ago', 'ahead', 'ain\'t', 'all', 'allow', 'allows', 'almost', 'alone', 'along', 'alongside', 'already', 'also', 'although', 'always', 'am', 'amid', 'amidst', 'among', 'amongst', 'an', 'and', 'another', 'any', 'anybody', 'anyhow', 'anyone', 'anything', 'anyway', 'anyways', 'anywhere', 'apart', 'appear', 'appreciate', 'appropriate', 'are', 'aren\'t', 'around', 'as', 'a\'s', 'aside', 'ask', 'asking', 'associated', 'at', 'available', 'away', 'awfully', 'b', 'back', 'backward', 'backwards', 'be', 'became', 'because', 'become', 'becomes', 'becoming', 'been', 'before', 'beforehand', 'begin', 'behind', 'being', 'believe', 'below', 'beside', 'besides', 'best', 'better', 'between', 'beyond', 'both', 'brief', 'but', 'by', 'c', 'came', 'can', 'cannot', 'cant', 'can\'t', 'caption', 'cause', 'causes', 'certain', 'certainly', 'changes', 'clearly', 'c\'mon', 'co', 'co.', 'com', 'come', 'comes', 'concerning', 'consequently', 'consider', 'considering', 'contain', 'containing', 'contains', 'corresponding', 'could', 'couldn\'t', 'course', 'c\'s', 'currently', 'd', 'dare', 'daren\'t', 'definitely', 'described', 'despite', 'did', 'didn\'t', 'different', 'directly', 'do', 'does', 'doesn\'t', 'doing', 'done', 'don\'t', 'down', 'downwards', 'during', 'e', 'each', 'edu', 'eg', 'eight', 'eighty', 'either', 'else', 'elsewhere', 'end', 'ending', 'enough', 'entirely', 'especially', 'et', 'etc', 'even', 'ever', 'evermore', 'every', 'everybody', 'everyone', 'everything', 'everywhere', 'ex', 'exactly', 'example', 'except', 'f', 'fairly', 'far', 'farther', 'few', 'fewer', 'fifth', 'first', 'five', 'followed', 'following', 'follows', 'for', 'forever', 'former', 'formerly', 'forth', 'forward', 'found', 'four', 'from', 'further', 'furthermore', 'g', 'get', 'gets', 'getting', 'given', 'gives', 'go', 'goes', 'going', 'gone', 'got', 'gotten', 'greetings', 'h', 'had', 'hadn\'t', 'half', 'happens', 'hardly', 'has', 'hasn\'t', 'have', 'haven\'t', 'having', 'he', 'he\'d', 'he\'ll', 'hello', 'help', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'here\'s', 'hereupon', 'hers', 'herself', 'he\'s', 'hi', 'him', 'himself', 'his', 'hither', 'hopefully', 'how', 'howbeit', 'however', 'hundred', 'i', 'i\'d', 'ie', 'if', 'ignored', 'i\'ll', 'i\'m', 'immediate', 'in', 'inasmuch', 'inc', 'inc.', 'indeed', 'indicate', 'indicated', 'indicates', 'inner', 'inside', 'insofar', 'instead', 'into', 'inward', 'is', 'isn\'t', 'it', 'it\'d', 'it\'ll', 'its', 'it\'s', 'itself', 'i\'ve', 'j', 'just', 'k', 'keep', 'keeps', 'kept', 'know', 'known', 'knows', 'l', 'last', 'lately', 'later', 'latter', 'latterly', 'least', 'less', 'lest', 'let', 'let\'s', 'like', 'liked', 'likely', 'likewise', 'little', 'look', 'looking', 'looks', 'low', 'lower', 'ltd', 'm', 'made', 'mainly', 'make', 'makes', 'many', 'may', 'maybe', 'mayn\'t', 'me', 'mean', 'meantime', 'meanwhile', 'merely', 'might', 'mightn\'t', 'mine', 'minus', 'miss', 'more', 'moreover', 'most', 'mostly', 'mr', 'mrs', 'much', 'must', 'mustn\'t', 'my', 'myself', 'n', 'name', 'namely', 'nd', 'near', 'nearly', 'necessary', 'need', 'needn\'t', 'needs', 'neither', 'never', 'neverf', 'neverless', 'nevertheless', 'new', 'next', 'nine', 'ninety', 'no', 'nobody', 'non', 'none', 'nonetheless', 'noone', 'no-one', 'nor', 'normally', 'not', 'nothing', 'notwithstanding', 'novel', 'now', 'nowhere', 'o', 'obviously', 'of', 'off', 'often', 'oh', 'ok', 'okay', 'old', 'on', 'once', 'one', 'ones', 'one\'s', 'only', 'onto', 'opposite', 'or', 'other', 'others', 'otherwise', 'ought', 'oughtn\'t', 'our', 'ours', 'ourselves', 'out', 'outside', 'over', 'overall', 'own', 'p', 'particular', 'particularly', 'past', 'per', 'perhaps', 'placed', 'please', 'plus', 'possible', 'presumably', 'probably', 'provided', 'provides', 'q', 'que', 'quite', 'qv', 'r', 'rather', 'rd', 're', 'really', 'reasonably', 'recent', 'recently', 'regarding', 'regardless', 'regards', 'relatively', 'respectively', 'right', 'round', 's', 'said', 'same', 'saw', 'say', 'saying', 'says', 'second', 'secondly', 'see', 'seeing', 'seem', 'seemed', 'seeming', 'seems', 'seen', 'self', 'selves', 'sensible', 'sent', 'serious', 'seriously', 'seven', 'several', 'shall', 'shan\'t', 'she', 'she\'d', 'she\'ll', 'she\'s', 'should', 'shouldn\'t', 'since', 'six', 'so', 'some', 'somebody', 'someday', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhat', 'somewhere', 'soon', 'sorry', 'specified', 'specify', 'specifying', 'still', 'sub', 'such', 'sup', 'sure', 't', 'take', 'taken', 'taking', 'tell', 'tends', 'th', 'than', 'thank', 'thanks', 'thanx', 'that', 'that\'ll', 'thats', 'that\'s', 'that\'ve', 'the', 'their', 'theirs', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'there\'d', 'therefore', 'therein', 'there\'ll', 'there\'re', 'theres', 'there\'s', 'thereupon', 'there\'ve', 'these', 'they', 'they\'d', 'they\'ll', 'they\'re', 'they\'ve', 'thing', 'things', 'think', 'third', 'thirty', 'this', 'thorough', 'thoroughly', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'till', 'to', 'together', 'too', 'took', 'toward', 'towards', 'tried', 'tries', 'truly', 'try', 'trying', 't\'s', 'twice', 'two', 'u', 'un', 'under', 'underneath', 'undoing', 'unfortunately', 'unless', 'unlike', 'unlikely', 'until', 'unto', 'up', 'upon', 'upwards', 'us', 'use', 'used', 'useful', 'uses', 'using', 'usually', 'v', 'value', 'various', 'versus', 'very', 'via', 'viz', 'vs', 'w', 'want', 'wants', 'was', 'wasn\'t', 'way', 'we', 'we\'d', 'welcome', 'well', 'we\'ll', 'went', 'were', 'we\'re', 'weren\'t', 'we\'ve', 'what', 'whatever', 'what\'ll', 'what\'s', 'what\'ve', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'where\'s', 'whereupon', 'wherever', 'whether', 'which', 'whichever', 'while', 'whilst', 'whither', 'who', 'who\'d', 'whoever', 'whole', 'who\'ll', 'whom', 'whomever', 'who\'s', 'whose', 'why', 'will', 'willing', 'wish', 'with', 'within', 'without', 'wonder', 'won\'t', 'would', 'wouldn\'t', 'x', 'y', 'yes', 'yet', 'you', 'you\'d', 'you\'ll', 'your', 'you\'re', 'yours', 'yourself', 'yourselves', 'you\'ve', 'z', 'zero');

            return $commonWords;
        }

    } // END CLASS
}