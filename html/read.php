<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM tasks WHERE id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $name = $row["task"];
                $address = $row["data"];
                $salary = $row["priority"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
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
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
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
                    <h1 class="mt-5 mb-3">Visualizar tarefa</h1>
                    <div class="form-group">
                        <label>Tarefa</label>
                        <p><b><?php echo $row["task"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Data</label>
                        <p><b><?php echo $row["data"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Prioridade</label>
                        <p><b><?php echo $row["priority"]; ?></b></p>
                    </div>
                    <p><a href="index.php" class="btn btn1">Voltar</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
