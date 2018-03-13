<?php
if(isset($_GET['theme'])){
    $req = $_GET['theme'];
    $array = explode('|', $req);
    setTheme($array);
}

function setTheme($array){
    $theme = $array[0];
    $period = $array[1];
    
    $additional_text='</span></span>
        </span>
    </p>

    <table align="center" border="0" cellpadding="5" cellspacing="0" style="width:650px; text-align: center;">
        <tbody>
            <tr style="background-color: #0b75b7;">
                <td><span style="color:#ffffff;"><span style="font-size:24px;"><span style="font-family:verdana,geneva,sans-serif;">';
    
    $footer_text='</span></span>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>

    <p>&nbsp;</p>';
    
    $pre_heater_text=readPreHeaderHtml();
    $text_to_file = $pre_heater_text.$theme.$additional_text.$period.$footer_text;
    
    writeHeaderHtml($text_to_file);
    
}

//Reading pre-header of the HTML file
function readPreHeaderHtml(){
    $fp = fopen("../html_template/pre_header.html", "r");
    $preHeaderHtml = '';
    if ($fp){
        while (!feof($fp)){
            $preHeaderHtml .= fgets($fp);
        }
    }
    fclose($fp);
    return $preHeaderHtml;
}

//Write header.html
function writeHeaderHtml($text_to_file){
    $fp = fopen('../html_template/header.html', 'w');
    fwrite($fp, $text_to_file);
    fclose($fp);
}