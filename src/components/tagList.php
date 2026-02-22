<?php

function tagList($id, $array, $tm, $element = 'default') {
    global $styleVar, $style, $script, $script_files, $media_mobile_portrait_geral, $media_mobile_landscape_geral, $media_desktop_landscape_geral, $media_tablet_portrait_geral, $media_tablet_landscape_geral;
    
    $tag = [];
    $length = count($array);

    // 1. Registro de Variáveis e Assets
    if(!in_array("--tagList-tm:$tm;", $styleVar)){
        $styleVar[] = "--tagList-tm:$tm;";
    }
    
    // Garante que o script de gerenciamento de Blobs/Iframesheet seja carregado
    $component_script = ROOT_PATH_WARANAS_LIB . '/public/assets/js/components/embedBlob.js';
    if (!in_array($component_script, $script_files)) {
        $script_files[] = $component_script;
    }

    // 2. Carregamento dos CSS (Conforme estrutura original)
    $style[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/tagList.css");
    $media_desktop_landscape_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/desktopL/tagList.css");
    $media_mobile_landscape_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/mobileL/tagList.css");
    $media_mobile_portrait_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/mobileP/tagList.css");
    $media_tablet_portrait_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/tabletP/tagList.css");
    $media_tablet_landscape_geral[] = file_get_contents(ROOT_PATH_WARANAS_LIB . "/public/assets/css/components/tabletL/tagList.css");
    
    for ($i = 0; $i < $length; $i++) {
        $source = $array[$i]['media']; 
        $text = $array[$i]['text'];    
        
        $innerContent = '';
        $anchorAttributes = "class='tag-link'";

        // 3. Lógica de seleção do Elemento com Integração Iframesheet
        switch ($element) {
            case 'image':
                $innerContent = "<img src='{$source}' alt='{$text}' {$anchorAttributes} style='width:100%; height:100%; object-fit:cover;'>";
                break;

            case 'video':
                $innerContent = "<video src='{$source}' {$anchorAttributes} autoplay muted loop playsinline style='width:100%; height:100%; object-fit:cover;'></video>";
                break;

            case 'audio':
                $innerContent = "<audio src='{$source}' {$anchorAttributes} controls style='width:100%;'></audio>";
                break;

            case 'iframe':
            case 'embed':
                // REGISTRO NO IFRAMESHEET [Lógica Centralizada]
                // getIframeRef verifica a URL e retorna o ID (eblob_...)
                $ref = getIframesheetRef($source); 
                $uniqueId = "el_{$id}_{$i}";
                
                // Cria o placeholder que será preenchido pelo Blob na RAM
                // Dentro do loop do tagList
                $innerContent = setIframesheet("ifrm_{$id}_{$i}", $source, $element);
                
                // Injeta o comando JS para carregar o Blob neste placeholder
                $script[] = "initIframesheet('{$uniqueId}', '{$ref}', '{$source}', '{$element}');";
                break;

            default:
                // Caso 'default' utiliza o sistema original de background-image (CSS Variable)
                $innerContent = "
                    <a href='{$array[$i]['url']}' 
                       class='tag-link' 
                       aria-label='{$text}' 
                       style='--bg-img: url({$source});' 
                       data-bg='url({$source})'>
                    </a>";
                break;
        }
        
        $tag[] = "
            <section class='tag-section {$id}'>
                {$innerContent}
                <p readonly rows='12' aria-label='Descrição'>{$text}</p>
            </section>"; 
    }

    return "<div class='tagList' id='{$id}'>" . implode('', $tag) . "</div>";
}