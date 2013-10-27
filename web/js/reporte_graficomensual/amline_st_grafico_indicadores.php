<?php require_once(dirname(__FILE__).'/../../../config/variablesGenerales.php'); ?>
<?php echo "<?xml version='1.0' encoding='UTF-8'?>
<settings> 
<!-- fondo degradado de la grafica -->
  <background> 
    <color>#FFFFFF</color>
	<alpha>100</alpha>
	<border_color>#FFFFFF</border_color>
	<border_alpha>100</border_alpha>
  </background>
  <!-- margenes de la grafica -->
  <plot_area> 
    <margins> 
      <left>70</left>
      <top>60</top>
      <right>50</right> 
      <bottom>60</bottom> 
    </margins>
  </plot_area>
  <!-- titulo de la grafica -->
  <labels> 
    <label lid='0'>
      <x>5</x>
      <y>20</y>
      <width>520</width> 
      <align>center</align>
      <text> 
        <![CDATA[<b>Tendencia indicadores / día</b>]]>
      </text> 
    </label>
	<label lid='2'>
      <x>90%</x> 
      <y>338</y>
      <width>40</width>
      <align>right</align> 
      <text>
        <![CDATA[<b>Días</b>]]>
      </text> 
    </label>
	<label lid='1'>
      <x>20</x> 
      <y>50%</y>
      <rotate>true</rotate> 
      <width>100</width>
      <align>left</align>
      <text>
        <![CDATA[<b>%</b>]]>
      </text> 
    </label>
  </labels>
  <!--Me permite poner texto adicconal sobre la grafica -->
	<guides>	        
	 <max_min>true</max_min>
	</guides> 
<!-- rotar eje x -->
  <values> 
    <x>
      <rotate>45</rotate>
    </x>
  </values>
<!-- cantidad lineas vertical -->
  <grid> 
    <x>
      <approx_count>10</approx_count>
    </x>
  </grid>
  
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
    <no_data>No hay datos para los criterios seleccionados</no_data>
  </strings>   
  
</settings>"; ?>
