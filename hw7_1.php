<form method="POST">
    <input name="word" type="text" />
    <input name="translate" type="text" />
    <input type="submit" value="Add" />
</form>

<?php

if(
    isset($_POST['word']) &&
    isset($_POST['translate']) &&
    !empty($_POST['word']) &&
    !empty($_POST['translate'])
) {
    $db = fopen("words.db", 'a+');
    fputcsv($db, [
        $_POST['word'], $_POST['translate']
    ]);
    fclose($db);
}

$db = fopen("words.db", "r");
$wordsCount = 0;
if ($db) {
    while (!feof($db)) {
        fgetcsv($db);
        $wordsCount++;
    }
}
$maxPage = ceil($wordsCount / 20);
if(isset($_GET['page']) && $_GET['page'] > 1) {
    $pageNum = $_GET['page'];
} elseif (isset($_GET['page']) && $_GET['page'] > $maxPage) {
    $pageNum = $maxPage;
} else {
    $pageNum = 1;
}
fseek($db, 0);
?>

<table width="100%" border="1">
    <thead>
    <tr>
        <th><a href="?hide=<?php echo isset($_GET['hide']) && $_GET['hide'] == 'left' ? 'none' : 'left' ?>">Hide</a></th>
        <th><a href="?hide=<?php echo isset($_GET['hide']) && $_GET['hide'] == 'right' ? 'none' : 'right' ?>">Hide</a></th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($pageNum > 1) {
        for ($n = 0; $n < (($pageNum - 1) * 20) && !feof($db); $n++) {
            fgetcsv($db);
        }
    }
    for($i=0; $i < 20 && !feof($db); $i++) {
        $array = fgetcsv($db);
        if(!empty($array)) {
            echo "<tr><td width='50%'>".
                ((isset($_GET['hide']) && $_GET['hide'] == 'left') ? '' : $array[0]).
                "</td><td width='50%'>".
                ((isset($_GET['hide']) && $_GET['hide'] == 'right') ? '' : $array[1]).
                "</td></tr>\n";
        }
    }
    ?>
    <table width="20%">
        <thead>
        <tr>
            <th><a href="?page=<?php echo isset($_GET['page']) && $_GET['page'] > '1' ? ($_GET['page'] - 1) : '1' ?>">
                    <?php if ($pageNum != 1) {
                        echo 'Prev';
                    } ?></a></th>
            <th><a href="?page=<?php echo isset($_GET['page']) && $_GET['page'] < $maxPage ? ($_GET['page'] + 1) : $maxPage ?>">
            <?php if ($pageNum != $maxPage) {
                        echo 'Next';
                    } ?></a></th>
        </tr>
        </thead>
    </table>


