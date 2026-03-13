<?php
class BuildHelper {
    public static function build($project_path, $output_path) {
        $script = __DIR__ . '/../../scripts/build-android.sh';
        $cmd = "bash $script " . escapeshellarg($project_path) . " " . escapeshellarg($output_path) . " 2>&1";
        
        $output = [];
        $return_var = 0;
        exec($cmd, $output, $return_var);
        
        if($return_var === 0 && file_exists($output_path)) {
            return ['success' => true, 'file' => $output_path];
        } else {
            return ['success' => false, 'error' => implode("\n", $output)];
        }
    }
}
