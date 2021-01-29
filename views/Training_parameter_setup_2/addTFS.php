<form class="form-horizontal" method="post">
    <div class="modal-header btn-primary">
        <h4 class="modal-title" id="myModalLabel">Add New Fee Type</h4>
    </div>
    <div class="modal-body">
        <div id="alert">
        	<b>Note : </b> ( <b><font color="red">*</font></b> ) <b><font color="red">compulsory fields</font></b><br>&nbsp; <span id="note"></span>
    	</div>
        <div class="form-group">
            <label class="col-md-3 control-label"><b>Code</b> <b><font color="red">* </font></b></label>
            <div class="col-md-9">
				<input name="form[code]" placeholder="Code" class="form-control" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label"><b>Description</b> <b><font color="red">* </font></b></label>
            <div class="col-md-9">
				<input name="form[description]" placeholder="Fee Description" class="form-control" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label"><b>Minimum Range (RM)</b> <b><font color="red">* </font></b></label>
            <div class="col-md-9">
				<input name="form[minimum_range]" placeholder="Minimum fee range" class="form-control" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label"><b>Maximum Range (RM)</b> <b><font color="red">* </font></b></label>
            <div class="col-md-9">
				<input name="form[maximum_range]" placeholder="Maximum fee range" class="form-control" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label"><b>DCR Approve </b></label>
            <div class="col-md-9">
                <?php
                    echo form_dropdown('form[dcr_approve]', $dept_list, '', 'class="form-control width-50"')
                ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-md-3 control-label"><b>Registrar Approve </b></label>
            <div class="col-md-9">
                <?php
                    echo form_dropdown('form[registrar_approve]', $dept_list, '', 'class="form-control width-50"')
                ?>
            </div>
        </div>

        <div class="form-group">
			<label class="col-md-3 control-label"><b>MPE Approve? </b><b><font color="red">* </font></b></label>
			<div class="col-md-4">
				<select class="selectpicker form-control" name="form[mpe_approve]">
					<option value="" selected > ---Please select--- </option>
					<option class="text-primary" value="Y">YES</option>
					<option class="text-success" value="N">NO</option>
				</select>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-3 control-label"><b>Status </b><b><font color="red">* </font></b></label>
			<div class="col-md-4">
				<select class="selectpicker form-control" name="form[status]">
					<option value="" selected > ---Please select--- </option>
					<option class="text-primary" value="Y">ACTIVE</option>
					<option class="text-success" value="N">INACTIVE</option>
				</select>
			</div>
		</div>

    </div>
    <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-hand-o-left"></i> Close</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
    </div>
</form>

<script>	
	$(document).ready(function(){	
		$('form').submit(function (e) { 
			e.preventDefault();
			var data = $('form').serialize();
			msg.wait('#alert');
			msg.wait('#alertFooter');
			
			$('.btn').attr('disabled', 'disabled');
			$.ajax({
				type: 'POST',
				url: '<?php echo $this->lib->class_url('saveTFS')?>',
				data: data,
				dataType: 'JSON',
				success: function(res) {
					msg.show(res.msg, res.alert, '#alert');
					//msg.show(res.msg, res.alert, '#alertFooter');
					
					if (res.sts == 1) {
						setTimeout(function () {
							location = '<?php echo $this->lib->class_url('viewTabFilter','s8')?>';
						}, 1000);
					} else {
						$('.btn').removeAttr('disabled');
					}
				},
				error: function() {
					$('.btn').removeAttr('disabled');
					msg.danger('Please contact administrator.', '#alert');
					//msg.danger('Please contact administrator.', '#alertFooter');
				}
			});	
		});  				

	});  	  
</script>