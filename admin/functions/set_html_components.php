<?php 
	function getRadioButton($value, $isChecked, $name){
		if($isChecked){
			echo '
			<label class="radio-inline icheck">
					<div  class="iradio_minimal-blue" style="position: relative;"><input checked type="radio" id="inlineradio1" value="'.$value.'" name="'.$name.'" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div> '.$value.'
			</label>';
		} else {
			echo '
			<label class="radio-inline icheck">
					<div  class="iradio_minimal-blue" style="position: relative;"><input type="radio" id="inlineradio1" value="'.$value.'" name="'.$name.'" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div> '.$value.'
			</label>';
		}
	}

?>