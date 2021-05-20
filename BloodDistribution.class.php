<?php

    class BloodDistribution {
        private $data = [];
        private $avail = [];

        public function parse($data) {
            $types = ['O-', 'O+', 'A-', 'A+', 'B-', 'B+', 'AB-', 'AB+'];

            // Array for valid blood, in priority usage order
            $priorities = array(
                ['O-'],
                ['O+', 'O-'],
                ['A-', 'O-'],
                ['A+', 'A-', 'O+', 'O-'],
                ['B-', 'O-'],
                ['B+', 'B-', 'O+', 'O-'],
                ['AB-', 'B-', 'A-', 'O-'],
                ['AB+', 'AB-', 'B+', 'B-', 'A+', 'A-', 'O+', 'O-']
            );

            // Get data based on newlines or spaces
            $arr = preg_split("/\s+/", $data);

            // Add to multidimensional array $data
            for($i = 0; $i < 8; $i++) {
                $el = array('type' => $types[$i], 'patients' => $arr[$i + 8], 'priority' => $priorities[$i]);
                $this->avail[$types[$i]] = $arr[$i];
                array_push($this->data, $el);
            }
        }

        public function getMaxPatients() {
            // Order from least to greatest.
            $donateCount = 0;
            $this->orderByPatients($this->data);

            // While not empty
            while(!empty($this->data)) {

                // For each element in data.
                for($i = 0; $i < count($this->data); $i++) {
            
                    $el = &$this->data[$i];


                    // Get blood type and then remove it from possible usage.
                    $blood = $el['priority'][0];
                    array_shift($el['priority']);
                    $unitsAvail = &$this->avail[$blood];

                    // If there's more blood than patients that need, all patients will receive. 
                    if($el['patients'] < $unitsAvail) {
                        $donateCount += $el['patients'];
                        $unitsAvail -= $el['patients'];

                        $el['patients'] = 0;

                    // Else use up all the blood. There will be some patients leftover.
                    } else {

                        $donateCount += $unitsAvail;
                        $el['patients'] -= $unitsAvail;
                        $unitsAvail = 0;

                    }

                    // If the priority is exhausted, meaning there's no more alternatives, set treatable patients to 0 to flag for removal.
                     if(empty($el['priority'])) {
                        $el['patients'] = 0;
                    } 

                }
                // Re-order least-to-greatest
                $this->orderByPatients($this->data);
                

                // Remove entries where patients are 0
                while(!empty($this->data) && $this->data[0]['patients'] == 0) {
                    array_shift($this->data);
                }
            }

            return $donateCount;

        }

        // Order array by number of patients
        private function orderByPatients(&$data) {
            $patients  = array_column($data, 'patients');
            array_multisort($patients, SORT_NUMERIC, $data);
        }

        // For debugging
        private function prettyPrint($arr) {
            echo "<pre>";
            print_r($arr);
            echo "</pre>";

        }

        private function printArr($arr) {
            foreach($arr as $val) {
                echo $val . " ";
            }
            echo nl2br("\n");
        }
    }
?>