<?php
// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Include config file
    require_once "config.php";
    
    // Start transaction
    mysqli_begin_transaction($link);

    try {
        // Prepare a delete statement
        $sql = "DELETE FROM tasks WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = (int) trim($_POST["id"]);
            
            // Attempt to execute the prepared statement
            if(!mysqli_stmt_execute($stmt)){
                throw new Exception("Could not execute delete statement.");
            }
        } else {
            throw new Exception("Could not prepare delete statement.");
        }
        
        // Resequence the ids
        $resequence_sql = "SET @count = 0; UPDATE tasks SET id = @count:= @count + 1; ALTER TABLE tasks AUTO_INCREMENT = 1;";
        
        if (!mysqli_multi_query($link, $resequence_sql)) {
            throw new Exception("Could not resequence ids.");
        }
        
        // Commit transaction
        mysqli_commit($link);
        
        // Records deleted and ids resequenced successfully. Redirect to landing page
        header("location: index.php");
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction
        mysqli_rollback($link);
        echo "Something went wrong. Please try again later.";
    }
    
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
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
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Excluir tarefa</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <p>Você tem certeza que deseja excluir esta tarefa?</p>
                            <p>
                                <input type="submit" value="Excluir" class="btn btn-danger">
                                <a href="index.php" class="btn btn-secondary">Cancelar</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
