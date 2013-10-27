<?php require_once(dirname(__FILE__).'/../../../config/variablesGenerales.php'); ?>
<?php echo "<?xml version='1.0' encoding='UTF-8'?>
<settings> 
<!--para que se vea 3d
  <depth>10</depth>
  <angle>40</angle>
-->
  <column>
    <type>clustered</type>
    <width>85</width>
    <spacing>0</spacing>                                    
    <grow_time>3</grow_time>                                 
    <sequenced_grow>true</sequenced_grow>                    
    <grow_effect>elastic</grow_effect>                       
    <alpha>100</alpha>                                       
    <border_color>#FFFFFF</border_color>                     
    <data_labels>
      <![CDATA[{value} %]]>                                   
    </data_labels>
    <data_labels_position>above</data_labels_position>
    <data_labels_always_on>true</data_labels_always_on>
    <balloon_text>                                                    
      <![CDATA[{title} {series} : {value} %]]>           
    </balloon_text>
    <corner_radius_top>10</corner_radius_top>                
  </column>
  
  <line>                                                    
    <data_labels>
       <![CDATA[{value}]]>                                   
    </data_labels>
    <balloon_text>                                                    
      <![CDATA[{value}]]>                                     
    </balloon_text>      
  </line>
     
  <axes>
    <category>     
      <alpha>0</alpha>
    </category>
    <value>    
      <color></color>
      <alpha>5</alpha>
      <width>1</width>
    </value>
  </axes>  
  
  <values> 
    <category>            
      <rotate>90</rotate>       
    </category>
  </values>
  <!-- titulo de la grafica -->
  <labels> 
    <label lid='0'>
      <x>5</x>
      <y>20</y>
      <width>520</width> 
      <align>center</align>
      <text> 
        <![CDATA[<b>Comparativo<br>Indicadores Vs Metas</b>]]>
      </text> 
    </label>
  </labels> 
  
  <legend> 
    <enabled>true</enabled>   
  </legend>
  
  <export_as_image>
    <file>".$urlWeb."flash/amline/export.php</file>     
    <color>#CC0000</color>                      
    <alpha>50</alpha>                           
  </export_as_image>  
  
  <error_messages>
    <color>D8D8D8</color>   
    <alpha></alpha>   
    <text_color>000000</text_color> 
    <text_size></text_size>   
  </error_messages>    
  
  <strings>
    <no_data>No hay datos para la configuración dada</no_data>
  </strings>    
</settings>"; ?>
