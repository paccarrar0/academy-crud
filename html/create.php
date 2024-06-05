<?php

require_once "config.php";
 
$task = $date = $priority = "";
$task_err = $date_err = $priority_err = "";

 
// Processing form data when form is submitted

/*-----------------------------------------------------------------*/
// Validate name
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_task = trim($_POST["task"]);
    if(empty($input_task)){
        $task_err = "Insira o nome da tarefa";
    } elseif(!filter_var($input_task, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $task_err = "Insira um nome válido.";
    } else{
        $task = $input_task;
    }

/*------------------------------------------------------------------------*/

/*------------------------------------------------------------------------*/

    // Validate address
    $input_date = trim($_POST["date"]);
    if(empty($input_date)){
        $date_err = "Insira o prazo da tarefa.";     
    } else{
        $date = $input_date;
    }

/*------------------------------------------------------------------------*/

/*------------------------------------------------------------------------*/

    // Validate salary
    $input_priority = trim($_POST["priority"]);
    if(empty($input_priority)){
        $priority_err = "Insira a prioridade da tarefa.";     
    } else{
        $priority = $input_priority;
    }

/*------------------------------------------------------------------------*/

    // Check input errors before inserting in database
    if(empty($task_err) && empty($date_err) && empty($prio_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO tasks (task, data, priority) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_task, $param_date, $param_priority);
            
            // Set parameters
            $param_task = $task;
            $param_date = $date;
            $param_priority = $priority;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Algo deu errado, tente novamente mais tarde.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>AcademyTask</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            .wrapper{
                width: 600px;
                margin: 0 auto;
            }
            .border {
                border: 1px solid #0A8967 !important;
                border-radius: 10px;
            }
            .btn1{
                background-color: #0A8967;
                color: white;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="d-flex justify-content-center mt-4 border">
                <img src="https://res.cloudinary.com/dnbbxja52/image/upload/v1717622700/academytask-devops/logo.svg" alt="logo">
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="mt-5">Adicionar tarefa</h2>
                        <p>Preencha o formulário para adicionar uma nova tarefa.</p>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label>Tarefa</label>
                                <input type="text" name="task" class="form-control <?php echo (!empty($task_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $task; ?>">
                                <span class="invalid-feedback"><?php echo $task_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>Data</label>
                                <input type="date" name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
                                <span class="invalid-feedback"><?php echo $date_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>Prioridade</label>
                                <!-- O segundo valor estará selecionado inicialmente -->
                                <select name="priority" class="form-control" <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
                                <option value="valor1">Valor 1</option>
                                <option value="valor2" selected>Valor 2</option>
                                <option value="valor3">Valor 3</option>
                                </select>

                            </div>
                            <input type="submit" class="btn btn1" value="Salvar">
                            <a href="index.php" class="btn btn-secondary ml-2">Cancelar</a>
                        </form>
                    </div>
                </div>        
            </div>
        </div>
    </body>
    </html>
