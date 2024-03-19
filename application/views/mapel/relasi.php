<div class="row align-items-center">
	<div class="col-6"><h3>Kelas  </h3></div>
</div>

<div class="row align-items-center mt-2">
	<div class="col-6" style="height:250px;overflow: auto;" >
		<?php
		$teacher_id =  $_GET['id'];
		$teacher_class = $this->db->where('teacher_id',$teacher_id)->get('class_teacher');

		$_arr_teacher_class = [];
		foreach ($teacher_class->result() as $obj) $_arr_teacher_class[] = $obj->class_id;

		$kelas = $this->db->order_by('class_id')->get('kelas');
		$i=0;
		foreach ($kelas->result() as $obj) {
			if(in_array($obj->class_id,$_arr_teacher_class)){ ?>

				<label><input  type="checkbox" name="teacher_class[]"  checked class=" teacher_class filled-in" id="teacher_class<?=$obj->class_id?>" value="<?=$obj->class_id?>" /></label>
				&nbsp;&nbsp;&nbsp;<?=$obj->class_name?><br />
			
			<?php 
			}else{ 
			?>
				<label><input  type="checkbox" name="teacher_class[]"    class="teacher_class filled-in"  id="teacher_class<?=$obj->class_id?>" value="<?=$obj->class_id?>" /></label>
				&nbsp;&nbsp;&nbsp;<?=$obj->class_name?><br />
				<?php
			}
				
			$i++;
		}
		?>
	
	</div>
</div>
