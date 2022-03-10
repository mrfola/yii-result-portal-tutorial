<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
</head>
<body>
    <div class="container">
        <h1>Student Dashboard</h1>

        <div class="student-results">
        <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Subject</th>
                <th scope="col">Score</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach($results as $index => $result)
                {
            ?>
            <tr>
            <th scope="row"><?=  $index+1; ?></th>
                <td><?= $result["subject"]; ?></td>
                <td><?= $result["score"]; ?></td>
            </tr>

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
