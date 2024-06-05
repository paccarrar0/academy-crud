<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$task = $date = $priority = "";
$task_err = $date_err = $priority_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_task = trim($_POST["task"]);
    if(empty($input_task)){
        $task_err = "Insira o nome da tarefa.";
    } elseif(!filter_var($input_task, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $task_err = "Insira um nome válido.";
    } else{
        $task = $input_task;
    }
    
    // Validate address address
    $input_date = trim($_POST["date"]);
    if(empty($input_date)){
        $date_err = "Insira o prazo da tarefa.";     
    } else{
        $date = $input_date;
    }
    
    // Validate salary
    $input_priority = trim($_POST["priority"]);
    if(empty($input_priority)){
        $priority_err = "Insira a prioridade da tarefa.";     
    } else{
        $priority = $input_priority;
    }
    
    // Check input errors before inserting in database
    if(empty($task_err) && empty($date_err) && empty($priority_err)){
        // Prepare an update statement
        $sql = "UPDATE tasks SET task=?, data=?, priority=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_task, $param_date, $param_priority, $param_id);
            
            // Set parameters
            $param_task = $task;
            $param_date = $date;
            $param_priority = $priority;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM tasks WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $task = $row["task"];
                    $date = $row["data"];
                    $priority = $row["priority"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Algo deu errado, tente novamente mais tarde.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
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
                    <h2 class="mt-5">Atualizar tarefa</h2>
                    <p>Edite o formulário para atualizar a tarefa</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Tarefa</label>
                            <input type="text" name="task" class="form-control <?php echo (!empty($task_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $task; ?>">
                            <span class="invalid-feedback"><?php echo $task_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Data</label>
                            <input name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
                            <span class="invalid-feedback"><?php echo $date_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Prioridade</label>
                            <input type="text" name="priority" class="form-control <?php echo (!empty($priority_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $priority; ?>">
                            <span class="invalid-feedback"><?php echo $priority_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn1" value="Alterar">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancelar</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
