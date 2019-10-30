<?php
    // import functions
    require 'functions.php';
    // get employees data
    $schedule = check_contract_hours();
    // set data type styles and info
    
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Employees report</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="/">Artdinamica</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/prueba_php">Prueba PHP <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="/modelo_datos">Modelo de datos</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </nav>
        <div class="container my-4">     
                <?php
                    foreach ($schedule as $emp) {
                        if ( $emp['job_hours'] > $emp['average_hours_by_day'] ) {
                            $alert_type = 'danger';
                            $desc = 'no está cumpliendo con su contrato';
                        } else if ( $emp['job_hours'] < $emp['average_hours_by_day'] ) {
                            $alert_type = 'warning';
                            $desc = 'está cumpliendo mas que su contrato';
                        } else if ( $emp['job_hours'] === $emp['average_hours_by_day'] ) {
                            $alert_type = 'success';
                            $desc = 'cumple con su contrato';
                        }
                ?>
                        <div class="alert alert-<?php echo $alert_type; ?>" role="alert">
                            <p>El empleado <strong><?php echo $emp['name'] .' ' .$emp['lastname']; ?></strong> <?php echo $desc; ?></p>
                            <hr>
                            <div class="py-4">Su desviación es: <?php echo number_format($emp['deviation'],2); ?> 
                                <a class="btn btn-<?php echo $alert_type; ?> btn-sm float-right" 
                                    data-toggle="collapse" 
                                    href="#employeeInfo<?php echo $emp['employee_id']; ?>" 
                                    role="button" 
                                    aria-expanded="false" 
                                    aria-controls="#employeeInfo<?php echo $emp['employee_id']; ?>">
                                    Más información
                                </a>
                            </div>
                            <div class="collapse multi-collapse" id="employeeInfo<?php echo $emp['employee_id']; ?>">
                                <div class="info-panel">
                                    <div>
                                        <img class="photo" src="<?php echo $emp['photo']; ?>" alt="<?php $emp['name'] . ' ' .$emp['lastname'] ?>">
                                    </div>
                                    <div class="dates">
                                        <div class="start">
                                            <strong>Inicio del contrato</strong> <?php echo $emp['start_date'] ?>
                                            <span></span>
                                        </div>
                                        <div class="ends">
                                            <strong>Fin del contrato</strong> <?php echo isset($emp['finish_date']) ? $emp['finish_date'] : '---' ?>
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="row info-group">
                                        <h5 class="w-100">Datos personales</h5>
                                        <div class="col col-12 col-sm-12 col-md-3">
                                            <strong>Employee ID</strong> <?php echo $emp['employee_id'] ?>
                                        </div>
                                        <div class="col col-12 col-sm-12 col-lg-5">
                                            <strong>Nombre</strong> <?php echo $emp['name'] .' ' .$emp['lastname']?>
                                        </div>
                                        <div class="col col-6 col-lg-2">
                                            <strong>Fecha nacimiento</strong> <?php echo $emp['birth_date'] ?>
                                        </div>
                                        <div class="col col-6 col-lg-2">
                                            <strong>DNI</strong> <?php echo $emp['dni'] ?>
                                        </div>
                                        <div class="w-100"></div>
                                        <div class="col col-12">
                                            <strong>Dirección</strong> <?php echo $emp['address'] ?>
                                        </div>                                                
                                    </div>
                                    
                                    <div class="row info-group">
                                        <h5 class="w-100">Datos de contrato</h5>
                                        <div class="col col-4 col-sm-auto">
                                            <strong>Cargo</strong> <?php echo $emp['charge'] ?>
                                        </div>
                                        <div class="col col-4 col-sm-auto">
                                            <strong>Horas de trabajo</strong> <?php echo $emp['job_hours'] ?>
                                        </div>
                                        <div class="col col-4 col-sm-1auto">
                                            <strong>Localización</strong> <?php echo $emp['location'] ?>
                                        </div>
                                        <div class="col col-4 col-sm-auto">
                                            <strong>Hora de entrada</strong> <?php echo $emp['entrance_time'] ?>
                                        </div>
                                        <div class="col col-4 col-sm-auto">
                                            <strong>Hora de salida</strong> <?php echo $emp['exit_time'] ?>
                                        </div>                                                
                                    </div>
                                    <div class="row info-group">
                                        <h5 class="w-100">Datos de trabajo</h5>
                                        <div class="col col-6 col-sm-auto">
                                            <strong>Días trabajados</strong> <?php echo $emp['total_work_days'] ?>
                                        </div>
                                        <div class="col col-6 col-sm-auto">
                                            <strong>Media de horas trabajadas</strong> <?php echo $emp['average_hours_by_day'] ?>
                                        </div>                         
                                    </div>
                                    <div class="row info-group">
                                        
                                        <div class="col col-12 col-lg-4">
                                            <strong>Días cumplidos</strong> <?php echo $emp['time_report']['days_ok'] ?>
                                            <div>
                                                <?php
                                                    foreach ($emp['time_report']['days_ok_dates'] as $val) {
                                                ?>
                                                        <span class="badge badge-pill badge-success"><?php echo $val['date']; ?></span>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>  
                                        
                                        <div class="col col-12 col-lg-4">
                                            <strong>Días incumplidos</strong> <?php echo $emp['time_report']['days_incomplete'] ?>
                                            <div>
                                                <?php
                                                    foreach ($emp['time_report']['days_incomplete_dates'] as $val) {
                                                ?>
                                                        <span class="badge badge-pill badge-danger"><?php echo $val['date']; ?></span>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>  

                                        <div class="col col-12 col-lg-4">
                                            <strong>Días Horas extra</strong> <?php echo $emp['time_report']['days_overtime'] ?>
                                            <div>
                                                <?php
                                                    foreach ($emp['time_report']['days_overtime_dates'] as $val) {
                                                ?>
                                                        <span class="badge badge-pill badge-warning"><?php echo $val['date']; ?></span>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                ?>
        </div>
    </body>
</html>