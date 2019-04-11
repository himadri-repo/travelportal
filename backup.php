 <?php
$folder="/home/qeded4rkn8ua/public_html/";
$output="compressed.zip";
$zip = new ZipArchive();
 
if ($zip->open($output, ZIPARCHIVE::CREATE) !== TRUE) {
    die ("Unable to open Archirve");
}
 
$all= new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder));
 
foreach ($all as $f=>$value) {
	$zip->addFile(realpath($f), $f) or die ("ERROR: Unable to add file: $f");
}
$zip->close();
echo "Compressed Successfully";
?>