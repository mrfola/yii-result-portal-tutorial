<?php
    use yii\bootstrap4\ActiveForm;
    use yii\helpers\Html;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>                        
    <div class="container dashboard">

        <h1>Admin Dashboard</h1>
        <div class="student-results">

            <?php
            $session = Yii::$app->session;
            $errors = $session->getFlash('errorMessages');
            $success = $session->getFlash('successMessage');
            if(isset($errors) && (count($errors) > 0))
            {
                foreach($session->getFlash('errorMessages') as $error)
                {
                    echo "<div class='alert alert-danger' role='alert'>$error[0]</div>";
                }
            }

            if(isset($success))
            {
                echo "<div class='alert alert-success' role='alert'>$success</div>";
            }
            ?>

        <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Maths</th>
                <th scope="col">English</th>
                <th scope="col">History</th>
                <th scope="col">Mental Studies</th>
                <th scope="col">Negotation Studies</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 

                foreach($students as $index => $student)
                {
            ?>
            <tr>
            <th scope="row"><?=  $index+1; ?></th>
                <td><?= $student["name"]; ?></td>
                <td><?= $student["subjects"]["Maths"]["score"]; ?></td>
                <td><?= $student["subjects"]["English"]["score"]; ?></td>
                <td><?= $student["subjects"]["History"]["score"]; ?></td>
                <td><?= $student["subjects"]["Mental Studies"]["score"]; ?></td>
                <td><?= $student["subjects"]["Negotiation Studies"]["score"]; ?></td>
                <td>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="<?= '#updateModal'.$index; ?>">
                Update
                </button>
                </td>
            </tr>


            <!-- Update Modal -->
            <div class="modal fade" id="<?= 'updateModal'.$index; ?>" tabindex="-1" aria-labelledby="<?= 'updateModal'.$index; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="<?= 'updateModal'.$index; ?>"><?= 'Update Result For '.$student["name"]; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php
                    ActiveForm::begin([
                        "action" => ["admin/update-user-result"],
                        "method" => "post"
                    ]);
                ?>
                <div class="modal-body">

                    <div class="form-group mx-2">

                        <?php

                        //hidden field containing student id
                        echo Html::hiddenInput('user_id', $student['id']);

                        foreach($student["subjects"] as $subjectName => $subject)
                        {
                        ?>
                            <label for="exampleFormControlSelect1"><?= $subjectName;?></label>
                            <input 
                            name = "<?= $subject["subject_id"];?>"
                            type="number"
                            min="0" 
                            max="100" 
                            class="form-control" 
                            id="score" 
                            placeholder="Enter score"
                            value = "<?= $subject["score"]; ?>"
                            >
                        <?php
                        }
                        ?>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>

                <?php
                    ActiveForm::end();
                ?>

                </div>
            </div>

            <?php
                }
            ?>

        </tbody>
        </table>
        </div>
    </div>
</div>
</body>
</html>
