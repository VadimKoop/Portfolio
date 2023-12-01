<?php

namespace HelpieFaq\Features\Insights;


if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!class_exists('\HelpieFaq\Features\Insights\Insights_Helper')) {
    class Insights_Helper
    {

        public function __construct($current_date, $date_format){
            $this->date_format = $date_format;
            $this->current_date = $current_date;
        }


        public function get_day_count($date_count){
            $day_total = 0;

            // Default
            if( !is_array($date_count) && is_numeric($date_count) )
            return $date_count;

            foreach($date_count as $key => $count){
                $day_total += $count;
            }

            return $day_total;

        }


        // TODO: Change to is_date_between
        public function is_last_n_days($start_timestamp, $date, $days){
            $start_date = date($this->date_format, $start_timestamp);
            $diff_timestamp = strtotime(' -'.$days.' days', $start_timestamp); 
            $date_ndays_ago = date($this->date_format, $diff_timestamp);

            $is_after_range_start = (strtotime($date_ndays_ago) < strtotime($date)) ? true : false;
            $is_before_range_end = (strtotime($date) <= strtotime($start_date)) ? true : false;

            if ( ($is_after_range_start ) && ($is_before_range_end))
                return true;

            // False by default
            return false;
        }

        public function get_current_month($current_timestamp){
            $month_format = "Y-m";
            $current_month = date($month_format, $current_timestamp);

            return $current_month;
        }

        public function get_events_for_last_n_days($counter_data, $days, $current_timestamp, $date_format){
            $current_date = date($date_format, $current_timestamp);
            $total = 0;

             // Today
            if( isset($counter_data[$current_date]) ){
                $total += $this->get_day_count($counter_data[$current_date]);
            }

            // Previous n-1 days
            for($ii = 1; $ii < $days; $ii++){
                $diff_timestamp = strtotime(' -'.$ii.' day'); 
                $date = date($date_format, $diff_timestamp);

                if( !isset($counter_data[$date]) )
                break;

                $total += $this->get_day_count($counter_data[$date]);
            }

            return $total;
        }

        public function get_events_for_last_n_days_new($counter_data, $days, $current_timestamp, $date_format){
           //  error_log('update_last_7days counter_data : ' . print_r($counter_data, true));
            $current_date = date($date_format, $current_timestamp);
            $total = 0;

            // Previous n-1 days
            for($ii = 0; $ii < $days; $ii++){
                $diff_timestamp = strtotime(' -'.$ii.' day'); 
                $date = date($date_format, $diff_timestamp);

                if( !isset($counter_data[$date]) )
                continue;

                // error_log('update_last_7days counter_data[date] : ' . $counter_data[$date]);

                $total += $this->get_day_count($counter_data[$date]);
            }

            // error_log('update_last_7days total : ' . $total);
            return $total;
        }


        public function get_last_30days($insights){
            
            $last_30days = array();
            $labels = array();
            $values = array();

            // Today 
            $value = isset($insights[$this->current_date]) ? $insights[$this->current_date]: 0;

            $labels[0] = date("d M");
            $values[0] = $value;

            // Previous 29 days
            for($ii = 1; $ii < 30; $ii++){
                $label_and_value = $this->get_label_and_value($insights, $ii);
                $labels[$ii] = $label_and_value['label'];
                $values[$ii] = $label_and_value['value'];
            }
            
            // Array order from old to newest
            $labels = array_reverse($labels);
            $values = array_reverse($values );
            
            // error_log('reversed $labels : ' . print_r($labels, true));

            $last_30days['labels'] = $labels;
            $last_30days['values'] = $values;
            
            return $last_30days;
        }

        /* PROTECTED METHODS */

        protected function get_label_and_value($insights, $ii){
            $diff_timestamp = strtotime(' -'.$ii.' day'); 
            $date = date($this->date_format, $diff_timestamp);

            $value = isset($insights[$date]) ? $insights[$date]: 0;
            $date_label = date("d M",  $diff_timestamp);

            return array(
                'label' =>$date_label,
                'value' => $value
            );
        }

    } // END CLASS
}