<?php
if (!empty($_GET['ticket'])) {
    $fileName = basename($_GET['ticket']);
    $filePath = "../tickets/{$fileName}";

    if (!empty($fileName) && file_exists($filePath)) {
        $newFileName = str_replace('txt', 'html', $fileName);

        // Define headers
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$newFileName;");
        
        // Read and output the file
        readfile($filePath);
        exit;
    } else {
        echo "Error...";
    }
}
?>
