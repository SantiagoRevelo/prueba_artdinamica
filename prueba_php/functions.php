<?php
// Connection data -> Change the access data for your owns
    $user   = "db_user";
    $pass   = "123456789";
    $server = "localhost";
    $bbdd   = "artdinamica";
       
    $connection = mysqli_connect( $server, $user, $pass );
    $connection->query("SET NAMES 'utf8'");
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }

    // gets employees from BBDD
    function get_employees() {
        $q = '  
                SELECT  t1.id, t1.name, t1.lastname, t1.address, t1.birth_date, t1.dni, t1.photo,
                        t2.start_date, t2.finish_date, 
                        t3.name as "location", 
                        t4.name as "charge", t4.job_hours, t4.entrance_time, t4.exit_time
                FROM employees t1, contracts t2 , workplaces t3, roles t4
                WHERE t1.contract_id = t2.id AND t2.workplace_id = t3.id AND t2.role_id = t4.id;
        ';
        $result = do_query( $q );
        $employees = [];
        while ( $row = $result->fetch_object() ){
            $employees[] = $row;
        }
        return $employees;
    }

    // Gets the user input hours
    function get_user_reports( $employee_id ) {
        $q = 'SELECT * FROM reports where employee_id = ' .$employee_id;
        $result = do_query( $q );
        $reports= [];
        while ( $row = $result->fetch_object() ){
            $reports[] = $row;
        }
        return $reports;
    }

    // Exec a query in BBDD
    function do_query( $query ) {
        global $connection, $bbdd;
        $db = mysqli_select_db( $connection, $bbdd ) or die( 'No se puede conectar con la bbdd: ' .$bbdd );
        $result = mysqli_query( $connection, $query ) or die( 'No se ha podido realizar la consulta: ' .$query);

        return $result;
    }

    // Updates BBDD  qith deviations
    function save_employee_deviation( $employee_id, $deviation ) {
        $today = date("Y-m-d");
        // check if deviation data exist for today
        $q = 'SELECT * FROM deviations where employee_id = ' .$employee_id .' AND update_date = "' .$today .'";'; 
        $reg = do_query($q);

        if ( mysqli_num_rows( $reg ) === 0 ) {
            // insert
            $q = "INSERT INTO deviations VALUES (NULL, '" .$employee_id ."', '" .$deviation ."', '" .$today ."');";
            $insert = do_query($q);
            $row2 = $insert->fetch_object();
            var_dump ($row2);
        }
        
    }

    // Main algorithm than check the employees job hours
    function check_contract_hours() {
        // Get employees
        $employees = get_employees();
        
        $employees_schedule = [];
        
        // Iterate through the employees
        foreach ($employees as $emp) {
            $employee_reports = get_user_reports($emp->id);

            $tmp_report = [];

            $total_work_hours = 0;
            $total_work_days = 0;
            $average_hours_per_day = 0;
            
            $tmp_days_ok_count = 0;
            $tmp_days_ok = [];

            $tmp_days_overtime_count = 0;
            $tmp_days_overtime = [];
            
            $tmp_days_incomplete_count = 0;
            $tmp_days_incomplete = [];

            $employee_deviation = 0;

            // Iterate through the employees's reports
            foreach ($employee_reports as $report) {
                // check for overtimed work days
                if ( $report->hours_worked > $emp->job_hours ) {
                    $tmp_days_overtime_count++;
                    $tmp_days_overtime[] = Array(
                        'date' => $report->date,
                        'hours' => $report->hours_worked - $emp->job_hours
                    );
                }
                // check for incomplete work days
                if ( $report->hours_worked > $emp->job_hours ) {
                    $tmp_days_incomplete_count++;
                    $tmp_days_incomplete[] = Array(
                        'date' => $report->date,
                        'hours' => $report->hours_worked - $emp->job_hours
                    );
                }
                // check for complete work days
                if ( $report->hours_worked == $emp->job_hours ) {
                    $tmp_days_ok_count++;
                    $tmp_days_ok[] = Array(
                        'date' => $report->date,
                        'hours' => $report->hours_worked - $emp->job_hours
                    );
                }
                $total_work_days += 1;
                $total_work_hours += $report->hours_worked;
            }
            
            if ($total_work_days > 0) {
                /** Deviation **/
                // D = SQRT( (∑|x-π|)^2 / N)
                // 1. Calc. Average work_hours (π)            
                $average_hours_per_day = $total_work_hours / $total_work_days;
                $deltaHours = 0;
                // 2. For each hours data
                foreach ($employee_reports as $report) {
                    // 3. Sum power 2 distance to average hours ∑(x-π)^2
                    //$deltaHours += pow($report->hours_worked - $average_hours_per_day, 2);
                    $deltaHours += pow($report->hours_worked - $emp->job_hours, 2);
                }
                // Paso 4: divide by data count. (N)
                $dev_sqrt = $deltaHours / $total_work_days;
                // Paso 5: calc square root.
                $employee_deviation = sqrt($dev_sqrt);
            }

            // Set the time report of an employee
            $tmp_report = Array(
                'employee_id' => $emp->id,
                'photo' => $emp->photo,
                'name' => $emp->name,
                'lastname' => $emp->lastname,
                'address' => $emp->address,
                'birth_date' => $emp->birth_date,
                'dni' => $emp->birth_date,
                'location' => $emp->location,
                'charge' => $emp->charge,
                'start_date' => $emp->start_date,
                'finish_date'=> $emp->finish_date,
                'entrance_time' => $emp->entrance_time,
                'exit_time' => $emp->exit_time,
                'total_work_days' => (int)$total_work_days,
                'average_hours_by_day' => (float)$average_hours_per_day,
                'job_hours' => (float)$emp->job_hours,
                'deviation' =>  (float)$employee_deviation,
                'time_report' => Array(                
                    'days_ok' => (int)$tmp_days_ok_count,
                    'days_ok_dates' => $tmp_days_ok,
                    'days_overtime' => (int)$tmp_days_overtime_count,
                    'days_overtime_dates' => $tmp_days_overtime,
                    'days_incomplete' => (int)$tmp_days_overtime_count,
                    'days_incomplete_dates' => $tmp_days_incomplete,
                )
            );
            save_employee_deviation($emp->id, $employee_deviation);

            $employees_schedule[] = $tmp_report;
        }
        return $employees_schedule;
    }
?>