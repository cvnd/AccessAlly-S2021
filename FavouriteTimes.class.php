<?php

    class FavouriteTimes {
        public $start = 1200;
        public $end;
        public $combos = [];
        public $hours = 0;
        public $min = 0;

        // Get end time in string format
        public function endTimes($num) {
            $this->hours = floor($num / 60);
            $this->min = $num - ($this->hours * 60);

            if($this->hours == 0) {
                $endTime = "12";
            } else {
                $endTime = $this->hours;
            }

            if($this->min < 10) {
                $endTime .= "0";
            }
            $endTime .= $this->min;

            $this->end = (int) $endTime;

        }

        public function checkTwelve() {
            if($this->end >= 1200) {
                return true;
            }
            return false;
        }

        public function getTimes($max_hours, $max_minutes = 0) {
            $count = 0;
            // If the time range encompasses 12:34, there's at least one value
            if($max_minutes >= 34 || $max_hours >= 1) {
                array_push($this->combos, "1234");
                $count++;
            }

            for($i = 1; $i <= $max_hours; $i++ ) {

                // Testing different increments. Max difference is |4| with 0, 
                for($j = -4; $j <= 4; $j++) {

                    $tens = $i + $j;
                    // If tens digit is in valid range
                    if($tens >= 0 && $tens <= 5) {
                        $ones = $tens + $j;

                        // If ones digit is in valid range
                        if($ones >= 0 && $ones <= 9) {
                            $minutes = (int) $tens . $ones;

                            // If this is at the last hour available, check if the minutes generate exceed the minutes calculated
                            if($i == $max_hours && $max_minutes < $minutes) {
                                continue;
                            }

                            $combo = $i . $minutes;
                            array_push($this->combos, $combo);
                            $count++;
                        }
                    }
                }
            }


            // If the time range inclues 11:11, add 1
            if($max_hours >= 11 && $max_minutes >= 11 || $max_hours == 12) {
                array_push($this->combos, "1111");
                $count++;
            } 
            return $count;


        }

        // For testing
        public function printTimes() {
            foreach ($this->combos as $time) {
                if($time > 1000) {
                    echo substr($time,0, 2) . ":" . substr($time, 2, 4) . "\r\n";
                } else if($time < 100) {
                    echo "12:34" . "\r\n";
                } else {
                    echo substr($time, 0, 1) . ":" . substr($time, 1, 3) . "\r\n";
                }
            }
        }



        public function countTimes() {
            if($this->hours <= 12) {
                return $this->getTimes($this->hours, $this->min);
            }
            $hourCounts = $this->getTimes(12);

            // How many times it goes about 12h intervals
            $original_rounds = $this->hours / 12;
            $rounds = floor($original_rounds);
            $total = $rounds * $hourCounts;

            // If the intervals were rounded and there's leftover minutes
            if($rounds < $original_rounds) {
                $leftover_hours = $this->hours - ($rounds * 12);
                $extra_counts = $this->getTimes($leftover_hours, $this->min);

                $total += $extra_counts;
            }

            return $total;

        }
        
    }
?>