<?php require_once(dirname(__FILE__).'/../../../config/variablesGenerales.php'); ?>
<?php echo "<?xml version='1.0' encoding='UTF-8'?>
<settings>     
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
      <![CDATA[]]>                                   
    </data_labels>
    <balloon_text>                                                    
      <![CDATA[{series} : {value} (minutos)]]>           
    </balloon_text>    
    <corner_radius_top>10</corner_radius_top>                
  </column>
  
  <plot_area>
    <margins>
      <bottom>50</bottom>
    </margins>
  </plot_area>
  
  <line>                                                    
    <data_labels>
       <![CDATA[]]>                                   
    </data_labels>
    <balloon_text>                                                    
      <![CDATA[]]>                                     
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
  
<!-- titulo eje x -->
  <values> 
   <category>     
      <enabled>true</enabled>  	
      <inside>true</inside>
      <rotate>true</rotate>
    </category>
    <value>
      <enabled>true</enabled>
    </value>
  </values>
  
<!-- titulo de la grafica -->
  <labels> 
    <label lid='0'>
      <x>5</x>
      <y>20</y>
      <width>520</width> 
      <align>center</align>
      <text> 
        <![CDATA[<b>Eventos  /  Tiempo</b>]]>
      </text> 
    </label>
    <label lid='2'>
      <x>47%</x> 
      <y>358</y>
      <width>42</width>
      <align>right</align> 
      <text>
        <![CDATA[<b>Evento</b>]]>
      </text> 
    </label>
    <label lid='2'>
      <x>5</x> 
      <y>60%</y>
      <rotate>true</rotate> 
      <width>100</width>
      <align>left</align>
      <text>
        <![CDATA[<b>Tiempo (minutos)</b>]]>
      </text> 
    </label>
  </labels> 
  
  <legend> 
    <enabled>false</enabled>   
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
  
  <background> 
    <color>#FFFFFF</color>
	<alpha>100</alpha>
	<border_color>#FFFFFF</border_color>
	<border_alpha>100</border_alpha>
  </background>
</settings>"; ?>
