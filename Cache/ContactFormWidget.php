<?php class_exists('Kernel\Template') or exit; ?>
<h1>
<?php 
ob_start(); 
echo $title; 
$output = ob_get_contents(); 
ob_end_clean(); 
if (preg_match_all("~\[widget id=\"([0-9]+)\"\]~is", $output, $matches)) {
    array_shift($matches);
    foreach($matches[0] as $match) {
        Kernel\PluginWidget::get($match);
    }
}
$output = preg_replace("~\[widget id=\"([0-9]+)\"\]~is", "", $output);
echo $output;

?>
</h1>