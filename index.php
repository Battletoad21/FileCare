<?php include('inc/head.php'); ?>
<?php
if (isset($_POST['content'])) {
    $file = $_POST['file'];
    $fileOpen = fopen($file, 'w');
    fwrite($fileOpen, stripslashes($_POST['content']));
    fclose($fileOpen);
}
?>


<?php

function showDir($dir)
{
    if (is_dir($dir)) {
        if ($openDir = opendir($dir)) {
            echo "<ul>";
            while (($file = readdir($openDir)) !== false) {
                if ($file == ".." || $file == "." || $file == "index.php") {
                    continue;
                } else {
                    if (is_dir("$dir/$file")) {
                        echo "<li>$file <a href='?d=$dir/$file' class ='btn'>Delete</a></li>";
                        showDir("$dir/$file");
                    } else {
                        echo "<li><a href='?f=$dir/$file'>$file</a> <a href='?d=$dir/$file' class ='btn'>Delete</a></li>";
                    }
                }
            }
            echo "</ul>";
        }
    }
}
showDir('files');
if (isset($_GET['d'])) {
    $fileDelete = $_GET['d'];
    if (is_dir($fileDelete)) {
        deleteDir($fileDelete);
    } else {
        unlink($fileDelete);
    }
    header('Location: index.php');
    exit();
}
function deleteDir($dirname)
{
    if (is_dir($dirname))
        $dirCarry = opendir($dirname);
    if (!$dirCarry)
        return false;
    while ($file = readdir($dirCarry)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file))
                unlink($dirname . "/" . $file);
            else
                deleteDir($dirname . '/' . $file);
        }
    }
    closedir($dirCarry);
    rmdir($dirname);
    return true;
}
if (isset($_GET['f'])) {
    echo "<h3>{$_GET["f"]}</h3>";
    $file = $_GET['f'];
    $content = file_get_contents($file);
    if (isset($_POST['content'])) {
        $file = $_POST['file'];
        $fileOpen = fopen($file, 'w');
        fwrite($fileOpen, stripslashes($_POST['content']));
        fclose($fileOpen);
    }
    $extension = pathinfo($_GET['f'])['extension'];
    ?>

    <form method="POST" action="">
                <textarea name="content" style="width: 100%;height: 200px;">
                    <?php
                    if ($extension != 'jpg') {
                        echo $content;
                    }
                    ?>
                </textarea>
        <input type="hidden" name="file" value="<?php echo $_GET['f'] ?>">
        <input type="submit" value="Edit">
    </form>

<?php } ?>


<?php include('inc/foot.php'); ?>