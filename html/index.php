<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AcademyTask</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
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
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Tarefas</h2>
                        <a href="create.php" class="btn btn1 pull-right"><i class="fa fa-plus"></i> Nova Tarefa</a>
                    </div>
                    <?php
                    
                    require_once "config.php";
                    
                    $sql = "SELECT * FROM tasks";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Índice</th>";
                                        echo "<th>Tarefa</th>";
                                        echo "<th>Data</th>";
                                        echo "<th>Prioridade</th>";
                                        echo "<th>Alterar</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){

                                    $date = DateTime::createFromFormat('Y-m-d', $row['data']);
                                    $formattedDate = $date ? $date->format('d/m/Y') : $row['data'];

                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['task'] . "</td>";
                                        echo "<td>" . $formattedDate . "</td>";
                                        echo "<td>" . $row['priority'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="read.php?id='. $row['id'] .'" class="mr-3"><span class="fa fa-eye" style="color: black"></span></a>';
                                            echo '<a href="update.php?id='. $row['id'] .'" class="mr-3"><span class="fa fa-pencil" style="color: black"></span></a>';
                                            echo '<a href="delete.php?id='. $row['id'] .'" "><span class="fa fa-trash" style="color: black"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>Nenhuma tarefa foi adicionada.</em></div>';
                        }
                    } else{
                        echo "Algo deu errado, tente novamente mais tarde.";
                    }
 
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>
