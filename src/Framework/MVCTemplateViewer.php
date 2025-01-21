<?php 
declare( strict_types = 1 );

namespace Framework;

class MVCTemplateViewer implements TemplateViewerInterface{
    
    public function render(string $template, array $data = []): string
    {
        $code = file_get_contents( ROOT_PATH . "/views/$template" );
        $code = $this->replaceVariables($code);
        $code = $this->replacePHP($code);

        extract( $data, EXTR_SKIP );
        ob_start();
        eval( "?>$code" );
        // require ROOT_PATH . "/views/$template";
        return ob_get_clean();
    }
    
    private function replaceVariables( string $code ): string {
        return preg_replace("#{{\s*(\S+)\s*}}#", "<?= htmlspecialchars(\$$1) ?>", $code);
    }
    
    private function replacePHP(string $code) : string {
        return preg_filter("#{%\s*(.+)\s*%}#", "<?php $1 ?>", $code);
    }
    
}