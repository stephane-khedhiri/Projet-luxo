<?php

namespace Core\HTML;
class Graph
{
    public static $options = array(
        'width'     => 600,
        'height'    => 300,
        'steps'      => 5
    );

    public static function line($datas, $titre,$options = array()){
        $options = array_merge(self::$options, $options);
        extract($options);
        $length = (count($datas) != 0?count($datas):4);
        $max    = max($datas);
        $pow    = ceil(pow(10, strlen($max) - 1));
        $max    = ceil(round(round($max / $pow) * $pow));
        $stepX  = $width / ($length  - 1);
        $ratioY = $height / $max/10;

        $return = "<div class='graph' style='width:{$width}px; height:{$height}px;'>";

        // On écrit l'axe des X
        $return .= "<div class='graph-x'>";
        $i = 0;
        foreach($datas as $label => $value){
            $return .= "<span style='left:" . ($i * $stepX) . "px'>$label</span>";
            $i++;
        }
        $return .= "</div>";
        // On écrit l'axe des Y
        $return .= "<div class='graph-y'>";
        for($i = $steps; $i >= 0; $i--){
            $return .= "<span style='bottom:" . (100 * $i / $steps ) . "%'>" . ($max*10 * $i / $steps ) . "</span>";
        }
        $return .= '</div>';

        // On dessine notre graph
        $return .= "<svg style='width:{$width}px; height:{$height}px;'>";
        $i = 0;
        $return .= "<path d='";
        foreach($datas as $label => $v){
            if($i == 0){
                $return .= 'M ' . $i * $stepX . ' ' . ($height - $v * $ratioY);
            }else{
                $return .= ' L ' . $i * $stepX . ' ' . ($height - $v * $ratioY);
            }
            $i++;
        }
        $return .= "' class='graph-line'/>";

        // On le remplit
        $return .= "<path d='";
        $i=0;
        foreach($datas as $label => $v){
            if($i == 0){
                $return .= 'M ' . $i * $stepX . ' ' . ($height - $v * $ratioY);
            }else{
                $return .= ' L ' . $i * $stepX . ' ' . ($height - $v * $ratioY);
            }
            $i++;
        }
        $return .= ' L ' . ($i - 1) * $stepX . ' ' . $height . " L 0 $height";
        $return .= "' class='graph-fill'/>";


        // On fait des ptis traits verticaux
        $i = 0;
        foreach($datas as $label => $v){
            $return .= '<path d="M ' . $i * $stepX . ' 0 L ' . $i * $stepX . ' ' . $height . '" class="graph-v"/>';
            $i++;
        }

        // On fait des ptis traits horizontaux !
        $i = 0;
        for($i = $steps; $i > 0; $i--){
            $return .= '<path d="M 0 ' . $i * $height / $steps . ' L ' . $width . ' ' . $i * $height / $steps . '" class="graph-v"/>';
        }

        // Le bord noir du graph
        $return .= "<path d='M 0 0 L 0 $height L $width $height' class='graph-stroke'/>";


        // On fait les pti cercles (à la fin pour être au dessus)
        $i=0;
        foreach($datas as $label => $v){
            $return .= '<circle cx="' . $i * $stepX . '" cy="' .  ($height - $v * $ratioY) . '" r="4" stroke="black" stroke-width="2" fill="red" class="graph-point"/>';
            $i++;
        }
        $return .= '<text class="graph-titre" x="'.($width/2).'" y="'.($height/$steps/2).'" >'.$titre.'</text>';
        $return .= "</svg>";
        $return .= "</div>";

        return $return;
    }
}